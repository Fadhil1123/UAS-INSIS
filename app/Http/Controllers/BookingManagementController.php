<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking; // <-- Import Model Booking

class BookingManagementController extends Controller
{
    // 1. TAMPILKAN RIWAYAT BOOKING USER (Mahasiswa/Dosen)
    public function history()
    {
        // Ambil booking milik user yang sedang login, gabungkan dengan data ruangan (room)
        $bookings = Booking::with('room')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.booking.history', compact('bookings'));
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

        return redirect()->route('booking.history')->with('success', 'Booking berhasil dibatalkan.');
    }

    // 4. TAMPILKAN DAFTAR PENGAJUAN MASUK DI HALAMAN ADMIN
    public function adminIndex()
    {
        // Ambil semua booking yang statusnya 'pending' untuk diverifikasi admin
        $bookings = Booking::with(['user', 'room'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('admin.bookings.index', compact('bookings'));
    }

    // 5. ADMIN MENYETUJUI BOOKING (Approve)
    public function approve($id)
    {
        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'approved'
        ]);

        // [TEMPAT TRIGGER WHATSAPP BE-08 NANTI DI SINI]

        return redirect()->route('admin.bookings.index')->with('success', 'Booking berhasil disetujui.');
    }

    // 6. ADMIN MENOLAK BOOKING (Reject)
    public function reject(Request $request, $id)
    {
        // Validasi agar alasan penolakan wajib diisi oleh admin di form
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $booking = Booking::findOrFail($id);
        
        $booking->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason // Mengisi kolom alasan sesuai input admin
        ]);

        // [TEMPAT TRIGGER WHATSAPP BE-08 NANTI DI SINI]

        return redirect()->route('admin.bookings.index')->with('success', 'Booking telah ditolak.');
    }
}