<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_name',
        'indicator',
        'condition_logic',
        'condition_param',
        'condition_operator',
        'condition_value',
        'evaluation_result',
        'evaluation_reason',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];
}
