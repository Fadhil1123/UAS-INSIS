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

            $responseData = $response->json();

            // Ambil array dari dalam key 'data' karena FastAPI membungkusnya di sana
            $jadwalItems = $responseData['data'] ?? [];

            if (!is_array($jadwalItems) || empty($jadwalItems)) {
                Log::error("Format data Jadwal tidak valid atau kosong.");
                return false;
            }

            ScheduleCache::truncate();

            foreach ($jadwalItems as $item) {
                // Perbaikan format jam agar terbaca FullCalendar (misal 8:00 -> 08:00:00)
                $jamMulai = isset($item['jam_mulai']) ? str_pad(trim($item['jam_mulai']), 5, '0', STR_PAD_LEFT) . ':00' : '00:00:00';
                $jamSelesai = isset($item['jam_selesai']) ? str_pad(trim($item['jam_selesai']), 5, '0', STR_PAD_LEFT) . ':00' : '00:00:00';

                // Karena parser mengirim nama hari di field 'tanggal', kita buat tanggal dinamis untuk minggu ini
                $hariInggris = [
                    'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                    'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
                ];
                $namaHari = $item['hari'] ?? 'Senin';
                $hariTarget = $hariInggris[$namaHari] ?? 'Monday';
                $tanggalDinamis = date('Y-m-d', strtotime("next $hariTarget", strtotime("-1 week")));

                ScheduleCache::create([
                    'room_code'     => $item['room_code'] ?? 'UNKNOWN', 
                    'course_code'   => null, // Parser Anda tidak mengekstrak kode mk
                    'course_name'   => $item['nama_mk'] ?? 'Mata Kuliah Tanpa Nama',
                    'lecturer_name' => '-',   // Parser Anda tidak mengekstrak nama dosen
                    'schedule_date' => $tanggalDinamis, // Dikonversi dari Hari menjadi tanggal Y-m-d nyata
                    'start_time'    => $jamMulai,
                    'end_time'      => $jamSelesai,
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