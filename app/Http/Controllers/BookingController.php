<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Booking;
use App\Models\ScheduleCache;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function create()
    {
        $rooms = Room::where('is_active', true)->get();
        return Inertia::render('Booking/Create', ['rooms' => $rooms]);
    }

    public function store(Request $request, WhatsAppService $waService)
    {
        $validated = $request->validate(
            [
                'room_id'      => 'required|exists:rooms,id',
                'booking_type' => 'required|in:perkuliahan,organisasi',
                'surat_file'   => 'required_if:booking_type,organisasi|nullable|file|mimes:pdf|max:2048',
                'booking_date' => 'required|date',
                'start_time'   => 'required',
                'end_time'     => 'required|after:start_time',
                'purpose'      => 'required|string',
                'phone_number' => 'required|string|min:10|max:15'
            ],
            [
                'room_id.required' => 'Ruangan wajib dipilih.',
                'room_id.exists' => 'Ruangan tidak ditemukan.',
                'booking_type.required' => 'Jenis peminjaman wajib dipilih.',
                'surat_file.required_if' => 'Surat peminjaman wajib diupload untuk kegiatan organisasi.',
                'surat_file.mimes' => 'File harus berformat PDF.',
                'surat_file.max' => 'Ukuran file maksimal 2 MB.',
                'booking_date.required' => 'Tanggal wajib diisi.',
                'start_time.required' => 'Jam mulai wajib diisi.',
                'end_time.required' => 'Jam selesai wajib diisi.',
                'end_time.after' => 'Jam selesai harus lebih besar dari jam mulai.',
                'purpose.required' => 'Keperluan wajib diisi.',
                'phone_number.required' => 'Nomor WhatsApp wajib diisi.',
                'phone_number.min' => 'Nomor WhatsApp minimal 10 karakter.',
                'phone_number.max' => 'Nomor WhatsApp maksimal 15 karakter.'
            ]
        );

        $user = auth()->user();
        if ($user && $user->phone_number !== $validated['phone_number']) {
            $user->update(['phone_number' => $validated['phone_number']]);
        }

        $room = Room::findOrFail($validated['room_id']);

        // Cek Bentrok Booking
        $isConflict = Booking::where('room_id', $validated['room_id'])
            ->where('booking_date', $validated['booking_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        // Cek Bentrok Jadwal Kuliah
        $isScheduleConflict = ScheduleCache::where('room_code', $room->room_code)
            ->where('schedule_date', $validated['booking_date'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->exists();

        if ($isConflict || $isScheduleConflict) {
            return back()->withErrors(['room_id' => 'Ruangan tidak tersedia pada waktu tersebut.'])->withInput();
        }

        $suratPath = $request->hasFile('surat_file') ? $request->file('surat_file')->store('surat-peminjaman', 'public') : null;
        $suratName = $request->hasFile('surat_file') ? $request->file('surat_file')->getClientOriginalName() : null;

        Booking::create([
            'user_id'             => auth()->id(),
            'room_id'             => $validated['room_id'],
            'booking_type'        => $validated['booking_type'],
            'surat_file'          => $suratPath,
            'surat_original_name' => $suratName,
            'booking_date'        => $validated['booking_date'],
            'start_time'          => $validated['start_time'],
            'end_time'            => $validated['end_time'],
            'purpose'             => $validated['purpose'],
            'status'              => 'pending'
        ]);

        return redirect('/dashboard')->with('success', 'Pengajuan booking berhasil dikirim.');
    }

    public function downloadTemplate()
    {
        $path = storage_path('app/public/template-surat.docx');

        if (!file_exists($path)) {
            abort(404, 'Template surat tidak ditemukan.');
        }

        return response()->download($path, 'Template-Surat-Peminjaman-Ruangan.docx');
    }
}
