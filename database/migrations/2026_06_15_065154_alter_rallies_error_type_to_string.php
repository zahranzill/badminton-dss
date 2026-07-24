<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Change ENUM to VARCHAR for flexibility (add new error types without migration)
        DB::statement("ALTER TABLE rallies MODIFY COLUMN error_type VARCHAR(50) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rallies MODIFY COLUMN error_type ENUM('Net','Out','Miskomunikasi','Timing','Footwork','Lainnya') NULL");
    }
};
