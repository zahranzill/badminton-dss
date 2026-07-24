<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use Illuminate\Http\Request;

class PerformanceStatController extends Controller
{
    public function index(Request $request)
    {
        // Get matches with stats (status Final or Dievaluasi)
        $query = MatchGame::whereIn('status', ['Final', 'Dievaluasi'])->with(['pair', 'performanceStatistic']);

        if ($request->has('search') && $request->search != '') {
            $query->where('opponent_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pair', function($p) use ($request) {
                      $p->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10);
        $unevaluatedCount = MatchGame::where('status', 'Final')->count();
 
        return view('statistics.index', compact('matches', 'unevaluatedCount'));
    }

    public function show(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'performanceStatistic.mostErrorPlayer']);

        if (!$match->performanceStatistic) {
            return redirect()->route('statistics.index')->with('error', 'Statistik performa untuk pertandingan ini belum dihitung.');
        }

        $stats = $match->performanceStatistic;

        return view('statistics.show', compact('match', 'stats'));
    }
}
