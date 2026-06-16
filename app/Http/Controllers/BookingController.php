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
        $validated = $request->validate(
            [
                'room_id' =>
                    'required|exists:rooms,id',

                'booking_type' =>
                    'required|in:perkuliahan,organisasi',

                'booking_date' =>
                    'required|date',

                'start_time' =>
                    'required',

                'end_time' =>
                    'required|after:start_time',

                'purpose' =>
                    'required|string'
            ],
            [
                'room_id.required' =>
                    'Ruangan wajib dipilih.',

                'booking_type.required' =>
                    'Jenis peminjaman wajib dipilih.',

                'booking_date.required' =>
                    'Tanggal wajib diisi.',

                'start_time.required' =>
                    'Jam mulai wajib diisi.',

                'end_time.required' =>
                    'Jam selesai wajib diisi.',

                'end_time.after' =>
                    'Jam selesai harus lebih besar dari jam mulai.',

                'purpose.required' =>
                    'Keperluan wajib diisi.'
            ]
        );

        $room = Room::findOrFail(
            $request->room_id
        );

        /*
        =================================
        CEK BENTROK BOOKING
        =================================
        */

        $bookingConflict = Booking::where(
                'room_id',
                $request->room_id
            )
            ->where(
                'booking_date',
                $request->booking_date
            )
            ->whereIn(
                'status',
                [
                    'pending',
                    'approved'
                ]
            )
            ->where(
                'start_time',
                '<',
                $request->end_time
            )
            ->where(
                'end_time',
                '>',
                $request->start_time
            )
            ->exists();

        if ($bookingConflict) {

            return back()
                ->withErrors([
                    'room_id' =>
                        'Ruangan sudah dibooking pada jam tersebut.'
                ])
                ->withInput();
        }

        /*
        =================================
        CEK BENTROK JADWAL KULIAH
        =================================
        */

        $scheduleConflict = ScheduleCache::where(
                'room_code',
                $room->room_code
            )
            ->where(
                'schedule_date',
                $request->booking_date
            )
            ->where(
                'start_time',
                '<',
                $request->end_time
            )
            ->where(
                'end_time',
                '>',
                $request->start_time
            )
            ->exists();

        if ($scheduleConflict) {

            return back()
                ->withErrors([
                    'room_id' =>
                        'Ruangan sedang digunakan untuk perkuliahan.'
                ])
                ->withInput();
        }

        /*
        =================================
        SIMPAN BOOKING
        =================================
        */

        Booking::create([

            'user_id' =>
                auth()->id(),

            'room_id' =>
                $request->room_id,

            'booking_type' =>
                $request->booking_type,

            'booking_date' =>
                $request->booking_date,

            'start_time' =>
                $request->start_time,

            'end_time' =>
                $request->end_time,

            'purpose' =>
                $request->purpose,

            'status' =>
                'pending'
        ]);

        return redirect('/dashboard')
            ->with(
                'success',
                'Pengajuan booking berhasil dikirim.'
            );
    }
}
