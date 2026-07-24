<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rallies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_game_id')->constrained('match_games')->onDelete('cascade');
            $table->integer('set_number');
            $table->integer('rally_number');
            $table->integer('pair_score');
            $table->integer('opponent_score');
            $table->enum('point_winner', ['Pasangan', 'Lawan']);
            $table->enum('point_result', ['Winner', 'Error Lawan', 'Error Sendiri', 'Let', 'Lainnya']);
            $table->enum('error_type', ['Net', 'Out', 'Miskomunikasi', 'Timing', 'Footwork', 'Lainnya'])->nullable();
            $table->foreignId('error_player_id')->nullable()->constrained('players')->onDelete('set null');
            $table->integer('stroke_count')->nullable();
            $table->integer('rally_duration')->nullable()->comment('dalam detik');
            $table->boolean('is_critical_point')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rallies');
    }
};
