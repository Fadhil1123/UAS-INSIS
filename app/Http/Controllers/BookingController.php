<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\ScheduleCache;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where(
            'is_active',
            true
        )->get();

        return view(
            'bookings.create',
            compact('rooms')
        );
    }

    public function store(Request $request)
    {
    
    }
}
