<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_rules', function (Blueprint $table) {
            $table->id();
            $table->string('rule_name');
            $table->string('indicator');
            $table->text('condition_logic');
            $table->string('condition_param', 100);
            $table->string('condition_operator', 20);
            $table->string('condition_value', 100);
            $table->text('evaluation_result');
            $table->text('evaluation_reason');
            $table->boolean('is_active')->default(true);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_rules');
    }
};
