<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduleCache;
use App\Models\Room;
use Exception;

class SimariService
{
    // Fungsi untuk bypass peringatan Ngrok
    private function getHeaders()
{
    // Kita gunakan array yang lebih standar
    return [
        'ngrok-skip-browser-warning' => 'true',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
    ];
}

    public function fetchAndCacheJadwal()
    {
        $url = env('SIMARI_API_URL');

        try {
            $response = Http::withHeaders($this->getHeaders())->timeout(10)->get($url);

            if ($response->failed()) {
                Log::error("Gagal mengambil data Jadwal SIMARI. Status: " . $response->status());
                return false;
            }

            $jadwalData = $response->json();

            if (!is_array($jadwalData)) {
                Log::error("Format data Jadwal tidak valid.");
                return false;
            }

            ScheduleCache::truncate();

            foreach ($jadwalData as $item) {
                ScheduleCache::create([
                    'room_code'     => $item['room_code'] ?? 'UNKNOWN', 
                    'course_code'   => $item['kode_mk'] ?? null,
                    'course_name'   => $item['nama_mk'] ?? 'Mata Kuliah Tanpa Nama',
                    'lecturer_name' => $item['nama_dosen'] ?? '-',
                    'schedule_date' => $item['tanggal'] ?? date('Y-m-d'),
                    'start_time'    => $item['jam_mulai'] ?? '00:00:00',
                    'end_time'      => $item['jam_selesai'] ?? '00:00:00',
                ]);
            }

            return true;

        } catch (Exception $e) {
            Log::critical("Koneksi gagal ke API Jadwal: " . $e->getMessage());
            return false;
        }
    }

    public function syncRooms()
    {
        // Ganti /jadwal menjadi /rooms sesuai API FastAPI teman Anda
        $url = str_replace('/jadwal', '/rooms', env('SIMARI_API_URL')); 

        try {
            $response = Http::withHeaders($this->getHeaders())->timeout(10)->get($url);

            if ($response->failed()) {
                Log::error("Gagal mengambil data Ruangan SIMARI. Status: " . $response->status());
                return false;
            }

            $result = $response->json();
            
            // API FastAPI teman Anda mengembalikan data dalam key 'data'
            $roomsData = $result['data'] ?? [];

            if (!is_array($roomsData)) return false;

            foreach ($roomsData as $roomCode) {
                // Menyimpan ke database
                Room::updateOrCreate(
                    ['room_code' => $roomCode], 
                    [
                        'room_name' => 'Ruang ' . $roomCode,
                        'capacity'  => 30, // Default kapasitas
                        'category'  => 'kelas'
                    ]
                );
            }

            return true;

        } catch (Exception $e) {
            Log::critical("Gagal sinkronisasi ruangan: " . $e->getMessage());
            return false;
        }
    }
}