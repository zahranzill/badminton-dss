<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationResultDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_result_id',
        'evaluation_rule_id',
        'rule_name',
        'condition_description',
        'actual_value',
        'evaluation_result_text',
        'evaluation_reason',
        'is_triggered',
    ];

    protected $casts = [
        'is_triggered' => 'boolean',
    ];

    /**
     * Get the evaluation result parent.
     */
    public function evaluationResult()
    {
        return $this->belongsTo(EvaluationResult::class, 'evaluation_result_id');
    }

    /**
     * Get the evaluation rule origin.
     */
    public function evaluationRule()
    {
        return $this->belongsTo(EvaluationRule::class, 'evaluation_rule_id');
    }
}
