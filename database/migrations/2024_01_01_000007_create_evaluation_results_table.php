<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_game_id')->unique()->constrained('match_games')->onDelete('cascade');
            $table->json('summary_stats');
            $table->text('overall_evaluation');
            $table->text('improvement_focus');
            $table->text('coach_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_results');
    }
};
