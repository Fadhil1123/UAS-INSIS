<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SimariService; // <-- Import service yang kita buat tadi

class SyncJadwalSimari extends Command
{
    // Ini nama perintah yang nanti akan kita ketik di terminal
    protected $signature = 'simari:sync';

    // Deskripsi perintahnya
    protected $description = 'Menarik data jadwal kuliah dari API SIMARI dan menyimpannya ke cache lokal';

    public function handle(SimariService $simariService)
    {
        $this->info('Memulai sinkronisasi dengan API SIMARI...');

        // 1. SINKRONISASI JADWAL
        $this->info('--> Menarik data Jadwal Kuliah...');
        $jadwalSuccess = $simariService->fetchAndCacheJadwal();
        
        if ($jadwalSuccess) {
            $this->info('    ✅ Jadwal berhasil disinkronisasi.');
        } else {
            $this->error('    ❌ Gagal sinkronisasi jadwal.');
        }

        // 2. SINKRONISASI RUANGAN
        $this->info('--> Menarik data Ruangan...');
        $ruanganSuccess = $simariService->syncRooms();
        
        if ($ruanganSuccess) {
            $this->info('    ✅ Ruangan berhasil disinkronisasi.');
        } else {
            $this->error('    ❌ Gagal sinkronisasi ruangan.');
        }

        if ($jadwalSuccess && $ruanganSuccess) {
            $this->info('🎉 Semua proses sinkronisasi BERHASIL diselesaikan!');
        } else {
            $this->warn('⚠️ Proses selesai, namun ada beberapa bagian yang gagal. Cek storage/logs/laravel.log untuk detailnya.');
        }
    }
}