<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Models\EvaluationResult;
use App\Services\DssEvaluationService;
use Illuminate\Http\Request;

class EvaluationController extends Controller
{
    protected $dssService;

    public function __construct(DssEvaluationService $dssService)
    {
        $this->dssService = $dssService;
    }

    /**
     * Jalankan modul DSS untuk mencocokkan statistik dengan aturan.
     */
    public function run(MatchGame $match)
    {
        // Hanya bisa dievaluasi jika statusnya Final (atau sudah Dievaluasi sebelumnya untuk hitung ulang)
        if (!in_array($match->status, ['Final', 'Dievaluasi'])) {
            return redirect()->route('matches.show', $match->id)
                ->with('error', 'Evaluasi DSS hanya dapat diproses setelah data difinalisasi.');
        }

        try {
            $this->dssService->evaluate($match);

            return redirect()->route('evaluations.show', $match->id)
                ->with('success', 'Modul DSS berhasil menganalisis pertandingan ini.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses evaluasi: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan hasil evaluasi pertandingan.
     */
    public function show(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'evaluationResult.details.evaluationRule', 'rallies.errorPlayer', 'performanceStatistic']);

        if (!$match->evaluationResult) {
            return redirect()->route('statistics.show', $match->id)
                ->with('error', 'Hasil evaluasi belum dibuat. Silakan jalankan modul DSS terlebih dahulu.');
        }

        $result = $match->evaluationResult;
        
        // Dapatkan hanya detail aturan yang bernilai true (terpicu)
        $triggeredDetails = $result->details->where('is_triggered', true);

        // === BUILD KNOWLEDGE GRAPH DATA ===
        $graphData = $this->buildKnowledgeGraphData($match, $result, $triggeredDetails);

        return view('evaluations.show', compact('match', 'result', 'triggeredDetails', 'graphData'));
    }

    /**
     * Bangun data Knowledge Graph (nodes & edges) untuk visualisasi.
     */
    private function buildKnowledgeGraphData(MatchGame $match, $result, $triggeredDetails): array
    {
        $nodes = [];
        $edges = [];
        $nodeId = 1;

        // === LEVEL 0: Pasangan Ganda (Root) ===
        $pairNodeId = $nodeId++;
        $nodes[] = [
            'id' => $pairNodeId,
            'label' => "Pasangan Ganda\n" . ($match->pair->name ?? 'Pasangan'),
            'group' => 'pair',
            'level' => 0,
            'title' => 'Pasangan Ganda yang Dievaluasi',
            'shape' => 'circle',
            'size' => 32,
        ];

        // === LEVEL 1: Pemain 1 & Pemain 2 ===
        $player1NodeId = null;
        $player2NodeId = null;
        if ($match->pair && $match->pair->player1) {
            $player1NodeId = $nodeId++;
            $nodes[] = [
                'id' => $player1NodeId,
                'label' => "Pemain 1\n" . $match->pair->player1->name,
                'group' => 'player',
                'level' => 1,
                'title' => 'Pemain 1 dari Pasangan',
                'shape' => 'box',
                'size' => 24,
            ];
            $edges[] = ['from' => $pairNodeId, 'to' => $player1NodeId, 'label' => 'Anggota', 'color' => '#3b82f6', 'width' => 1.5];
        }
        if ($match->pair && $match->pair->player2) {
            $player2NodeId = $nodeId++;
            $nodes[] = [
                'id' => $player2NodeId,
                'label' => "Pemain 2\n" . $match->pair->player2->name,
                'group' => 'player',
                'level' => 1,
                'title' => 'Pemain 2 dari Pasangan',
                'shape' => 'box',
                'size' => 24,
            ];
            $edges[] = ['from' => $pairNodeId, 'to' => $player2NodeId, 'label' => 'Anggota', 'color' => '#3b82f6', 'width' => 1.5];
        }

        // === LEVEL 2: Statistik Pertandingan & Error Types ===
        $stats = $match->performanceStatistic;
        $statsNodeId = $nodeId++;
        $errorRate = $stats && $stats->total_rallies > 0 ? round(($stats->pair_errors / $stats->total_rallies) * 100, 1) : 0;
        $nodes[] = [
            'id' => $statsNodeId,
            'label' => "Statistik Laga\nError Rate: {$errorRate}%",
            'group' => 'stat',
            'level' => 2,
            'title' => "Total Rally: " . ($stats->total_rallies ?? 0) . ", Pair Errors: " . ($stats->pair_errors ?? 0),
            'shape' => 'box',
            'size' => 22,
        ];
        $edges[] = ['from' => $pairNodeId, 'to' => $statsNodeId, 'label' => 'Hasil Statistik', 'color' => '#6366f1', 'width' => 1.5];

        // Error Types (Level 2)
        $errorTypes = $match->rallies->where('point_result', 'Error Sendiri')
            ->whereNotNull('error_type')
            ->groupBy('error_type')
            ->map->count()
            ->sortDesc();

        $errorNodeIds = [];
        foreach ($errorTypes as $errorType => $count) {
            $eid = $nodeId++;
            $errorNodeIds[$errorType] = $eid;
            $nodes[] = [
                'id' => $eid,
                'label' => "Error: {$errorType}\n({$count}x)",
                'group' => 'error',
                'level' => 2,
                'title' => "Jenis Error: {$errorType} — Terjadi {$count} kali",
                'shape' => 'diamond',
                'size' => max(18, min(30, 14 + $count * 3)),
            ];
            $edges[] = ['from' => $statsNodeId, 'to' => $eid, 'label' => "{$count}x", 'color' => '#f43f5e', 'width' => 1.5];

            // Hubungkan error ke pemain spesifik
            $playersWithError = $match->rallies->where('point_result', 'Error Sendiri')
                ->where('error_type', $errorType)
                ->whereNotNull('error_player_id')
                ->groupBy('error_player_id')
                ->map->count();

            foreach ($playersWithError as $playerId => $errCount) {
                $targetPlayerNodeId = null;
                if ($match->pair->player1 && $match->pair->player1->id == $playerId) {
                    $targetPlayerNodeId = $player1NodeId;
                } elseif ($match->pair->player2 && $match->pair->player2->id == $playerId) {
                    $targetPlayerNodeId = $player2NodeId;
                }
                if ($targetPlayerNodeId) {
                    $edges[] = ['from' => $targetPlayerNodeId, 'to' => $eid, 'label' => "{$errCount}x error", 'color' => '#fb923c', 'width' => 1.5];
                }
            }
        }

        // === LEVEL 3: Aturan DSS yang Terpicu ===
        $ruleNodeIds = [];
        foreach ($triggeredDetails as $detail) {
            $rid = $nodeId++;
            $ruleNodeIds[$detail->id] = $rid;
            $nodes[] = [
                'id' => $rid,
                'label' => "Aturan Terpicu\n{$detail->rule_name}",
                'group' => 'rule',
                'level' => 3,
                'title' => "Aturan DSS: {$detail->rule_name}\nKondisi: {$detail->condition_description}",
                'shape' => 'box',
                'size' => 22,
            ];

            // Hubungkan statistik ke aturan terpicu
            $edges[] = ['from' => $statsNodeId, 'to' => $rid, 'label' => 'Memicu Aturan', 'color' => '#8b5cf6', 'width' => 1.5];
        }

        // === LEVEL 4: Rekomendasi Program Latihan ===
        $focusLines = array_filter(explode("\n", $result->improvement_focus), fn($l) => str_starts_with(trim($l), '-'));
        $drillIndex = 0;
        $ruleKeys = array_values($ruleNodeIds);
        foreach ($focusLines as $line) {
            $did = $nodeId++;
            $shortLabel = mb_substr(ltrim(trim($line), '- '), 0, 45) . '...';
            $nodes[] = [
                'id' => $did,
                'label' => "Rekomendasi Latihan\n{$shortLabel}",
                'group' => 'drill',
                'level' => 4,
                'title' => "Rekomendasi Program Latihan:\n" . ltrim(trim($line), '- '),
                'shape' => 'box',
                'size' => 20,
            ];

            // Hubungkan drill ke aturan terpicu
            if (isset($ruleKeys[$drillIndex])) {
                $edges[] = ['from' => $ruleKeys[$drillIndex], 'to' => $did, 'label' => 'Saran Drill', 'color' => '#f59e0b', 'width' => 1.5];
            } else {
                $edges[] = ['from' => $statsNodeId, 'to' => $did, 'label' => 'Saran Drill', 'color' => '#f59e0b', 'width' => 1.5];
            }
            $drillIndex++;
        }

        return ['nodes' => $nodes, 'edges' => $edges];
    }

    /**
     * Simpan catatan pelatih mengenai evaluasi.
     */
    public function updateNotes(Request $request, MatchGame $match)
    {
        $request->validate([
            'coach_notes' => 'nullable|string',
        ]);

        $result = EvaluationResult::where('match_game_id', $match->id)->first();

        if ($result) {
            $result->update([
                'coach_notes' => $request->coach_notes,
            ]);
        }

        return back()->with('success', 'Catatan pelatih berhasil disimpan.');
    }
}
