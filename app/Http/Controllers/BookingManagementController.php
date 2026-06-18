<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class BookingManagementController extends Controller
{
    public function history()
    {
        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id'               => $booking->id,
                    'room_name'        => $booking->room ? $booking->room->room_name : '-',
                    'room_code'        => $booking->room ? $booking->room->room_code : '-',
                    'booking_type'     => $booking->booking_type,
                    'booking_date'     => $booking->booking_date,
                    'start_time'       => $booking->start_time,
                    'end_time'         => $booking->end_time,
                    'purpose'          => $booking->purpose,
                    'status'           => $booking->status,
                    'rejection_reason' => $booking->rejection_reason,
                    'created_at'       => $booking->created_at->format('d M Y'),
                ];
            });

        return Inertia::render('Bookings/History', [
            'bookings' => $bookings
        ]);
    }

    public function show($id)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);
        return view('user.booking.detail', compact('booking'));
    }

    public function cancel($id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking yang sudah diproses tidak bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()->route('bookings.history')->with('success', 'Booking berhasil dibatalkan.');
    }

    public function adminIndex()
    {
        $bookings = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id'               => $booking->id,
                    'user_name'        => $booking->user->name ?? 'Unknown',
                    'user_nomor_induk' => $booking->user->nomor_induk ?? '-',
                    'user_phone'       => $booking->user->phone_number ?? '-',
                    'room_name'        => $booking->room->room_name ?? '-',
                    'room_code'        => $booking->room->room_code ?? '-',
                    'booking_type'     => $booking->booking_type,
                    'booking_date'     => $booking->booking_date,
                    'start_time'       => $booking->start_time,
                    'end_time'         => $booking->end_time,
                    'purpose'          => $booking->purpose,
                    'status'           => $booking->status,
                    'created_at'       => $booking->created_at->format('d M Y, H:i'),
                ];
            });

        return Inertia::render('Admin/Bookings/Index', [
            'bookings' => $bookings
        ]);
    }

    public function approve($id, WhatsAppService $waService)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking hanya bisa diproses dari status pending.');
        }
        
        $booking->update(['status' => 'approved']);

        $pesanTemplate = "Halo {$booking->user->name},\n\n"
                        . "Pengajuan peminjaman ruangan untuk kegiatan *\"{$booking->purpose}\"* telah *DISETUJUI*.\n\n"
                        . "🗓️ Tanggal: {$booking->booking_date}\n"
                        . "⏰ Waktu: {$booking->start_time} - {$booking->end_time}";

        if ($booking->user->phone_number) {
            $waService->sendMessage($booking->user->phone_number, $pesanTemplate);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil disetujui.');
    }

    public function reject(Request $request, $id, WhatsAppService $waService)
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking hanya bisa diproses dari status pending.');
        }
        
        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $pesanTemplate = "Halo {$booking->user->name},\n\n"
                        . "Mohon maaf, pengajuan peminjaman untuk \"{$booking->purpose}\" telah *DITOLAK*.\n"
                        . "❌ Alasan: {$request->rejection_reason}";

        if ($booking->user->phone_number) {
            $waService->sendMessage($booking->user->phone_number, $pesanTemplate);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking telah ditolak.');
    }

    public function downloadSurat(Booking $booking)
    {
        if (!$booking->surat_file) abort(404);
        return Storage::disk('public')->download($booking->surat_file, $booking->surat_original_name);
    }
}