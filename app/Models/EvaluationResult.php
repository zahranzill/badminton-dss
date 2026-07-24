<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'match_game_id',
        'summary_stats',
        'overall_evaluation',
        'improvement_focus',
        'coach_notes',
    ];

    protected $casts = [
        'summary_stats' => 'json',
    ];

    /**
     * Get the match game.
     */
    public function matchGame()
    {
        return $this->belongsTo(MatchGame::class, 'match_game_id');
    }

    /**
     * Get detailed rules triggered.
     */
    public function details()
    {
        return $this->hasMany(EvaluationResultDetail::class, 'evaluation_result_id');
    }
}
