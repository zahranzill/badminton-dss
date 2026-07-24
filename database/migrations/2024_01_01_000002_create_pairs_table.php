<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('player_1_id')->constrained('players')->onDelete('cascade');
            $table->foreignId('player_2_id')->constrained('players')->onDelete('cascade');
            $table->enum('pair_type', ['GD_PUTRA', 'GD_PUTRI', 'GD_CAMPURAN'])
                  ->comment('Ganda Putra, Ganda Putri, Ganda Campuran');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairs');
    }
};
