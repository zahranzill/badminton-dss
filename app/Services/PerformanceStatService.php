<?php

namespace App\Services;

use App\Models\MatchGame;
use App\Models\PerformanceStatistic;

class PerformanceStatService
{
    /**
     * Hitung statistik performa dan simpan ke database.
     */
    public function calculateAndSave(MatchGame $match): PerformanceStatistic
    {
        $rallies = $match->rallies;

        if ($rallies->count() === 0) {
            throw new \Exception("Pertandingan tidak memiliki data rally untuk dihitung.");
        }

        $totalRallies = $rallies->count();
        $pairPoints = $rallies->where('point_winner', 'Pasangan')->count();
        $opponentPoints = $rallies->where('point_winner', 'Lawan')->count();
        
        // Error Pasangan (Error Sendiri / Unforced Error)
        $pairErrors = $rallies->where('point_result', 'Error Sendiri')->count();

        // Dominant Error Type
        $errorTypes = $rallies->where('point_result', 'Error Sendiri')
            ->whereNotNull('error_type')
            ->groupBy('error_type')
            ->map->count();
        
        $dominantErrorType = null;
        if ($errorTypes->count() > 0) {
            $dominantErrorType = $errorTypes->sortDesc()->keys()->first();
        }

        // Most Error Player
        $errorPlayers = $rallies->where('point_result', 'Error Sendiri')
            ->whereNotNull('error_player_id')
            ->groupBy('error_player_id')
            ->map->count();

        $mostErrorPlayerId = null;
        $mostErrorPlayerCount = 0;
        if ($errorPlayers->count() > 0) {
            $mostErrorPlayerId = $errorPlayers->sortDesc()->keys()->first();
            $mostErrorPlayerCount = $errorPlayers->sortDesc()->first();
        }

        // Averages
        $avgStrokeCount = $rallies->whereNotNull('stroke_count')->avg('stroke_count') ?? 0;
        $avgRallyDuration = $rallies->whereNotNull('rally_duration')->avg('rally_duration') ?? 0;

        // Critical Points
        $criticalPoints = $rallies->where('is_critical_point', true);
        $totalCriticalPoints = $criticalPoints->count();
        $criticalPointErrors = $criticalPoints->where('point_result', 'Error Sendiri')->count();

        // Percentages
        $pairPointPercentage = $totalRallies > 0 ? ($pairPoints / $totalRallies) * 100 : 0;
        $opponentPointPercentage = $totalRallies > 0 ? ($opponentPoints / $totalRallies) * 100 : 0;

        // Long Rally Win Rate (long rally = stroke count > 8)
        $longRallies = $rallies->where('stroke_count', '>', 8);
        $totalLongRallies = $longRallies->count();
        $longRalliesWon = $longRallies->where('point_winner', 'Pasangan')->count();
        $longRallyWinRate = $totalLongRallies > 0 ? ($longRalliesWon / $totalLongRallies) * 100 : 0;

        // Error Concentration (if most error player count is > 60% of total pair errors)
        $errorConcentration = false;
        if ($pairErrors > 0 && $mostErrorPlayerCount > 0) {
            $errorConcentration = ($mostErrorPlayerCount / $pairErrors) > 0.6;
        }

        // Per-Set Performance
        $setPerformance = [];
        $sets = $rallies->pluck('set_number')->unique()->sort();
        foreach ($sets as $setNum) {
            $setRallies = $rallies->where('set_number', $setNum);
            $setTotal = $setRallies->count();
            $setPair = $setRallies->where('point_winner', 'Pasangan')->count();
            $setOpp = $setRallies->where('point_winner', 'Lawan')->count();
            $setErrors = $setRallies->where('point_result', 'Error Sendiri')->count();
            $setResult = $setPair > $setOpp ? 'Menang' : 'Kalah';

            $setPerformance[$setNum] = [
                'total_rallies' => $setTotal,
                'pair_points' => $setPair,
                'opponent_points' => $setOpp,
                'pair_errors' => $setErrors,
                'result' => $setResult,
            ];
        }

        // Detailed Stats
        $detailedStats = [
            'long_rally_win_rate' => round($longRallyWinRate, 2),
            'total_long_rallies' => $totalLongRallies,
            'long_rallies_won' => $longRalliesWon,
            'error_concentration' => $errorConcentration,
            'error_distribution' => $errorPlayers->mapWithKeys(function ($count, $playerId) use ($pairErrors) {
                return [$playerId => [
                    'count' => $count,
                    'percentage' => $pairErrors > 0 ? round(($count / $pairErrors) * 100, 2) : 0
                ]];
            })->toArray(),
            'error_types_distribution' => $errorTypes->toArray(),
        ];

        // Save or update Performance Statistic
        return PerformanceStatistic::updateOrCreate(
            ['match_game_id' => $match->id],
            [
                'total_rallies' => $totalRallies,
                'pair_points' => $pairPoints,
                'opponent_points' => $opponentPoints,
                'pair_errors' => $pairErrors,
                'dominant_error_type' => $dominantErrorType,
                'most_error_player_id' => $mostErrorPlayerId,
                'most_error_player_count' => $mostErrorPlayerCount,
                'avg_stroke_count' => round($avgStrokeCount, 2),
                'avg_rally_duration' => round($avgRallyDuration, 2),
                'critical_point_errors' => $criticalPointErrors,
                'total_critical_points' => $totalCriticalPoints,
                'pair_point_percentage' => round($pairPointPercentage, 2),
                'opponent_point_percentage' => round($opponentPointPercentage, 2),
                'set_performance' => $setPerformance,
                'detailed_stats' => $detailedStats,
            ]
        );
    }
}
