<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Services\PerformanceStatService;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    protected $statService;

    public function __construct(PerformanceStatService $statService)
    {
        $this->statService = $statService;
    }

    public function index(Request $request)
    {
        // Get matches that need verification (status = Draft) or are already Final
        $query = MatchGame::with('pair');

        if ($request->has('search') && $request->search != '') {
            $query->where('opponent_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pair', function($p) use ($request) {
                      $p->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $matches = $query->orderBy('status', 'asc') // Draft first
                         ->orderBy('match_date', 'desc')
                         ->paginate(10);

        return view('verification.index', compact('matches'));
    }

    public function show(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'rallies.errorPlayer']);

        // Group rallies by set number
        $ralliesBySet = $match->rallies->groupBy('set_number')->sortKeys();

        return view('verification.show', compact('match', 'ralliesBySet'));
    }

    public function finalize(MatchGame $match)
    {
        if ($match->status !== 'Draft') {
            return redirect()->route('verification.index')->with('error', 'Hanya pertandingan berstatus Draft yang dapat difinalisasi.');
        }

        if ($match->rallies->count() === 0) {
            return back()->with('error', 'Pertandingan tidak dapat difinalisasi karena belum memiliki data rally.');
        }

        try {
            // Calculate and save performance statistics
            $this->statService->calculateAndSave($match);

            // Update match status to Final
            $match->update(['status' => 'Final']);

            return redirect()->route('statistics.show', $match->id)
                ->with('success', 'Pertandingan berhasil diverifikasi dan difinalisasi. Statistik performa telah dihitung.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses finalisasi data: ' . $e->getMessage());
        }
    }
}
