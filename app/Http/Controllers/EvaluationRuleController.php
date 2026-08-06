<?php

namespace App\Http\Controllers;

use App\Models\EvaluationRule;
use Illuminate\Http\Request;

class EvaluationRuleController extends Controller
{
    public function index()
    {
        $rules = EvaluationRule::orderBy('priority', 'asc')->paginate(15);

        // Summary statistics
        $totalRules = EvaluationRule::count();
        $activeRules = EvaluationRule::where('is_active', true)->count();
        $inactiveRules = EvaluationRule::where('is_active', false)->count();
        $uniqueIndicators = EvaluationRule::where('is_active', true)->distinct('condition_param')->count('condition_param');

        // Build Global Knowledge Graph data
        $allRules = EvaluationRule::where('is_active', true)->orderBy('priority', 'asc')->get();
        $globalGraphData = $this->buildGlobalKnowledgeGraph($allRules);

        return view('evaluation-rules.index', compact('rules', 'totalRules', 'activeRules', 'inactiveRules', 'uniqueIndicators', 'globalGraphData'));
    }

    /**
     * Bangun data Global Knowledge Graph dari seluruh aturan aktif.
     * Menampilkan peta taksonomi pengetahuan DSS secara utuh.
     */
    private function buildGlobalKnowledgeGraph($allRules): array
    {
        $nodes = [];
        $edges = [];
        $nodeId = 1;

        // Label mapping parameter → bahasa pelatih
        $paramLabels = [
            'pair_error_rate' => "Tingkat Error\nPasangan (%)",
            'pair_errors' => "Total Error\nPasangan",
            'dominant_error_type' => "Jenis Error\nDominan",
            'error_concentration' => "Ketidakseimbangan\nError Pemain",
            'critical_point_error_rate' => "Error di\nPoin Kritis (%)",
            'long_rally_win_rate' => "Win Rate\nRally Panjang (%)",
            'pair_point_percentage' => "Persentase Poin\nPasangan (%)",
            'opponent_point_percentage' => "Persentase Poin\nLawan (%)",
            'avg_stroke_count' => "Rata-rata\nJumlah Pukulan",
            'avg_rally_duration' => "Rata-rata\nDurasi Rally",
        ];

        // LEVEL 0: Data Input (Fakta Pertandingan)
        $inputNodeId = $nodeId++;
        $nodes[] = [
            'id' => $inputNodeId,
            'label' => "Data Input\n(Statistik Rally Pertandingan)",
            'group' => 'input',
            'level' => 0,
            'title' => 'Data Input: Fakta statistik pertandingan (Skor, Rally, Error, Pukulan, Durasi)',
            'shape' => 'box',
            'size' => 24,
        ];

        // LEVEL 2: Inference Engine (Mesin Inferensi Utama)
        $engineNodeId = $nodeId++;
        $nodes[] = [
            'id' => $engineNodeId,
            'label' => "Inference Engine\n(Mesin Evaluasi DSS PB Garles)",
            'group' => 'system',
            'level' => 2,
            'title' => 'Inference Engine: Memproses pencocokan fakta statistik dengan aturan berbasis logika IF-THEN',
            'shape' => 'circle',
            'size' => 32,
        ];

        // LEVEL 5: Keputusan Output (Hasil Akhir Evaluasi DSS)
        $outputNodeId = $nodeId++;
        $nodes[] = [
            'id' => $outputNodeId,
            'label' => "Keputusan Output\n(Laporan Evaluasi & Rekomendasi Latihan)",
            'group' => 'output',
            'level' => 5,
            'title' => 'Keputusan Output: Hasil akhir penilaian performa dan program latihan rekomendasi pelatih',
            'shape' => 'box',
            'size' => 26,
        ];

        // Kelompokkan aturan berdasarkan condition_param (= Kategori Indikator)
        $groupedRules = $allRules->groupBy('condition_param');

        foreach ($groupedRules as $param => $rulesInGroup) {
            // LEVEL 1: Indikator Kinerja
            $indicatorId = $nodeId++;
            $label = $paramLabels[$param] ?? $param;
            $nodes[] = [
                'id' => $indicatorId,
                'label' => $label,
                'group' => 'indicator',
                'level' => 1,
                'title' => "Indikator Kinerja: {$param}\nJumlah aturan aktif: {$rulesInGroup->count()}",
                'shape' => 'diamond',
                'size' => 22,
            ];
            
            // Level 0 (Data Input) -> Level 1 (Indikator Kinerja)
            $edges[] = [
                'from' => $inputNodeId, 
                'to' => $indicatorId, 
                'label' => 'Mengukur', 
                'color' => '#3b82f6',
                'width' => 1.5
            ];

            // Level 1 (Indikator Kinerja) -> Level 2 (Inference Engine)
            $edges[] = [
                'from' => $indicatorId, 
                'to' => $engineNodeId, 
                'label' => 'Input Ke Engine', 
                'color' => '#8b5cf6',
                'width' => 1.5
            ];

            // LEVEL 3: Aturan (IF) & LEVEL 4: Evaluasi (THEN)
            foreach ($rulesInGroup as $rule) {
                // Level 3: Aturan (IF)
                $ruleNodeId = $nodeId++;
                $nodes[] = [
                    'id' => $ruleNodeId,
                    'label' => "Aturan (IF)\n{$rule->rule_name}",
                    'group' => 'rule',
                    'level' => 3,
                    'title' => "Aturan (IF): {$rule->rule_name}\nKondisi: IF ({$rule->condition_param} {$rule->condition_operator} {$rule->condition_value})",
                    'shape' => 'box',
                    'size' => 20,
                ];

                // Level 2 (Inference Engine) -> Level 3 (Aturan IF)
                $edges[] = [
                    'from' => $engineNodeId, 
                    'to' => $ruleNodeId, 
                    'label' => "Uji Aturan", 
                    'color' => '#64748b',
                    'width' => 1.5
                ];

                // Level 4: Evaluasi (THEN)
                $evalNodeId = $nodeId++;
                $evalShort = mb_substr($rule->evaluation_result, 0, 45);
                if (mb_strlen($rule->evaluation_result) > 45) $evalShort .= '...';

                $nodes[] = [
                    'id' => $evalNodeId,
                    'label' => "Evaluasi (THEN)\n{$evalShort}",
                    'group' => 'evaluation',
                    'level' => 4,
                    'title' => "Evaluasi (THEN):\n{$rule->evaluation_result}",
                    'shape' => 'box',
                    'size' => 18,
                ];

                // Level 3 (Aturan IF) -> Level 4 (Evaluasi THEN)
                $edges[] = [
                    'from' => $ruleNodeId, 
                    'to' => $evalNodeId, 
                    'label' => 'THEN', 
                    'color' => '#f43f5e',
                    'width' => 1.5
                ];

                // Level 4 (Evaluasi THEN) -> Level 5 (Keputusan Output)
                $edges[] = [
                    'from' => $evalNodeId, 
                    'to' => $outputNodeId, 
                    'label' => 'Menghasilkan', 
                    'color' => '#f59e0b',
                    'width' => 1.5
                ];
            }
        }

        return ['nodes' => $nodes, 'edges' => $edges];
    }

    public function create()
    {
        $params = [
            'pair_errors' => 'Total Error Pasangan (kali)',
            'pair_error_rate' => 'Tingkat Error Pasangan (%)',
            'dominant_error_type' => 'Jenis Error Dominan (Net, Out, dll)',
            'error_concentration' => 'Ketidakseimbangan Error Pemain (true/false)',
            'critical_point_error_rate' => 'Tingkat Error Poin Kritis (%)',
            'long_rally_win_rate' => 'Win Rate Rally Panjang (%)',
            'pair_point_percentage' => 'Persentase Poin Pasangan (%)',
            'opponent_point_percentage' => 'Persentase Poin Lawan (%)',
            'avg_stroke_count' => 'Rata-rata Jumlah Pukulan (kali)',
            'avg_rally_duration' => 'Rata-rata Durasi Rally (detik)',
        ];

        $operators = [
            '>' => 'Lebih Dari (>)',
            '<' => 'Kurang Dari (<)',
            '>=' => 'Lebih Dari Sama Dengan (>=)',
            '<=' => 'Kurang Dari Sama Dengan (<=)',
            '==' => 'Sama Dengan (==)',
            '!=' => 'Tidak Sama Dengan (!=)',
        ];

        return view('evaluation-rules.create', compact('params', 'operators'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rule_name' => 'required|string|max:255',
            'indicator' => 'required|string|max:255',
            'condition_logic' => 'required|string',
            'condition_param' => 'required|string|max:100',
            'condition_operator' => 'required|string|max:20',
            'condition_value' => 'required|string|max:100',
            'evaluation_result' => 'required|string',
            'evaluation_reason' => 'required|string',
            'priority' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'rule_name.required' => 'Nama aturan wajib diisi.',
            'indicator.required' => 'Indikator wajib diisi.',
            'condition_logic.required' => 'Logika kondisi IF wajib ditulis.',
            'condition_param.required' => 'Parameter kondisi wajib dipilih.',
            'condition_operator.required' => 'Operator pembanding wajib dipilih.',
            'condition_value.required' => 'Nilai threshold wajib diisi.',
            'evaluation_result.required' => 'Hasil evaluasi wajib diisi.',
            'evaluation_reason.required' => 'Alasan evaluasi wajib diisi.',
            'priority.required' => 'Prioritas urutan wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        EvaluationRule::create($validated);

        return redirect()->route('evaluation-rules.index')->with('success', 'Aturan evaluasi berhasil ditambahkan.');
    }

    public function edit(EvaluationRule $evaluation_rule)
    {
        $rule = $evaluation_rule;

        $params = [
            'pair_errors' => 'Total Error Pasangan (kali)',
            'pair_error_rate' => 'Tingkat Error Pasangan (%)',
            'dominant_error_type' => 'Jenis Error Dominan (Net, Out, dll)',
            'error_concentration' => 'Ketidakseimbangan Error Pemain (true/false)',
            'critical_point_error_rate' => 'Tingkat Error Poin Kritis (%)',
            'long_rally_win_rate' => 'Win Rate Rally Panjang (%)',
            'pair_point_percentage' => 'Persentase Poin Pasangan (%)',
            'opponent_point_percentage' => 'Persentase Poin Lawan (%)',
            'avg_stroke_count' => 'Rata-rata Jumlah Pukulan (kali)',
            'avg_rally_duration' => 'Rata-rata Durasi Rally (detik)',
        ];

        $operators = [
            '>' => 'Lebih Dari (>)',
            '<' => 'Kurang Dari (<)',
            '>=' => 'Lebih Dari Sama Dengan (>=)',
            '<=' => 'Kurang Dari Sama Dengan (<=)',
            '==' => 'Sama Dengan (==)',
            '!=' => 'Tidak Sama Dengan (!=)',
        ];

        return view('evaluation-rules.edit', compact('rule', 'params', 'operators'));
    }

    public function update(Request $request, EvaluationRule $evaluation_rule)
    {
        $rule = $evaluation_rule;

        $validated = $request->validate([
            'rule_name' => 'required|string|max:255',
            'indicator' => 'required|string|max:255',
            'condition_logic' => 'required|string',
            'condition_param' => 'required|string|max:100',
            'condition_operator' => 'required|string|max:20',
            'condition_value' => 'required|string|max:100',
            'evaluation_result' => 'required|string',
            'evaluation_reason' => 'required|string',
            'priority' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ], [
            'rule_name.required' => 'Nama aturan wajib diisi.',
            'indicator.required' => 'Indikator wajib diisi.',
            'condition_logic.required' => 'Logika kondisi IF wajib ditulis.',
            'condition_param.required' => 'Parameter kondisi wajib dipilih.',
            'condition_operator.required' => 'Operator pembanding wajib dipilih.',
            'condition_value.required' => 'Nilai threshold wajib diisi.',
            'evaluation_result.required' => 'Hasil evaluasi wajib diisi.',
            'evaluation_reason.required' => 'Alasan evaluasi wajib diisi.',
            'priority.required' => 'Prioritas urutan wajib diisi.',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;

        $rule->update($validated);

        return redirect()->route('evaluation-rules.index')->with('success', 'Aturan evaluasi berhasil diperbarui.');
    }

    public function destroy(EvaluationRule $evaluation_rule)
    {
        $rule = $evaluation_rule;

        // Note: rule could be referenced by old evaluation_result_details.
        // We set onDelete cascade or restrict?
        // Since we have foreign keys on evaluation_result_details pointing to evaluation_rules,
        // we can block deletion if it's already used in a detail.
        
        // Let's check:
        $isUsed = \App\Models\EvaluationResultDetail::where('evaluation_rule_id', $rule->id)->exists();
        if ($isUsed) {
            return redirect()->route('evaluation-rules.index')->with('error', 'Aturan tidak dapat dihapus karena pernah digunakan dalam riwayat evaluasi.');
        }

        $rule->delete();

        return redirect()->route('evaluation-rules.index')->with('success', 'Aturan evaluasi berhasil dihapus.');
    }
}
