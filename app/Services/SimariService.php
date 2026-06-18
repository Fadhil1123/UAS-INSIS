<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\ScheduleCache;
use App\Models\Room;
use Exception;

class SimariService
{
    private function getHeaders()
    {
        return [
            'ngrok-skip-browser-warning' => 'true',
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
        ];
    }

    public function fetchAndCacheJadwal()
    {
        $url = env('SIMARI_API_URL');
        Log::info("Mencoba konek ke: " . $url); // Debugging

        try {
            // Menggunakan withoutVerifying dan timeout lebih lama
            $response = Http::withoutVerifying()
                            ->withHeaders($this->getHeaders())
                            ->timeout(60)
                            ->get($url);

            if ($response->failed()) {
                Log::error("Gagal mengambil data Jadwal SIMARI. Status: " . $response->status());
                return false;
            }

            $responseData = $response->json();

            // MENGGUNAKAN EXTRACTOR AGAR KALENDER TIDAK KOSONG
            $jadwalItems = $this->extractJadwalItems($responseData['data'] ?? []);

            if (!is_array($jadwalItems) || empty($jadwalItems)) {
                Log::error("Format data Jadwal tidak valid atau kosong.");
                return false;
            }

            ScheduleCache::truncate();

            foreach ($jadwalItems as $item) {
                $jamMulai = isset($item['jam_mulai']) ? str_pad(trim($item['jam_mulai']), 5, '0', STR_PAD_LEFT) . ':00' : '00:00:00';
                $jamSelesai = isset($item['jam_selesai']) ? str_pad(trim($item['jam_selesai']), 5, '0', STR_PAD_LEFT) . ':00' : '00:00:00';

                $hariInggris = [
                    'Senin' => 'Monday', 'Selasa' => 'Tuesday', 'Rabu' => 'Wednesday',
                    'Kamis' => 'Thursday', 'Jumat' => 'Friday', 'Sabtu' => 'Saturday', 'Minggu' => 'Sunday'
                ];
                $namaHari = $item['hari'] ?? 'Senin';
                $hariTarget = $hariInggris[$namaHari] ?? 'Monday';
                $tanggalDinamis = date('Y-m-d', strtotime("next $hariTarget", strtotime("-1 week")));

                ScheduleCache::create([
                    'room_code'     => $item['room_code'] ?? 'UNKNOWN', 
                    'course_code'   => null, 
                    'course_name'   => $item['nama_mk'] ?? 'Mata Kuliah Tanpa Nama',
                    'lecturer_name' => '-',   
                    'schedule_date' => $tanggalDinamis, 
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
        $url = str_replace('/jadwal', '/rooms', env('SIMARI_API_URL')); 

        try {
            $response = Http::withHeaders($this->getHeaders())->timeout(10)->get($url);

            if ($response->failed()) {
                Log::error("Gagal mengambil data Ruangan SIMARI. Status: " . $response->status());
                return false;
            }

            $result = $response->json();
            
            // MENGGUNAKAN EXTRACTOR AGAR KATEGORI & KAPASITAS TIDAK DEFAULT
            $roomGroups = $this->extractRoomGroups($result['data'] ?? []);

            if (empty($roomGroups)) {
                Log::error("Format data Ruangan tidak valid atau kosong.");
                return false;
            }

            foreach ($roomGroups as $group) {
                $category = $group['category']; // Akan otomatis menjadi 'kelas' atau 'laboratorium'

                foreach ($group['codes'] as $roomItem) {
                    $roomCode = is_array($roomItem) ? ($roomItem['room_code'] ?? $roomItem['id'] ?? null) : $roomItem;

                    if (empty($roomCode)) continue;

                    // Otomatis kapasitas 25 untuk lab, 30 untuk kelas
                    $capacity = ($category === 'laboratorium') ? 25 : 30;

                    Room::updateOrCreate(
                        ['room_code' => (string) $roomCode], 
                        [
                            'room_name' => ($category === 'laboratorium' ? 'Lab. ' : 'Ruang ') . (string) $roomCode,
                            'capacity'  => $capacity,
                            'category'  => $category
                        ]
                    );
                }
            }

            return true;

        } catch (Exception $e) {
            Log::critical("Gagal sinkronisasi ruangan: " . $e->getMessage());
            return false;
        }
    }

    private function extractJadwalItems(mixed $data): ?array
    {
        if (! is_array($data)) return null;

        if (isset($data['kelas']) || isset($data['lab'])) {
            $items = array_merge($data['kelas'] ?? [], $data['lab'] ?? []);
            return empty($items) ? null : $items;
        }

        if (array_is_list($data)) {
            return empty($data) ? null : $data;
        }

        return null;
    }

    private function extractRoomGroups(mixed $data): ?array
    {
        if (! is_array($data)) return null;

        if (isset($data['kelas']) || isset($data['lab'])) {
            $groups = [
                ['codes' => $data['kelas'] ?? [], 'category' => 'kelas'],
                ['codes' => $data['lab'] ?? [], 'category' => 'laboratorium'],
            ];

            $hasRooms = collect($groups)->contains(fn (array $group) => ! empty($group['codes']));
            return $hasRooms ? $groups : null;
        }

        if (array_is_list($data) && ! empty($data)) {
            return [['codes' => $data, 'category' => 'kelas']];
        }

        return null;
    }
}