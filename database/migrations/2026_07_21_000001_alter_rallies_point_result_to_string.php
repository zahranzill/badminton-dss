<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah ENUM point_result menjadi VARCHAR agar lebih fleksibel
        // dan menghapus nilai 'Let' dan 'Lainnya' yang tidak sesuai aturan bulu tangkis
        DB::statement("ALTER TABLE rallies MODIFY COLUMN point_result VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE rallies MODIFY COLUMN point_result ENUM('Winner','Error Lawan','Error Sendiri','Let','Lainnya') NOT NULL");
    }
};
