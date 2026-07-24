<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('performance_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_game_id')->unique()->constrained('match_games')->onDelete('cascade');
            $table->integer('total_rallies');
            $table->integer('pair_points');
            $table->integer('opponent_points');
            $table->integer('pair_errors');
            $table->string('dominant_error_type')->nullable();
            $table->foreignId('most_error_player_id')->nullable()->constrained('players')->onDelete('set null');
            $table->integer('most_error_player_count')->default(0);
            $table->decimal('avg_stroke_count', 8, 2);
            $table->decimal('avg_rally_duration', 8, 2);
            $table->integer('critical_point_errors')->default(0);
            $table->integer('total_critical_points')->default(0);
            $table->decimal('pair_point_percentage', 5, 2);
            $table->decimal('opponent_point_percentage', 5, 2);
            $table->json('set_performance')->comment('per-set stats');
            $table->json('detailed_stats')->nullable()->comment('additional stats like win rates');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_statistics');
    }
};
