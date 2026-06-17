<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Inertia\Inertia;
use App\Models\Booking;
use App\Models\ScheduleCache;
use Illuminate\Http\Request;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where(
            'is_active',
            true
        )->get();

        return Inertia::render('Booking/Create', [
            'rooms' => $rooms
        ]);
    }

    public function store(
        Request $request,
        WhatsAppService $waService
    )
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

                'room_id.exists' =>
                    'Ruangan tidak ditemukan.',

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
            $validated['room_id']
        );

        /*
        =================================
        CEK BENTROK BOOKING
        =================================
        */

        $bookingConflict = Booking::where(
                'room_id',
                $validated['room_id']
            )
            ->where(
                'booking_date',
                $validated['booking_date']
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
                $validated['end_time']
            )
            ->where(
                'end_time',
                '>',
                $validated['start_time']
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
                $validated['booking_date']
            )
            ->where(
                'start_time',
                '<',
                $validated['end_time']
            )
            ->where(
                'end_time',
                '>',
                $validated['start_time']
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

        $booking = Booking::create([

            'user_id' =>
                auth()->id(),

            'room_id' =>
                $validated['room_id'],

            'booking_type' =>
                $validated['booking_type'],

            'booking_date' =>
                $validated['booking_date'],

            'start_time' =>
                $validated['start_time'],

            'end_time' =>
                $validated['end_time'],

            'purpose' =>
                $validated['purpose'],

            'status' =>
                'pending'
        ]);

        /*
        =================================
        KIRIM WHATSAPP KE ADMIN
        =================================
        */

        try {

            $pesanAdmin =
                "📢 PENGAJUAN PEMINJAMAN RUANGAN BARU\n\n" .
                "Nama: " . auth()->user()->name . "\n" .
                "Nomor Induk: " . auth()->user()->nomor_induk . "\n\n" .
                "Ruangan: " . $room->room_name . "\n" .
                "Kode Ruangan: " . $room->room_code . "\n" .
                "Jenis: " . $validated['booking_type'] . "\n\n" .
                "Tanggal: " . $validated['booking_date'] . "\n" .
                "Jam: " . $validated['start_time'] .
                " - " . $validated['end_time'] . "\n\n" .
                "Keperluan:\n" .
                $validated['purpose'];

            $adminPhone = config('services.whatsapp.admin_number');

            if (!empty($adminPhone)) {

                $waService->sendMessage(
                    $adminPhone,
                    $pesanAdmin
                );
            } else {
                Log::warning('ADMIN_PHONE_NUMBER belum dikonfigurasi, notifikasi booking ke admin dilewati.');
            }

        } catch (\Exception $e) {

            Log::error(
                'Gagal kirim WA ke admin: ' .
                $e->getMessage()
            );
        }

        return redirect('/dashboard')
            ->with(
                'success',
                'Pengajuan booking berhasil dikirim.'
            );
    }
}