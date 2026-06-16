<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'my_bookings_count' => Booking::where('user_id', $user->id)->count(),
            'approved_bookings_count' => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
            'pending_bookings_count' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
        ];

        $upcomingBookings = Booking::with('room')
            ->where('user_id', $user->id)
            ->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'room_name' => $booking->room ? $booking->room->room_name : '-',
                    'room_id' => $booking->room ? $booking->room->room_code : '-',
                    'booking_date' => $booking->booking_date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                    'status' => $booking->status,
                ];
            });

        return Inertia::render('Dashboard', [
            'stats' => $stats,
            'upcomingBookings' => $upcomingBookings,
        ]);
    }

    public function admin()
    {
        $stats = [
            'rooms_count' => Room::count(),
            'pending_bookings_count' => Booking::where('status', 'pending')->count(),
            'approved_bookings_count' => Booking::where('status', 'approved')->count(),
            'bookings_count' => Booking::count(),
        ];

        $pendingBookings = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->orderBy('booking_date')
            ->orderBy('start_time')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'user_name' => $booking->user ? $booking->user->name : 'Unknown User',
                    'user_phone' => $booking->user ? $booking->user->nomor_induk : '-',
                    'room_name' => $booking->room ? $booking->room->room_name : '-',
                    'room_id' => $booking->room ? $booking->room->room_code : '-',
                    'booking_date' => $booking->booking_date,
                    'start_time' => $booking->start_time,
                    'end_time' => $booking->end_time,
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'pendingBookings' => $pendingBookings,
        ]);
    }
}