<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\ScheduleCache;
use Illuminate\Http\Request;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ScheduleCache;
use App\Models\Room;

use Inertia\Inertia;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $roomCode = $request->query('room_id');

        $rooms = Room::select('id', 'room_code', 'room_name', 'category', 'capacity', 'photo', 'description', 'is_active')
            ->where('is_active', true)
            ->orderBy('room_name')
            ->get()
            ->map(function ($room) {
                return [
                    'id' => $room->id,
                    'room_code' => $room->room_code,
                    'room_name' => $room->room_name,
                    'category' => $room->category,
                    'capacity' => $room->capacity,
                    'photo_path' => $room->photo,
                    'description' => $room->description,
                    'status' => $room->is_active ? 'aktif' : 'nonaktif',
                ];
            });

        $selectedRoom = null;
        $schedules = collect();

        if ($roomCode) {
            $selectedRoom = Room::where('room_code', $roomCode)
                ->select('id', 'room_code', 'room_name', 'category', 'capacity', 'photo', 'description', 'is_active')
                ->first();

            $schedules = ScheduleCache::where('room_code', $roomCode)
                ->orderBy('schedule_date')
                ->orderBy('start_time')
                ->get()
                ->map(function ($schedule) {
                    return [
                        'id' => $schedule->id,
                        'room_code' => $schedule->room_code,
                        'course_code' => $schedule->course_code,
                        'course_name' => $schedule->course_name,
                        'lecturer_name' => $schedule->lecturer_name,
                        'schedule_date' => $schedule->schedule_date,
                        'start_time' => $schedule->start_time,
                        'end_time' => $schedule->end_time,
                    ];
                });
        }

        return Inertia::render('Calendar/Index', [
            'rooms' => $rooms,
            'selectedRoom' => $selectedRoom ? [
                'id' => $selectedRoom->id,
                'room_code' => $selectedRoom->room_code,
                'room_name' => $selectedRoom->room_name,
                'category' => $selectedRoom->category,
                'capacity' => $selectedRoom->capacity,
                'photo_path' => $selectedRoom->photo,
                'description' => $selectedRoom->description,
                'status' => $selectedRoom->is_active ? 'aktif' : 'nonaktif',
            ] : null,
            'schedules' => $schedules,
            'selectedRoomCode' => $roomCode,
    public function index()
    {
        $rooms = Room::select('id', 'room_name', 'room_code')->get();
        return Inertia::render('Calendar/Index', [
            'rooms' => $rooms
        ]);
    }

    public function getEvents(Request $request)
    {
        $roomCode = $request->query('room_id');

        $query = ScheduleCache::query();

        if ($roomCode) {
            $query->where('room_code', $roomCode);
        }

        return response()->json(
            $query->orderBy('schedule_date')
                ->orderBy('start_time')
                ->get()
        );
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