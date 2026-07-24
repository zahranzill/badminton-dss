<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pair extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'player_1_id',
        'player_2_id',
        'pair_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get player 1.
     */
    public function player1()
    {
        return $this->belongsTo(Player::class, 'player_1_id');
    }

    /**
     * Get player 2.
     */
    public function player2()
    {
        return $this->belongsTo(Player::class, 'player_2_id');
    }

    /**
     * Get matches for this pair.
     */
    public function matches()
    {
        return $this->hasMany(MatchGame::class, 'pair_id');
    }
}
