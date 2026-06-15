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
        $this->info('Memulai sinkronisasi jadwal dari API SIMARI...');

        $success = $simariService->fetchAndCacheJadwal();

        if ($success) {
            $this->info('Sinkronisasi BERHASIL! Data cache telah diperbarui.');
        } else {
            $this->error('Sinkronisasi GAGAL! Silakan periksa file log (storage/logs/laravel.log) untuk detail error.');
        }
    }
}