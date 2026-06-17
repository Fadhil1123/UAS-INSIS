<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;

class BookingDummySeeder extends Seeder
{
    public function run(): void
    {
        // Dummy 1: Status Pending (Untuk dicoba Admin Approve/Reject nanti)
        Booking::create([
            'user_id' => 2, // Sesuaikan dengan ID Mahasiswa/Dosen di tabel users Anda
            'room_id' => 1, // Sesuaikan dengan ID Ruangan di tabel rooms Anda
            'booking_date' => '2026-06-16',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'purpose' => 'Rapat Koordinasi Panitia UAS',
            'status' => 'pending',
            'rejection_reason' => null,
            'booking_type' => 'umum',
        ]);

        // Dummy 2: Status Approved
        Booking::create([
            'user_id' => 2,
            'room_id' => 2,
            'booking_date' => '2026-06-17',
            'start_time' => '13:00:00',
            'end_time' => '15:30:00',
            'purpose' => 'Kuliah Pengganti Integrasi Sistem',
            'status' => 'approved',
            'rejection_reason' => null,
            'booking_type' => 'umum',
        ]);

        // Dummy 3: Status Rejected
        Booking::create([
            'user_id' => 3,
            'room_id' => 1,
            'booking_date' => '2026-06-15',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'purpose' => 'Seminar Komunitas Koding',
            'status' => 'rejected',
            'rejection_reason' => 'Ruangan pada jam tersebut mati lampu / sedang perbaikan AC.',
            'booking_type' => 'umum',
        ]);
    }
}