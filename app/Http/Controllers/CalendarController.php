<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\ScheduleCache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil daftar ruangan untuk dropdown
        $rooms = Room::select('id', 'room_name', 'room_code')->get();

        return Inertia::render('Calendar/Index', [
            'rooms' => $rooms
        ]);
    }

    public function getEvents(Request $request)
    {
        $roomId = $request->query('room_id');
        $events = [];

        // 1. AMBIL JADWAL DARI API (ScheduleCache)
        $schedules = ScheduleCache::query();
        
        if ($roomId) {
            $room = Room::find($roomId);
            if ($room) {
                $schedules->where('room_code', $room->room_code);
            }
        }
        
        foreach ($schedules->get() as $jadwal) {
            $events[] = [
                'title' => 'Kuliah: ' . $jadwal->course_name,
                'start' => $jadwal->schedule_date . 'T' . $jadwal->start_time,
                'end'   => $jadwal->schedule_date . 'T' . $jadwal->end_time,
                'color' => '#dc3545', // Merah untuk kuliah
                'textColor' => '#ffffff'
            ];
        }

        // 2. AMBIL DATA BOOKING LOKAL
        $bookings = Booking::whereIn('status', ['pending', 'approved']);
        
        if ($roomId) {
            $bookings->where('room_id', $roomId);
        }
        
        foreach ($bookings->get() as $booking) {
            $bgColor = $booking->status === 'approved' ? '#198754' : '#ffc107';
            $txtColor = $booking->status === 'pending' ? '#000000' : '#ffffff';

            $events[] = [
                'title' => 'Peminjaman: ' . $booking->purpose,
                'start' => $booking->booking_date . 'T' . $booking->start_time,
                'end'   => $booking->booking_date . 'T' . $booking->end_time,
                'color' => $bgColor,
                'textColor' => $txtColor
            ];
        }

        return response()->json($events);
    }
}