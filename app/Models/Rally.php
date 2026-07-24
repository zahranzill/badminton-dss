<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rally extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_game_id',
        'set_number',
        'rally_number',
        'pair_score',
        'opponent_score',
        'point_winner',
        'point_result',
        'error_type',
        'error_player_id',
        'stroke_count',
        'rally_duration',
        'is_critical_point',
        'remarks',
    ];

    protected $casts = [
        'is_critical_point' => 'boolean',
        'stroke_count' => 'integer',
        'rally_duration' => 'integer',
        'rally_number' => 'integer',
        'set_number' => 'integer',
        'pair_score' => 'integer',
        'opponent_score' => 'integer',
    ];

    /**
     * Get the match game.
     */
    public function matchGame()
    {
        return $this->belongsTo(MatchGame::class, 'match_game_id');
    }

    /**
     * Get the player who committed the error.
     */
    public function errorPlayer()
    {
        return $this->belongsTo(Player::class, 'error_player_id');
    }
}
