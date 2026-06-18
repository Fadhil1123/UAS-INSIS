<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Storage;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BookingManagementController extends Controller
{
    // 1. TAMPILKAN RIWAYAT BOOKING USER (Mahasiswa/Dosen)
    public function history()
    {
        // Ambil booking milik user yang sedang login, gabungkan dengan data ruangan (room)
        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'id'               => $booking->id,
                    'room_name'        => $booking->room->room_name ?? '-',
                    'room_code'        => $booking->room->room_code ?? '-',
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

        return Inertia::render('Booking/History', [
            'bookings' => $bookings
        ]);
    }

    // 2. TAMPILKAN DETAIL BOOKING
    public function show($id)
    {
        // Ambil detail booking beserta data user dan room nya
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        return view('user.booking.detail', compact('booking'));
    }

    // 3. BATALKAN BOOKING OLEH USER (Cancel)
    public function cancel($id)
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($id);

        // Validasi: Hanya booking berstatus 'pending' yang boleh dibatalkan
        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'Booking yang sudah diproses tidak bisa dibatalkan.');
        }

        // Ubah status menjadi cancelled (dua 'l' sesuai enum migration Anda)
        $booking->update([
            'status' => 'cancelled'
        ]);

        return redirect()->route('bookings.history')->with('success', 'Booking berhasil dibatalkan.');
    }

    // 4. TAMPILKAN DAFTAR PENGAJUAN MASUK DI HALAMAN ADMIN
    public function adminIndex()
    {
        // Ambil semua booking yang statusnya 'pending' untuk diverifikasi admin
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

    // 5. ADMIN MENYETUJUI BOOKING (Approve)
    public function approve($id, WhatsAppService $waService)
    {
        $booking = Booking::with([
            'user',
            'room'
        ])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking hanya bisa diproses dari status pending.');
        }
        
        $booking->update([
            'status' => 'approved'
        ]);

        $noHpUser = $booking->user->phone_number;
        
        $pesanTemplate = "Halo {$booking->user->name},\n\n"
                       . "Pengajuan peminjaman ruangan Anda untuk kegiatan *\"{$booking->purpose}\"* telah *DISETUJUI* oleh Admin.\n\n"
                        . "Detail Kegiatan:\n"
                        . "🗓️ Tanggal: {$booking->booking_date}\n"
                        . "⏰ Waktu: {$booking->start_time} - {$booking->end_time}\n\n"
                        . "Silakan gunakan ruangan dengan tertib. Terima kasih.";

        if (!empty($noHpUser)) {
            $waService->sendMessage($noHpUser, $pesanTemplate);
        } else {
            Log::warning("Booking #{$booking->id} disetujui, tetapi nomor WhatsApp user kosong.");
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil disetujui.');
    }

    // 6. ADMIN MENOLAK BOOKING (Reject)
    public function reject(Request $request, $id, WhatsAppService $waService)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking = Booking::with([
            'user',
            'room'
        ])->findOrFail($id);

        if ($booking->status !== 'pending') {
            return redirect()->route('admin.bookings.index')->with('error', 'Booking hanya bisa diproses dari status pending.');
        }
        
        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason
        ]);

        $noHpUser = $booking->user->phone_number;
        
        $pesanTemplate = "Halo {$booking->user->name},\n\n"
                       . "Mohon maaf, pengajuan peminjaman ruangan Anda untuk kegiatan *\"{$booking->purpose}\"* telah *DITOLAK* oleh Admin.\n\n"
                       . "❌ *Alasan Penolakan:* {$request->rejection_reason}\n\n"
                        . "Silakan ajukan kembali dengan menyesuaikan jadwal atau ruangan lain. Terima kasih.";

        if (!empty($noHpUser)) {
            $waService->sendMessage($noHpUser, $pesanTemplate);
        } else {
            Log::warning("Booking #{$booking->id} ditolak, tetapi nomor WhatsApp user kosong.");
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Booking telah ditolak.');
    }

    public function downloadSurat(Booking $booking)
    {
        if (!$booking->surat_file) {
            abort(404, 'Surat tidak ditemukan.');
        }

        return Storage::disk('public')->download(
            $booking->surat_file,
            $booking->surat_original_name
        );
    }
}