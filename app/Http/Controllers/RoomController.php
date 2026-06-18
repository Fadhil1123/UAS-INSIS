<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->get()->map(function ($room) {
            return [
                'id'          => $room->id,
                'room_code'   => $room->room_code,
                'room_name'   => $room->room_name,
                'category'    => $room->category,
                'capacity'    => $room->capacity,
                'photo_path'  => $room->photo,
                'description' => $room->description,
                'status'      => $room->is_active ? 'aktif' : 'nonaktif',
            ];
        });

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Rooms/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'room_code' => 'required|unique:rooms',
                'room_name' => 'required',
                'category'  => 'required|in:kelas,laboratorium',
                'capacity'  => 'required|integer|min:1',
                'photo'     => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            ],
            [
                'room_code.required' => 'Kode ruangan wajib diisi.',
                'room_code.unique'   => 'Kode ruangan sudah digunakan.',
                'room_name.required' => 'Nama ruangan wajib diisi.',
                'category.required'  => 'Kategori wajib dipilih.',
                'category.in'        => 'Kategori tidak valid.',
                'capacity.required'  => 'Kapasitas wajib diisi.',
                'capacity.integer'   => 'Kapasitas harus berupa angka.',
                'capacity.min'       => 'Kapasitas minimal 1.',
                'photo.image'        => 'File harus berupa gambar.',
                'photo.max'          => 'Ukuran foto maksimal 2MB.',
            ]
        );

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')
                ->store('room-photos', 'public');
        }

        Room::create($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        return Inertia::render('Admin/Rooms/Edit', [
            'room' => $room,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate(
            [
                'room_code' => 'required|unique:rooms,room_code,' . $room->id,
                'room_name' => 'required',
                'category'  => 'required|in:kelas,laboratorium',
                'capacity'  => 'required|integer|min:1',
                'photo'     => 'nullable|image|mimes:jpeg,png,webp|max:2048',
            ],
            [
                'room_code.required' => 'Kode ruangan wajib diisi.',
                'room_code.unique'   => 'Kode ruangan sudah digunakan.',
                'room_name.required' => 'Nama ruangan wajib diisi.',
                'category.required'  => 'Kategori wajib dipilih.',
                'category.in'        => 'Kategori tidak valid.',
                'capacity.required'  => 'Kapasitas wajib diisi.',
                'capacity.integer'   => 'Kapasitas harus berupa angka.',
                'capacity.min'       => 'Kapasitas minimal 1.',
                'photo.image'        => 'File harus berupa gambar.',
                'photo.max'          => 'Ukuran foto maksimal 2MB.',
            ]
        );

        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($room->photo) {
                Storage::disk('public')->delete($room->photo);
            }
            $validated['photo'] = $request->file('photo')
                ->store('room-photos', 'public');
        } else {
            unset($validated['photo']);
        }

        $room->update($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    public function toggleStatus(Room $room)
    {
        $room->update(['is_active' => !$room->is_active]);

        return back()->with(
            'success',
            $room->is_active ? 'Ruangan berhasil diaktifkan' : 'Ruangan berhasil dinonaktifkan'
        );
    }

    public function destroy(Room $room)
    {
        if ($room->photo) {
            Storage::disk('public')->delete($room->photo);
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus');
    }
}