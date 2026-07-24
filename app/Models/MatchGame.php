<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchGame extends Model
{
    use HasFactory;

    protected $table = 'match_games';

    protected $fillable = [
        'match_date',
        'pair_id',
        'opponent_name',
        'match_type',
        'pair_category',
        'final_score',
        'result',
        'notes',
        'status',
    ];

    protected $casts = [
        'match_date' => 'date',
    ];

    /**
     * Get the pair.
     */
    public function pair()
    {
        return $this->belongsTo(Pair::class, 'pair_id');
    }

    /**
     * Get rallies for this match.
     */
    public function rallies()
    {
        return $this->hasMany(Rally::class, 'match_game_id');
    }

    /**
     * Get performance statistic.
     */
    public function performanceStatistic()
    {
        return $this->hasOne(PerformanceStatistic::class, 'match_game_id');
    }

    /**
     * Get evaluation result.
     */
    public function evaluationResult()
    {
        return $this->hasOne(EvaluationResult::class, 'match_game_id');
    }

    /**
     * Periksa apakah input data rally sudah selesai (satu pihak telah memenangkan minimal 2 set secara sah).
     */
    public function isRallyInputComplete(): bool
    {
        $rallies = $this->rallies;
        if ($rallies->count() === 0) {
            return false;
        }

        $setWins = ['Pasangan' => 0, 'Lawan' => 0];
        $bySet = $rallies->groupBy('set_number');

        foreach ([1, 2, 3] as $setNum) {
            $setRallies = $bySet->get($setNum);
            if (!$setRallies || $setRallies->count() === 0) {
                continue;
            }

            $lastRally = $setRallies->sortByDesc('rally_number')->first();
            $pScore = $lastRally->pair_score;
            $oScore = $lastRally->opponent_score;
            $maxScore = max($pScore, $oScore);
            $diff = abs($pScore - $oScore);

            // Set dianggap selesai jika ada skor >= 21 dengan selisih >= 2, atau capai max 30 poin
            if (($maxScore >= 21 && $diff >= 2) || $maxScore >= 30) {
                if ($pScore > $oScore) {
                    $setWins['Pasangan']++;
                } else {
                    $setWins['Lawan']++;
                }
            }

            // Jika salah satu pihak telah memenangkan 2 set, maka input rally sudah lengkap/selesai
            if ($setWins['Pasangan'] >= 2 || $setWins['Lawan'] >= 2) {
                return true;
            }
        }

        return false;
    }
}
