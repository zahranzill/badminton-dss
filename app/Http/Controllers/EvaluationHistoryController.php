<?php

namespace App\Http\Controllers;

use App\Models\MatchGame;
use App\Models\Pair;
use Illuminate\Http\Request;

class EvaluationHistoryController extends Controller
{
    public function index(Request $request)
    {
        // Get matches with status 'Dievaluasi'
        $query = MatchGame::where('status', 'Dievaluasi')->with(['pair', 'evaluationResult']);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('opponent_name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pair', function($p) use ($request) {
                      $p->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        if ($request->has('pair_id') && $request->pair_id != '') {
            $query->where('pair_id', $request->pair_id);
        }

        if ($request->has('result') && $request->result != '') {
            $query->where('result', $request->result);
        }

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('match_date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('match_date', '<=', $request->end_date);
        }

        $matches = $query->orderBy('match_date', 'desc')->paginate(10)->withQueryString();
        $pairs = Pair::where('is_active', true)->orderBy('name')->get();

        return view('evaluations.history', compact('matches', 'pairs'));
    }
}
