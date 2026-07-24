<?php

namespace App\Http\Controllers;

use App\Models\Player;
use App\Models\Pair;
use App\Models\MatchGame;
use App\Models\EvaluationResult;
use App\Models\Rally;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Tampilkan halaman dashboard.
     */
    public function index()
    {
        $totalPlayers = Player::count();
        $totalPairs = Pair::count();
        $totalMatches = MatchGame::count();
        $totalEvaluations = EvaluationResult::count();

        $recentMatches = MatchGame::with('pair')
            ->latest('match_date')
            ->take(5)
            ->get();

        $recentEvaluations = EvaluationResult::with('matchGame.pair')
            ->latest()
            ->take(5)
            ->get();

        $matchStats = [
            'wins' => MatchGame::where('result', 'Menang')->count(),
            'losses' => MatchGame::where('result', 'Kalah')->count(),
            'draft' => MatchGame::where('status', 'Draft')->count(),
            'final' => MatchGame::where('status', 'Final')->count(),
            'evaluated' => MatchGame::where('status', 'Dievaluasi')->count(),
        ];

        // Win rate percentage
        $winRate = $totalMatches > 0 ? round(($matchStats['wins'] / $totalMatches) * 100) : 0;

        // Unevaluated matches (Final but not yet evaluated)
        $unevaluatedMatches = MatchGame::where('status', 'Final')
            ->with('pair')
            ->latest('match_date')
            ->take(5)
            ->get();
        $unevaluatedCount = MatchGame::where('status', 'Final')->count();

        // Error type distribution for radar chart
        $errorDistribution = Rally::select('error_type', DB::raw('count(*) as total'))
            ->whereNotNull('error_type')
            ->where('error_type', '!=', '')
            ->groupBy('error_type')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        // Monthly match history (last 6 months)
        $monthlyMatches = MatchGame::select(
                DB::raw("DATE_FORMAT(match_date, '%Y-%m') as month"),
                DB::raw("SUM(CASE WHEN result = 'Menang' THEN 1 ELSE 0 END) as wins"),
                DB::raw("SUM(CASE WHEN result = 'Kalah' THEN 1 ELSE 0 END) as losses")
            )
            ->where('match_date', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('dashboard.index', compact(
            'totalPlayers',
            'totalPairs',
            'totalMatches',
            'totalEvaluations',
            'recentMatches',
            'recentEvaluations',
            'matchStats',
            'winRate',
            'unevaluatedMatches',
            'unevaluatedCount',
            'errorDistribution',
            'monthlyMatches'
        ));
    }
}
