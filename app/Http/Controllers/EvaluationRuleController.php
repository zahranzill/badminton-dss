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

        return view('evaluation-rules.index', compact('rules', 'totalRules', 'activeRules', 'inactiveRules', 'uniqueIndicators'));
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
