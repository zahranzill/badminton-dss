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
        $match->load(['pair.player1', 'pair.player2', 'evaluationResult.details.evaluationRule']);

        if (!$match->evaluationResult) {
            return redirect()->route('statistics.show', $match->id)
                ->with('error', 'Hasil evaluasi belum dibuat. Silakan jalankan modul DSS terlebih dahulu.');
        }

        $result = $match->evaluationResult;
        
        // Dapatkan hanya detail aturan yang bernilai true (terpicu)
        $triggeredDetails = $result->details->where('is_triggered', true);

        return view('evaluations.show', compact('match', 'result', 'triggeredDetails'));
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
