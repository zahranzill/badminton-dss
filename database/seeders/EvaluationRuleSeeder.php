<?php

namespace Database\Seeders;

use App\Models\EvaluationRule;
use Illuminate\Database\Seeder;

class EvaluationRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // ============================================================
            // ATURAN 1: Sering Melakukan Error Sendiri
            // ============================================================
            [
                'rule_name' => 'Sering Melakukan Error Sendiri',
                'indicator' => 'Kesalahan Tidak Dipaksa (Unforced Error)',
                'condition_logic' => 'Jika lebih dari 30% rally diakhiri dengan kesalahan sendiri oleh pasangan',
                'condition_param' => 'pair_error_rate',
                'condition_operator' => '>',
                'condition_value' => '30',
                'evaluation_result' => 'Pasangan terlalu sering melakukan kesalahan sendiri. Pukulan yang tidak akurat atau pengambilan keputusan yang tergesa-gesa menjadi penyebab utama hilangnya poin.',
                'evaluation_reason' => 'Dari total rally yang dimainkan, [angka]% rally diakhiri dengan kesalahan sendiri pasangan — jauh melampaui batas wajar 30%.',
                'is_active' => true,
                'priority' => 1,
            ],

            // ============================================================
            // ATURAN 2: Bola Sering Menyangkut di Net
            // ============================================================
            [
                'rule_name' => 'Bola Sering Menyangkut di Net',
                'indicator' => 'Kesalahan Net (Bola Menyangkut)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah bola menyangkut di net',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Net',
                'evaluation_result' => 'Pasangan paling sering melakukan kesalahan berupa bola menyangkut di net. Pukulan terlalu rendah atau teknik netting yang kurang presisi menjadi akar masalahnya.',
                'evaluation_reason' => 'Kesalahan bola menyangkut di net adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 2,
            ],

            // ============================================================
            // ATURAN 3: Pukulan Sering Keluar Lapangan (Out)
            // ============================================================
            [
                'rule_name' => 'Pukulan Sering Keluar Lapangan (Out)',
                'indicator' => 'Kesalahan Pukulan Keluar (Out)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah bola keluar lapangan',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Out',
                'evaluation_result' => 'Pasangan paling sering memukul bola terlalu jauh hingga keluar garis lapangan. Kekuatan pukulan dan kontrol arah bola perlu lebih diperhatikan.',
                'evaluation_reason' => 'Kesalahan bola keluar lapangan (out) adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 3,
            ],

            // ============================================================
            // ATURAN 4: Sering Terjadi Salah Paham Antar Pemain
            // ============================================================
            [
                'rule_name' => 'Sering Terjadi Salah Paham Antar Pemain',
                'indicator' => 'Miskomunikasi Pasangan Ganda',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah miskomunikasi antar pemain',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Miskomunikasi',
                'evaluation_result' => 'Koordinasi dan komunikasi di lapangan antara kedua pemain masih bermasalah. Sering terjadi situasi di mana bola seharusnya dipukul salah satu pemain tetapi dibiarkan atau justru diperebutkan.',
                'evaluation_reason' => 'Kesalahan miskomunikasi antar pemain adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 4,
            ],

            // ============================================================
            // ATURAN 5: Sering Gagal Melakukan Servis
            // ============================================================
            [
                'rule_name' => 'Sering Gagal Melakukan Servis',
                'indicator' => 'Kesalahan Servis',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah kesalahan servis',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Serve',
                'evaluation_result' => 'Pasangan terlalu sering melakukan kesalahan servis (service fault). Ini adalah kelemahan serius karena servis adalah kesempatan emas untuk memulai rally dengan keuntungan.',
                'evaluation_reason' => 'Kesalahan servis adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 5,
            ],

            // ============================================================
            // ATURAN 6: Smash Sering Tidak Akurat atau Gagal
            // ============================================================
            [
                'rule_name' => 'Smash Sering Tidak Akurat atau Gagal',
                'indicator' => 'Kesalahan Smash',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah smash gagal atau keluar',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Smash',
                'evaluation_result' => 'Smash yang seharusnya menjadi senjata utama justru sering menjadi bumerang. Pukulan smash terlalu sering keluar atau menyangkut, sehingga poin berpindah ke lawan.',
                'evaluation_reason' => 'Kesalahan smash adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 6,
            ],

            // ============================================================
            // ATURAN 7: Sering Gagal Bertahan dari Serangan Lawan (Defence)
            // ============================================================
            [
                'rule_name' => 'Sering Gagal Bertahan dari Serangan Lawan',
                'indicator' => 'Kelemahan Bertahan (Defence)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah gagal bertahan dari serangan',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Defence',
                'evaluation_result' => 'Pasangan kesulitan menghadapi serangan keras atau menekan dari lawan. Saat dalam posisi bertahan, pasangan sering gagal mengembalikan bola sehingga lawan mudah mematikan.',
                'evaluation_reason' => 'Kegagalan bertahan (defence) adalah jenis kesalahan yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 7,
            ],

            // ============================================================
            // ATURAN 8: Drop Shot dan Netting Sering Gagal
            // ============================================================
            [
                'rule_name' => 'Permainan Depan Net Sering Gagal',
                'indicator' => 'Kelemahan Drop Shot dan Netting',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah drop shot atau netting gagal',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Netting',
                'evaluation_result' => 'Permainan di depan net (netting dan drop shot) menjadi titik lemah pasangan. Pukulan tipis di atas net sering tidak akurat sehingga menjadi peluang bagi lawan untuk menyerang.',
                'evaluation_reason' => 'Kesalahan netting dan drop shot adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 8,
            ],

            // ============================================================
            // ATURAN 9: Satu Pemain Jadi Sasaran Empuk Lawan
            // ============================================================
            [
                'rule_name' => 'Satu Pemain Jadi Sasaran Empuk Lawan',
                'indicator' => 'Beban Kesalahan Menumpuk pada Satu Pemain',
                'condition_logic' => 'Jika lebih dari 60% total kesalahan pasangan ditanggung oleh satu pemain saja',
                'condition_param' => 'error_concentration',
                'condition_operator' => '==',
                'condition_value' => 'true',
                'evaluation_result' => 'Lawan tampaknya sudah mengenali titik lemah pasangan dan secara sengaja mengarahkan hampir semua serangan ke satu pemain yang lebih rentan. Pemain tersebut perlu mendapat perhatian dan dukungan ekstra dari rekannya.',
                'evaluation_reason' => 'Pemain [nama_pemain] menyumbangkan sebagian besar kesalahan dalam pertandingan ini, sehingga terlihat menjadi sasaran utama serangan lawan.',
                'is_active' => true,
                'priority' => 9,
            ],

            // ============================================================
            // ATURAN 10: Sering Melakukan Kesalahan di Poin Penentu
            // ============================================================
            [
                'rule_name' => 'Sering Melakukan Kesalahan di Poin Penentu',
                'indicator' => 'Ketangguhan Mental di Poin Kritis',
                'condition_logic' => 'Jika lebih dari 40% kesalahan terjadi pada poin-poin kritis (skor 18 ke atas atau poin penentu set)',
                'condition_param' => 'critical_point_error_rate',
                'condition_operator' => '>',
                'condition_value' => '40',
                'evaluation_result' => 'Pasangan cenderung kehilangan kendali dan membuat kesalahan justru di saat-saat paling krusial dan menegangkan. Ini mengindikasikan perlunya latihan mental dan pengendalian tekanan dalam situasi skor ketat.',
                'evaluation_reason' => 'Sebanyak [angka]% dari total kesalahan terjadi pada situasi poin kritis, jauh di atas batas wajar 40%.',
                'is_active' => true,
                'priority' => 10,
            ],

            // ============================================================
            // ATURAN 11: Lemah Saat Rally Berlangsung Lama
            // ============================================================
            [
                'rule_name' => 'Lemah Saat Rally Berlangsung Lama',
                'indicator' => 'Stamina dan Efektivitas Rally Panjang',
                'condition_logic' => 'Jika pasangan sering kalah pada rally-rally yang berjalan lama (lebih dari rata-rata pukulan)',
                'condition_param' => 'long_rally_win_rate',
                'condition_operator' => '<',
                'condition_value' => '40',
                'evaluation_result' => 'Pasangan kesulitan memenangkan rally yang berlangsung lama. Stamina yang menurun atau ketidaksabaran membangun serangan membuat pasangan akhirnya membuat kesalahan sendiri saat rally memanjang.',
                'evaluation_reason' => 'Hanya [angka]% rally panjang yang berhasil dimenangkan oleh pasangan.',
                'is_active' => true,
                'priority' => 11,
            ],

            // ============================================================
            // ATURAN 12: Lawan Lebih Mendominasi Perolehan Poin
            // ============================================================
            [
                'rule_name' => 'Lawan Lebih Mendominasi Perolehan Poin',
                'indicator' => 'Dominasi Poin oleh Lawan',
                'condition_logic' => 'Jika pasangan hanya berhasil meraih kurang dari 45% dari total poin yang diperebutkan',
                'condition_param' => 'pair_point_percentage',
                'condition_operator' => '<',
                'condition_value' => '45',
                'evaluation_result' => 'Secara keseluruhan lawan lebih mendominasi jalur perolehan poin. Pola permainan pasangan kurang variatif dan mudah dibaca lawan, sehingga lawan lebih sering unggul dalam meraih poin.',
                'evaluation_reason' => 'Pasangan hanya berhasil meraih [angka]% dari total poin yang dimainkan — lawan lebih unggul dalam perolehan poin.',
                'is_active' => true,
                'priority' => 12,
            ],

            // ============================================================
            // ATURAN 13: Pasangan Mudah Dikalahkan di Awal Rally
            // ============================================================
            [
                'rule_name' => 'Pasangan Mudah Dikalahkan di Awal Rally',
                'indicator' => 'Kerentanan di Awal Rally (Pukulan Pendek)',
                'condition_logic' => 'Jika rata-rata rally hanya berlangsung kurang dari 5 kali pukulan sebelum mati',
                'condition_param' => 'avg_stroke_count',
                'condition_operator' => '<',
                'condition_value' => '5',
                'evaluation_result' => 'Sebagian besar rally berakhir sangat cepat, sering kali sebelum pasangan sempat membangun serangan. Ini menunjukkan pasangan sering langsung ditekan atau kalah di fase awal rally (servis dan return servis).',
                'evaluation_reason' => 'Rata-rata rally hanya berlangsung [angka] kali pukulan — menunjukkan rally berlangsung sangat singkat.',
                'is_active' => true,
                'priority' => 13,
            ],

            // ============================================================
            // ATURAN 14: Pertandingan Berlangsung Melelahkan (Rally Panjang)
            // ============================================================
            [
                'rule_name' => 'Pertandingan Berlangsung Melelahkan (Rally Panjang)',
                'indicator' => 'Pola Permainan Adu Bertahan',
                'condition_logic' => 'Jika rata-rata durasi setiap rally lebih dari 12 detik',
                'condition_param' => 'avg_rally_duration',
                'condition_operator' => '>',
                'condition_value' => '12',
                'evaluation_result' => 'Pertandingan berlangsung dengan intensitas tinggi dan rally yang melelahkan. Kedua pasangan cenderung adu ketahanan dan bertahan panjang. Kondisi ini sangat menguras stamina dan membutuhkan konsentrasi penuh sepanjang pertandingan.',
                'evaluation_reason' => 'Rata-rata durasi setiap rally adalah [angka] detik — tergolong rally yang panjang dan melelahkan.',
                'is_active' => true,
                'priority' => 14,
            ],

            // ============================================================
            // ATURAN 15: Pukulan Lob Sering Gagal atau Tidak Tepat
            // ============================================================
            [
                'rule_name' => 'Pukulan Lob Sering Gagal atau Terlalu Pendek',
                'indicator' => 'Kelemahan Pukulan Lob (Lift)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah pukulan lob atau lift yang gagal',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Lift',
                'evaluation_result' => 'Pukulan lob atau lift yang seharusnya menjadi cara keluar dari tekanan justru sering gagal. Bola yang terlalu pendek atau terlalu rendah memberi kesempatan lawan untuk langsung menyerang dengan smash.',
                'evaluation_reason' => 'Kesalahan pukulan lob/lift adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 15,
            ],

            // ============================================================
            // ATURAN 16: Drop Shot Sering Gagal
            // ============================================================
            [
                'rule_name' => 'Drop Shot Sering Tidak Tepat Sasaran',
                'indicator' => 'Kelemahan Drop Shot (Pukulan Pendek ke Depan Net)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah drop shot yang tidak masuk atau terlalu tinggi',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Drop Shot',
                'evaluation_result' => 'Drop shot yang seharusnya menjadi variasi serangan mematikan justru sering berakhir dengan kesalahan. Pukulan terlalu tinggi atau tidak cukup tipis membuat lawan mudah mengembalikan bola dan balik menyerang.',
                'evaluation_reason' => 'Kesalahan drop shot adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 16,
            ],

            // ============================================================
            // ATURAN 17: Drive Sering Gagal
            // ============================================================
            [
                'rule_name' => 'Pukulan Cepat Mendatar (Drive) Sering Meleset',
                'indicator' => 'Kelemahan Pukulan Drive',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah pukulan drive atau pukulan datar yang gagal',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Drive',
                'evaluation_result' => 'Pasangan sering melakukan kesalahan saat melakukan pukulan cepat mendatar (drive). Ini biasanya terjadi karena timing yang kurang tepat atau posisi raket yang tidak siap menerima bola cepat dari lawan.',
                'evaluation_reason' => 'Kesalahan pukulan drive adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 17,
            ],

            // ============================================================
            // ATURAN 18: Timing Pukulan Sering Terlambat atau Terlalu Awal
            // ============================================================
            [
                'rule_name' => 'Waktu Pukul Sering Tidak Pas (Timing Buruk)',
                'indicator' => 'Masalah Timing Pukulan',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi adalah timing pukulan yang kurang tepat',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Timing',
                'evaluation_result' => 'Pasangan sering memukul bola pada waktu yang kurang tepat — entah terlalu awal sebelum bola mencapai posisi ideal, atau terlalu terlambat sehingga tenaga tidak tersalurkan dengan baik. Akibatnya bola menjadi tidak terarah dan mudah dimakan lawan.',
                'evaluation_reason' => 'Kesalahan timing pukulan adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 18,
            ],

            // ============================================================
            // ATURAN 19: Footwork Buruk / Posisi Kaki Sering Salah
            // ============================================================
            [
                'rule_name' => 'Posisi Kaki Sering Salah (Footwork Lemah)',
                'indicator' => 'Kelemahan Pergerakan dan Posisi Kaki (Footwork)',
                'condition_logic' => 'Jika jenis kesalahan yang paling banyak terjadi disebabkan oleh posisi kaki yang salah atau pergerakan yang lambat',
                'condition_param' => 'dominant_error_type',
                'condition_operator' => '==',
                'condition_value' => 'Footwork',
                'evaluation_result' => 'Banyak kesalahan dalam pertandingan ini berakar dari pergerakan kaki yang kurang baik. Posisi tubuh yang tidak ideal saat memukul membuat pukulan menjadi tidak terkontrol dan arah bola tidak bisa diprediksi.',
                'evaluation_reason' => 'Kesalahan yang disebabkan footwork buruk adalah yang paling dominan dalam pertandingan ini.',
                'is_active' => true,
                'priority' => 19,
            ],
        ];


        foreach ($rules as $rule) {
            EvaluationRule::updateOrCreate(
                ['rule_name' => $rule['rule_name']],
                $rule
            );
        }

        // Nonaktifkan aturan-aturan lama yang namanya masih teknis
        // (tidak dihapus karena mungkin sudah dipakai di riwayat evaluasi)
        $oldTechnicalNames = [
            'Error Rate Tinggi',
            'Error Dominan Netting',
            'Error Dominan Out',
            'Error Dominan Miskomunikasi',
            'Konsentrasi Kesalahan pada Satu Pemain',
            'Tingkat Error Poin Kritis Tinggi',
            'Kurang Efektif pada Rally Panjang',
            'Persentase Perolehan Poin Rendah',
            'Kecenderungan Rally Pendek',
            'Dominasi Rally Panjang',
        ];

        EvaluationRule::whereIn('rule_name', $oldTechnicalNames)
            ->update(['is_active' => false]);
    }
}

