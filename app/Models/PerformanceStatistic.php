<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_game_id',
        'total_rallies',
        'pair_points',
        'opponent_points',
        'pair_errors',
        'dominant_error_type',
        'most_error_player_id',
        'most_error_player_count',
        'avg_stroke_count',
        'avg_rally_duration',
        'critical_point_errors',
        'total_critical_points',
        'pair_point_percentage',
        'opponent_point_percentage',
        'set_performance',
        'detailed_stats',
    ];

    protected $casts = [
        'set_performance' => 'json',
        'detailed_stats' => 'json',
        'total_rallies' => 'integer',
        'pair_points' => 'integer',
        'opponent_points' => 'integer',
        'pair_errors' => 'integer',
        'most_error_player_count' => 'integer',
        'critical_point_errors' => 'integer',
        'total_critical_points' => 'integer',
        'avg_stroke_count' => 'decimal:2',
        'avg_rally_duration' => 'decimal:2',
        'pair_point_percentage' => 'decimal:2',
        'opponent_point_percentage' => 'decimal:2',
    ];

    /**
     * Get the match game.
     */
    public function matchGame()
    {
        return $this->belongsTo(MatchGame::class, 'match_game_id');
    }

    /**
     * Get the player with the most errors.
     */
    public function mostErrorPlayer()
    {
        return $this->belongsTo(Player::class, 'most_error_player_id');
    }
}
