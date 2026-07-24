<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'gender',
        'category',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get pairs where this player is player 1.
     */
    public function pairsAsPlayer1()
    {
        return $this->hasMany(Pair::class, 'player_1_id');
    }

    /**
     * Get pairs where this player is player 2.
     */
    public function pairsAsPlayer2()
    {
        return $this->hasMany(Pair::class, 'player_2_id');
    }

    /**
     * Get rallies where this player committed an error.
     */
    public function errorRallies()
    {
        return $this->hasMany(Rally::class, 'error_player_id');
    }
}
