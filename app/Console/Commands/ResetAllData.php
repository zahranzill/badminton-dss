<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetAllData extends Command
{
    protected $signature = 'app:reset-all-data {--force : Skip confirmation prompt}';
    protected $description = 'Hapus semua data uji coba: evaluasi, pertandingan, pasangan, pemain, dan aturan lama. Hanya menyisakan aturan evaluasi baru dan akun user.';

    public function handle()
    {
        if (!$this->option('force')) {
            $this->warn('⚠️  PERINGATAN: Perintah ini akan menghapus SEMUA data berikut:');
            $this->line('   - Seluruh riwayat evaluasi DSS');
            $this->line('   - Seluruh aturan evaluasi yang nonaktif (bahasa teknis)');
            $this->line('   - Seluruh data rally');
            $this->line('   - Seluruh data pertandingan');
            $this->line('   - Seluruh data pasangan ganda');
            $this->line('   - Seluruh data pemain');
            $this->line('   - Seluruh data statistik performa');
            $this->newLine();
            $this->info('✅ Data yang TIDAK dihapus: Aturan evaluasi aktif (19 aturan baru) dan akun user.');
            $this->newLine();

            if (!$this->confirm('Apakah Anda yakin ingin melanjutkan?')) {
                $this->info('Operasi dibatalkan.');
                return 0;
            }
        }

        $this->info('Memulai penghapusan data...');

        // Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            // 1. Hapus detail evaluasi (child of evaluation_results & evaluation_rules)
            $countDetails = DB::table('evaluation_result_details')->count();
            DB::table('evaluation_result_details')->truncate();
            $this->line("   ✓ Hapus {$countDetails} detail evaluasi");

            // 2. Hapus hasil evaluasi (child of matches)
            $countResults = DB::table('evaluation_results')->count();
            DB::table('evaluation_results')->truncate();
            $this->line("   ✓ Hapus {$countResults} hasil evaluasi");

            // 3. Hapus statistik performa (child of matches)
            if (Schema::hasTable('performance_statistics')) {
                $countStats = DB::table('performance_statistics')->count();
                DB::table('performance_statistics')->truncate();
                $this->line("   ✓ Hapus {$countStats} statistik performa");
            }

            // 4. Hapus rally (child of matches)
            $countRallies = DB::table('rallies')->count();
            DB::table('rallies')->truncate();
            $this->line("   ✓ Hapus {$countRallies} data rally");

            // 5. Hapus pertandingan (child of pairs)
            $countMatches = DB::table('match_games')->count();
            DB::table('match_games')->truncate();
            $this->line("   ✓ Hapus {$countMatches} pertandingan");

            // 6. Hapus pasangan ganda (child of players)
            $countPairs = DB::table('pairs')->count();
            DB::table('pairs')->truncate();
            $this->line("   ✓ Hapus {$countPairs} pasangan ganda");

            // 7. Hapus pemain
            $countPlayers = DB::table('players')->count();
            DB::table('players')->truncate();
            $this->line("   ✓ Hapus {$countPlayers} pemain");

            // 8. Hapus aturan evaluasi yang NONAKTIF (bahasa teknis lama)
            $countInactiveRules = DB::table('evaluation_rules')->where('is_active', false)->count();
            DB::table('evaluation_rules')->where('is_active', false)->delete();
            $this->line("   ✓ Hapus {$countInactiveRules} aturan evaluasi nonaktif (bahasa teknis)");

            // Aktifkan kembali foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $remainingRules = DB::table('evaluation_rules')->where('is_active', true)->count();

            $this->newLine();
            $this->info('✅ Semua data uji coba berhasil dihapus!');
            $this->info("   Tersisa {$remainingRules} aturan evaluasi aktif (bahasa coaching) dan akun user.");

        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
