<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\EvaluationRule;
use App\Models\EvaluationResult;
use App\Models\EvaluationResultDetail;

class DssEvaluationService
{
    /**
     * Jalankan proses evaluasi DSS berbasis aturan (IF-THEN).
     */
    public function evaluate(MatchGame $match): EvaluationResult
    {
        $stats = $match->performanceStatistic;

        if (!$stats) {
            throw new \Exception("Pertandingan tidak memiliki data statistik untuk dievaluasi. Harap finalisasi terlebih dahulu.");
        }

        // Ambil semua aturan evaluasi yang aktif
        $rules = EvaluationRule::where('is_active', true)->orderBy('priority', 'asc')->get();

        // Siapkan variabel untuk menampung hasil
        $triggeredRulesDetails = [];
        $overallEvaluations = [];
        $improvementFocuses = [];

        // Buat atau perbarui hasil evaluasi induk
        $evaluationResult = EvaluationResult::updateOrCreate(
            ['match_game_id' => $match->id],
            [
                'summary_stats' => [
                    'total_rallies' => $stats->total_rallies,
                    'pair_points' => $stats->pair_points,
                    'opponent_points' => $stats->opponent_points,
                    'pair_errors' => $stats->pair_errors,
                    'dominant_error_type' => $stats->dominant_error_type,
                    'avg_stroke_count' => $stats->avg_stroke_count,
                    'avg_rally_duration' => $stats->avg_rally_duration,
                    'critical_point_errors' => $stats->critical_point_errors,
                    'total_critical_points' => $stats->total_critical_points,
                    'pair_point_percentage' => $stats->pair_point_percentage,
                ],
                'overall_evaluation' => '', // akan diisi nanti
                'improvement_focus' => '',  // akan diisi nanti
            ]
        );

        // Hapus detail evaluasi yang lama untuk di-generate ulang
        $evaluationResult->details()->delete();

        foreach ($rules as $rule) {
            $actualValue = $this->getActualValue($stats, $rule->condition_param);
            $isTriggered = $this->checkCondition($actualValue, $rule->condition_operator, $rule->condition_value);

            // Format string alasan & hasil evaluasi dengan nilai riil (mendukung [angka] dan [actual_value])
            $reason = str_replace(['[angka]', '[actual_value]'], $actualValue, $rule->evaluation_reason);
            
            if ($rule->condition_param === 'error_concentration' && $isTriggered && $stats->mostErrorPlayer) {
                $reason = str_replace(['[nama_pemain]', '[player_name]'], $stats->mostErrorPlayer->name, $reason);
            }

            // Simpan detail hasil evaluasi aturan
            EvaluationResultDetail::create([
                'evaluation_result_id' => $evaluationResult->id,
                'evaluation_rule_id' => $rule->id,
                'rule_name' => $rule->rule_name,
                'condition_description' => $rule->condition_logic,
                'actual_value' => (string)$actualValue,
                'evaluation_result_text' => $rule->evaluation_result,
                'evaluation_reason' => $reason,
                'is_triggered' => $isTriggered,
            ]);

            if ($isTriggered) {
                // Gunakan bahasa natural dalam pernyataan evaluasi
                $naturalStatement = $this->toNaturalStatement($rule->condition_param, $actualValue, $stats);
                $overallEvaluations[] = "- " . ($naturalStatement ?: $rule->evaluation_result . " (" . $reason . ")");
                
                // Tentukan fokus perbaikan berdasarkan indikator aturan
                $focus = $this->getImprovementFocusText($rule->condition_param, $stats);
                if ($focus && !in_array($focus, $improvementFocuses)) {
                    $improvementFocuses[] = $focus;
                }
            }
        }

        // Tambahkan evaluasi built-in yang tidak tergantung aturan dari DB
        $builtInEvals = $this->getBuiltInEvaluations($stats, $match);
        foreach ($builtInEvals['evaluations'] as $eval) {
            $overallEvaluations[] = "- " . $eval;
        }
        foreach ($builtInEvals['focuses'] as $focus) {
            if (!in_array($focus, $improvementFocuses)) {
                $improvementFocuses[] = $focus;
            }
        }

        // Gabungkan hasil teks evaluasi dengan bahasa alami
        $overallText = count($overallEvaluations) > 0
            ? "Berdasarkan rekaman data pertandingan, terdapat beberapa hal yang perlu diperhatikan:\n\n" . implode("\n", $overallEvaluations)
            : "Secara keseluruhan, pasangan ganda menunjukkan performa yang stabil dan konsisten. Tidak ditemukan indikator kelemahan menonjol dalam pertandingan ini. Pertahankan pola permainan yang ada dan tetap tingkatkan variasi serangan.";

        $focusText = count($improvementFocuses) > 0
            ? "Berikut adalah prioritas latihan yang disarankan untuk pertandingan ke depan:\n\n" . implode("\n", $improvementFocuses)
            : "Tetap jaga konsistensi dan stamina. Lakukan simulasi game secara rutin agar ritme bermain tetap terjaga.";

        // Update hasil akhir evaluasi
        $evaluationResult->update([
            'overall_evaluation' => $overallText,
            'improvement_focus' => $focusText,
        ]);

        // Update status pertandingan ke 'Dievaluasi'
        $match->update(['status' => 'Dievaluasi']);

        return $evaluationResult;
    }

    /**
     * Ambil nilai aktual statistik berdasarkan nama parameter.
     */
    private function getActualValue($stats, string $param)
    {
        switch ($param) {
            case 'pair_errors':
                return $stats->pair_errors;
            case 'pair_error_rate':
                return $stats->total_rallies > 0 ? round(($stats->pair_errors / $stats->total_rallies) * 100, 2) : 0;
            case 'dominant_error_type':
                return $stats->dominant_error_type ?? 'Tidak Ada';
            case 'error_concentration':
                return ($stats->detailed_stats['error_concentration'] ?? false) ? 'true' : 'false';
            case 'critical_point_error_rate':
                return $stats->total_critical_points > 0 ? round(($stats->critical_point_errors / $stats->total_critical_points) * 100, 2) : 0;
            case 'long_rally_win_rate':
                return $stats->detailed_stats['long_rally_win_rate'] ?? 0;
            case 'pair_point_percentage':
                return $stats->pair_point_percentage;
            case 'opponent_point_percentage':
                return $stats->opponent_point_percentage;
            case 'avg_stroke_count':
                return $stats->avg_stroke_count;
            case 'avg_rally_duration':
                return $stats->avg_rally_duration;
            default:
                return 0;
        }
    }

    /**
     * Cocokkan kondisi (IF) logika pembanding.
     */
    private function checkCondition($actual, string $operator, $value): bool
    {
        // Konversi tipe data
        if ($actual === 'true') $actual = true;
        if ($actual === 'false') $actual = false;
        if ($value === 'true') $value = true;
        if ($value === 'false') $value = false;

        if (is_numeric($actual) && is_numeric($value)) {
            $actual = floatval($actual);
            $value = floatval($value);
        }

        switch ($operator) {
            case '>':
                return $actual > $value;
            case '<':
                return $actual < $value;
            case '>=':
                return $actual >= $value;
            case '<=':
                return $actual <= $value;
            case '==':
                if (is_bool($actual) || is_bool($value)) {
                    return $actual === $value;
                }
                return strtolower(trim((string)$actual)) === strtolower(trim((string)$value));
            case '!=':
                if (is_bool($actual) || is_bool($value)) {
                    return $actual !== $value;
                }
                return strtolower(trim((string)$actual)) !== strtolower(trim((string)$value));
            default:
                return false;
        }
    }

    /**
     * Ubah hasil kondisi parameter menjadi kalimat alami yang mudah dipahami pelatih.
     */
    private function toNaturalStatement(string $param, $actualValue, $stats): string
    {
        switch ($param) {
            case 'pair_error_rate':
                return "Pasangan sering melakukan kesalahan sendiri ({$actualValue}% dari total rally adalah unforced error). Hal ini berdampak langsung pada kehilangan poin.";
            case 'dominant_error_type':
                $map = [
                    'Net' => 'Bola terlalu sering menyangkut di net',
                    'Out' => 'Pukulan sering keluar lapangan (out)',
                    'Miskomunikasi' => 'Terjadi miskomunikasi antar pemain saat mengambil bola',
                    'Serve' => 'Kesalahan saat melakukan servis',
                    'Smash' => 'Smash sering gagal atau tidak akurat',
                    'Drive' => 'Pukulan drive sering meleset atau tidak tepat sasaran',
                    'Lift' => 'Angkat bola (lift) kurang akurat',
                    'Lob' => 'Pukulan lob (melambung) sering gagal atau kurang akurat',
                    'Drop Shot' => 'Drop shot sering gagal atau terlalu tinggi',
                    'Netting' => 'Permainan di depan net (netting) masih lemah',
                    'Defence' => 'Bertahan dari serangan lawan masih menjadi titik lemah',
                    'Timing' => 'Timing pukulan masih sering tidak tepat',
                    'Footwork' => 'Footwork (pergerakan kaki) perlu diperbaiki',
                ];
                $desc = $map[$actualValue] ?? "Tipe error dominan: {$actualValue}";
                return "{$desc}. Ini adalah jenis kesalahan yang paling sering terjadi dalam pertandingan ini.";
            case 'error_concentration':
                $playerName = $stats->mostErrorPlayer->name ?? 'salah satu pemain';
                return "Beban kesalahan terpusat pada {$playerName}. Lawan tampaknya sudah menyadari kelemahan ini dan mengarahkan serangan ke arah pemain tersebut.";
            case 'critical_point_error_rate':
                return "Di momen-momen penentu (poin kritis), pasangan lebih sering melakukan kesalahan sendiri ({$actualValue}% error saat poin kritis). Ini menunjukkan perlu peningkatan ketahanan mental.";
            case 'long_rally_win_rate':
                return "Pasangan cenderung kalah dalam rally-rally panjang (win rate di long rally hanya {$actualValue}%). Stamina dan kesabaran dalam rally bertahan perlu ditingkatkan.";
            case 'pair_point_percentage':
                return "Persentase poin yang berhasil diraih pasangan cukup rendah ({$actualValue}%). Variasi serangan dan penempatan bola perlu lebih dioptimalkan.";
            case 'avg_stroke_count':
                if ($actualValue < 4) {
                    return "Rata-rata rally berlangsung sangat singkat ({$actualValue} pukulan). Pasangan sering dikalahkan sebelum sempat membangun serangan.";
                }
                return "Rata-rata rally cukup panjang ({$actualValue} pukulan). Stamina perlu dijaga konsistensinya.";
            case 'avg_rally_duration':
                return "Rata-rata durasi rally adalah {$actualValue} detik. Perhatikan efisiensi transisi antara bertahan dan menyerang.";
            default:
                return '';
        }
    }

    /**
     * Evaluasi built-in yang tidak bergantung pada aturan di database.
     * Mencakup: Defence, Lob, dan analisis Dominant Player direction.
     */
    private function getBuiltInEvaluations($stats, MatchGame $match): array
    {
        $evaluations = [];
        $focuses = [];

        // Ambil rally dengan detail error type dari match
        $rallies = $match->rallies;

        // --- 1. Analisis Defence ---
        $defenceErrors = $rallies->where('point_result', 'Error Sendiri')
            ->where('error_type', 'Defence')->count();
        $totalErrors = $rallies->where('point_result', 'Error Sendiri')->count();

        if ($totalErrors > 0 && $defenceErrors > 0) {
            $defenceErrorRate = round(($defenceErrors / $totalErrors) * 100, 1);
            if ($defenceErrorRate >= 20) {
                $evaluations[] = "Pasangan kesulitan dalam bertahan (defence). Sebanyak {$defenceErrorRate}% dari total error berasal dari situasi bertahan. Lawan berhasil menekan dengan smash atau serangan keras.";
                $focuses[] = "- Tingkatkan ketahanan bertahan (defence): latihan menerima smash, block net, dan lift dari posisi tertekan.";
            }
        }

        // --- 2. Analisis Netting & Drop Shot ---
        $nettingErrors = $rallies->where('point_result', 'Error Sendiri')
            ->whereIn('error_type', ['Netting', 'Drop Shot'])->count();
        if ($totalErrors > 0 && $nettingErrors > 0) {
            $nettingRate = round(($nettingErrors / $totalErrors) * 100, 1);
            if ($nettingRate >= 15) {
                $evaluations[] = "Permainan di depan net (netting/drop shot) masih rawan kesalahan. {$nettingRate}% error terjadi di situasi depan net, yang menunjukkan pasangan perlu lebih cermat dalam pukulan tipis net.";
                $focuses[] = "- Latihan netting dan drop shot: fokus pada akurasi pukulan tipis net dan teknik drop shot dari posisi tengah hingga belakang lapangan.";
            }
        }

        // --- 3. Analisis Dominant Player (arah serangan lawan) ---
        $errorByPlayer = [];
        if ($match->pair && $match->pair->player1_id && $match->pair->player2_id) {
            $p1Id = $match->pair->player1_id;
            $p2Id = $match->pair->player2_id;
            $p1Name = $match->pair->player1->name ?? 'Pemain 1';
            $p2Name = $match->pair->player2->name ?? 'Pemain 2';

            $p1Errors = $rallies->where('point_result', 'Error Sendiri')
                ->where('error_player_id', $p1Id)->count();
            $p2Errors = $rallies->where('point_result', 'Error Sendiri')
                ->where('error_player_id', $p2Id)->count();

            if ($totalErrors >= 5) {
                $p1Rate = $totalErrors > 0 ? round(($p1Errors / $totalErrors) * 100, 1) : 0;
                $p2Rate = $totalErrors > 0 ? round(($p2Errors / $totalErrors) * 100, 1) : 0;

                if ($p1Rate >= 65) {
                    $evaluations[] = "Lawan tampaknya mengincar {$p1Name} sebagai target utama serangan. {$p1Rate}% dari total error berasal dari {$p1Name}, menandakan lawan sudah mempelajari titik lemah ini.";
                    $focuses[] = "- Taktik mengalihkan tekanan dari {$p1Name}: latihan rotasi posisi dan komunikasi sehingga {$p1Name} tidak selalu menjadi sasaran empuk lawan.";
                } elseif ($p2Rate >= 65) {
                    $evaluations[] = "Lawan tampaknya mengincar {$p2Name} sebagai target utama serangan. {$p2Rate}% dari total error berasal dari {$p2Name}, menandakan lawan sudah mempelajari titik lemah ini.";
                    $focuses[] = "- Taktik mengalihkan tekanan dari {$p2Name}: latihan rotasi posisi dan komunikasi sehingga {$p2Name} tidak selalu menjadi sasaran empuk lawan.";
                }
            }
        }

        // --- 4. Analisis Faktor Non-Teknis ---
        $nonTechnicalTypes = ['Angin lapangan', 'Lantai licin', 'Cahaya silau/redup', 'Raket patah', 'Senar putus', 'Shuttlecock rusak', 'Human error (wasit)'];
        $nonTechErrors = $rallies->where('point_result', 'Error Sendiri')
            ->whereIn('error_type', $nonTechnicalTypes)->count();
        if ($nonTechErrors > 0) {
            $evaluations[] = "Terdapat {$nonTechErrors} error yang disebabkan faktor di luar kemampuan teknis pemain (faktor lapangan, peralatan, atau keputusan wasit). Hal ini perlu dicatat terpisah agar tidak memengaruhi analisis teknis.";
            $focuses[] = "- Persiapkan kondisi peralatan sebelum pertandingan: cek kondisi raket, senar, dan shuttlecock. Lakukan pemanasan ekstra jika kondisi lapangan (angin, cahaya) kurang ideal.";
        }

        return ['evaluations' => $evaluations, 'focuses' => $focuses];
    }

    /**
     * Dapatkan teks rekomendasi perbaikan taktis/latihan (coaching language).
     */
    private function getImprovementFocusText(string $param, $stats): string
    {
        switch ($param) {
            case 'pair_error_rate':
                return "- Kurangi kesalahan sendiri dengan memperbanyak latihan konsistensi pukulan dasar. Hindari pukulan berisiko tinggi (spekulatif) terutama saat skor sedang ketat atau di poin-poin penting.";

            case 'dominant_error_type':
                $errorType = $stats->dominant_error_type ?? '';
                if ($errorType === 'Net') {
                    return "- Latihan akurasi netting dan perbaiki teknik sentuhan bola di depan net. Biasakan melakukan drill netting 100 bola setiap sesi latihan agar pukulan lebih konsisten dan tidak menyangkut.";
                } elseif ($errorType === 'Out') {
                    return "- Latihan kontrol kekuatan pukulan dan batas lapangan (backcourt control). Perhatikan dosis tenaga pukulan sesuai jarak posisi pemain ke garis belakang lapangan.";
                } elseif ($errorType === 'Miskomunikasi') {
                    return "- Perbaiki komunikasi antar pemain di lapangan. Latihan rotasi ganda (rotation drill) dan biasakan menggunakan kode atau isyarat suara yang jelas saat mengambil bola di area tengah.";
                } elseif ($errorType === 'Serve') {
                    return "- Latihan berbagai variasi servis: servis pendek (low serve), servis flick, dan servis drive. Fokus pada konsistensi penempatan dan hindari service fault berulang.";
                } elseif ($errorType === 'Smash') {
                    return "- Latihan akurasi smash ke berbagai sudut lapangan. Jangan hanya melatih kekuatan smash — arahkan smash ke titik lemah lawan, bukan sekadar memukul sekeras-kerasnya.";
                } elseif ($errorType === 'Drive') {
                    return "- Latihan drive exchange (saling balas pukulan mendatar) untuk melatih refleks dan kontrol pukulan cepat. Fokus pada posisi raket yang siap dan pergelangan tangan yang luwes.";
                } elseif ($errorType === 'Lift') {
                    return "- Latihan pukulan lob dan lift dengan kontrol ketinggian dan kedalaman yang tepat. Bola harus naik cukup tinggi agar tidak menjadi smash empuk bagi lawan, dan jatuh di dekat garis belakang.";
                } elseif ($errorType === 'Lob') {
                    return "- Latihan pukulan lob (melambung) dengan kontrol ketinggian dan kedalaman yang tepat. Pastikan bola melambung cukup tinggi dan jatuh presisi di dekat garis belakang lapangan.";
                } elseif ($errorType === 'Drop Shot') {
                    return "- Latihan drop shot dari posisi tengah dan belakang lapangan. Fokus pada kelembutan sentuhan dan penempatan bola setipis mungkin di atas net agar lawan kesulitan mengembalikannya.";
                } elseif ($errorType === 'Netting') {
                    return "- Latihan netting konsisten: 50-100 repetisi netting per sesi. Perhatikan teknik memegang raket dan sudut pergelangan tangan saat menyentuh bola tipis di atas net.";
                } elseif ($errorType === 'Defence') {
                    return "- Latihan bertahan intensif: fokus pada penerimaan smash keras, block net cepat, dan kemampuan mengangkat bola (lift) dari posisi terdesak. Latihan dengan sparring partner yang agresif menyerang.";
                } elseif ($errorType === 'Timing') {
                    return "- Latihan membaca arah dan kecepatan bola sejak dini (anticipation drill). Perbaiki timing pukulan dengan latihan shadow badminton dan drill multi-bola untuk membiasakan tubuh bergerak tepat waktu.";
                } elseif ($errorType === 'Footwork') {
                    return "- Latihan footwork intensif: shadow badminton 6 sudut, lari shuttle run, dan ladder drill untuk memperbaiki kecepatan dan posisi kaki. Posisi kaki yang benar adalah fondasi pukulan yang akurat.";
                }
                return "- Lakukan latihan khusus untuk memperbaiki jenis kesalahan yang paling sering terjadi: " . ($errorType ?: '-') . ". Diskusikan dengan pelatih untuk menyusun program drill yang tepat sasaran.";

            case 'error_concentration':
                $playerName = $stats->mostErrorPlayer->name ?? 'pemain yang bersangkutan';
                return "- Berikan perhatian dan latihan individual lebih kepada {$playerName}: tingkatkan ketahanan mental, kondisi fisik, dan variasi pukulan agar tidak mudah dijadikan target serangan oleh lawan.";

            case 'critical_point_error_rate':
                return "- Latihan simulasi skenario skor kritis (mulai dari 18-18, 19-20, atau 20-20) agar pemain terbiasa bermain di bawah tekanan dan tidak panik saat menghadapi poin-poin penentu.";

            case 'long_rally_win_rate':
                return "- Tingkatkan stamina dan kesabaran membangun serangan. Latihan rally panjang minimal 15-20 pukulan per rally, dan shadow footwork untuk menjaga kualitas gerak saat kondisi kelelahan.";

            case 'pair_point_percentage':
                return "- Evaluasi dan variasikan pola serangan. Coba kombinasi smash-drop-drive yang tidak mudah dibaca lawan. Ciptakan lebih banyak kesempatan memenangkan poin secara aktif (winner), bukan hanya mengandalkan kesalahan lawan.";

            case 'avg_stroke_count':
                return "- Latihan return servis yang lebih variatif dan agresif agar pasangan tidak langsung ditekan di awal rally. Biasakan membangun serangan dari rally pendek sebelum memukul bola mematikan.";

            case 'avg_rally_duration':
                return "- Latih transisi dari bertahan ke menyerang dengan lebih cepat dan berani. Jangan ragu untuk mengambil inisiatif serangan di tengah rally agar tidak terus-terusan dalam posisi bertahan.";

            default:
                return "";
        }
    }
}

