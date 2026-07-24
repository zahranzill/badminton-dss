<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Get matches with status 'Dievaluasi'
        $query = MatchGame::where('status', 'Dievaluasi')->with(['pair', 'performanceStatistic', 'evaluationResult']);

        if ($request->has('search') && $request->search != '') {
            $query->where('opponent_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pair', function($p) use ($request) {
                      $p->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10);

        return view('reports.index', compact('matches'));
    }

    public function show(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'performanceStatistic', 'evaluationResult.details.evaluationRule']);

        if (!$match->evaluationResult) {
            return redirect()->route('reports.index')->with('error', 'Laporan tidak dapat dibuka karena data belum dievaluasi.');
        }

        return view('reports.show', compact('match'));
    }

    public function print(MatchGame $match)
    {
        $match->load(['pair.player1', 'pair.player2', 'performanceStatistic', 'evaluationResult.details.evaluationRule']);

        if (!$match->evaluationResult) {
            return redirect()->route('reports.index')->with('error', 'Laporan tidak dapat dicetak karena data belum dievaluasi.');
        }

        return view('reports.print', compact('match'))->with('no_layout', true);
    }
}
