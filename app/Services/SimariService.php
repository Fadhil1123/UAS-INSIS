<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduleCache;
use Exception;

class SimariService
{
    public function fetchAndCacheJadwal()
    {
        // Ambil URL API dari file .env
        $url = env('SIMARI_API_URL');

        try {
            // 1. KONSUMSI API MENGGUNAKAN HTTP CLIENT LARAVEL
            // Set timeout 10 detik agar aplikasi tidak hang jika API luar down
            $response = Http::timeout(10)->get($url);

            // 2. ERROR HANDLING: Cek jika API gagal merespons (Status 400/500)
            if ($response->failed()) {
                Log::error("Gagal mengambil data SIMARI. Status: " . $response->status());
                return false;
            }

            // 3. PARSING JSON
            $jadwalData = $response->json(); // Mengubah response teks menjadi array PHP

            if (!is_array($jadwalData)) {
                Log::error("Format data dari API SIMARI bukan JSON Array yang valid.");
                return false;
            }

            // 4. CACHE JADWAL: Kosongkan cache lama terlebih dahulu
            ScheduleCache::truncate();

            // 5. Simpan data baru hasil parsing ke database lokal
            foreach ($jadwalData as $item) {
                ScheduleCache::create([
                    // Sebelah kiri adalah nama kolom di database teman Anda
                    // Sebelah kanan adalah estimasi nama key JSON dari API
                    'room_code'     => $item['room_code'] ?? 'UNKNOWN', 
                    'course_code'   => $item['kode_mk'] ?? null,
                    'course_name'   => $item['nama_mk'] ?? 'Mata Kuliah Tanpa Nama',
                    'lecturer_name' => $item['nama_dosen'] ?? '-',
                    'schedule_date' => $item['tanggal'] ?? date('Y-m-d'), // Tanggal jadwal (YYYY-MM-DD)
                    'start_time'    => $item['jam_mulai'] ?? '00:00:00',
                    'end_time'      => $item['jam_selesai'] ?? '00:00:00',
                ]);
            }

            return true;

        } catch (Exception $e) {
            // 2. ERROR HANDLING: Menangani jika koneksi internet putus total / API down
            Log::critical("Terjadi kegagalan koneksi sistem ke API SIMARI: " . $e->getMessage());
            return false;
        }
    }
}