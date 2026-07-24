<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_result_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_result_id')->constrained('evaluation_results')->onDelete('cascade');
            $table->foreignId('evaluation_rule_id')->constrained('evaluation_rules')->onDelete('restrict');
            $table->string('rule_name');
            $table->text('condition_description');
            $table->string('actual_value');
            $table->text('evaluation_result_text');
            $table->text('evaluation_reason');
            $table->boolean('is_triggered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_result_details');
    }
};
