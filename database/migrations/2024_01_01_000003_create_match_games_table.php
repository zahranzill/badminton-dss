<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_games', function (Blueprint $table) {
            $table->id();
            $table->date('match_date');
            $table->foreignId('pair_id')->constrained('pairs')->onDelete('cascade');
            $table->string('opponent_name');
            $table->enum('match_type', ['Persahabatan', 'Turnamen', 'Latih Tanding', 'Lainnya'])->default('Lainnya');
            $table->enum('pair_category', ['GD_PUTRA', 'GD_PUTRI', 'GD_CAMPURAN']);
            $table->string('final_score', 50)->nullable();
            $table->enum('result', ['Menang', 'Kalah']);
            $table->text('notes')->nullable();
            $table->enum('status', ['Draft', 'Final', 'Dievaluasi'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('match_games');
    }
};
