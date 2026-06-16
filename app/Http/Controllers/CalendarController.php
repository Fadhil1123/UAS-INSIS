<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ScheduleCache;
use App\Models\Room;

class CalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        // Tangkap parameter filter ruangan dari Frontend (jika ada)
        $roomId = $request->input('room_id');
        
        $events = [];

        // ========================================================
        // 1. AMBIL DATA DARI JADWAL SIMARI (Tabel schedule_cache)
        // ========================================================
        $schedules = ScheduleCache::query();
        
        if ($roomId) {
            // Ambil data ruangan berdasarkan ID yang dikirim frontend
            $room = Room::find($roomId);
            if ($room) {
                $schedules->where('room_code', $room->room_code); 
            }
        }
        
        $schedules = $schedules->get();

        foreach ($schedules as $jadwal) {
            $events[] = [
                'title' => 'Kuliah: ' . $jadwal->course_name,
                'start' => $jadwal->schedule_date . 'T' . $jadwal->start_time,
                'end'   => $jadwal->schedule_date . 'T' . $jadwal->end_time,
                'color' => '#dc3545',
                'textColor' => '#ffffff'
            ];
        }

        // ========================================================
        // 2. AMBIL DATA BOOKING LOKAL (Tabel bookings)
        // ========================================================
        // Hanya ambil status yang valid berkegiatan (pending dan approved)
        $bookings = Booking::whereIn('status', ['pending', 'approved']);
        
        if ($roomId) {
            $bookings->where('room_id', $roomId);
        }
        
        $bookings = $bookings->get();

        foreach ($bookings as $booking) {
            // Bedakan warna balon event berdasarkan status bookingnya
            $bgColor = $booking->status === 'approved' ? '#198754' : '#ffc107'; // Hijau jika OK, Kuning jika Pending
            $txtColor = $booking->status === 'pending' ? '#000000' : '#ffffff';

            $events[] = [
                'title' => 'Peminjaman: ' . $booking->purpose,
                'start' => $booking->booking_date . 'T' . $booking->start_time,
                'end'   => $booking->booking_date . 'T' . $booking->end_time,
                'color' => $bgColor,
                'textColor' => $txtColor
            ];
        }

        // Kembalikan dalam bentuk response JSON siap pakai untuk FullCalendar
        return response()->json($events);
    }
}