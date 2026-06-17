<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::latest()->get()->map(function ($room) {
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

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms
        ]);
    }

    public function create()
    {
        return view('rooms.create');
    }

    public function store(Request $request)
    {
            $validated = $request->validate(
        [
            'room_code' => 'required|unique:rooms',
            'room_name' => 'required',
            'category' => 'required|in:kelas,laboratorium,aula',
            'capacity' => 'required|integer|min:1',
        ],
        [
            'room_code.required' => 'Kode ruangan wajib diisi.',
            'room_code.unique' => 'Kode ruangan sudah digunakan.',

            'room_name.required' => 'Nama ruangan wajib diisi.',

            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori tidak valid.',

            'capacity.required' => 'Kapasitas wajib diisi.',
            'capacity.integer' => 'Kapasitas harus berupa angka.',
            'capacity.min' => 'Kapasitas minimal 1.',
        ]
    );

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        return view(
            'rooms.edit',
            compact('room')
        );
    }

    public function update(
    Request $request,
    Room $room
    )
    {
        $validated = $request->validate(
            [
                'room_code' => 'required|unique:rooms,room_code,' . $room->id,
                'room_name' => 'required',
                'category' => 'required|in:kelas,laboratorium,aula',
                'capacity' => 'required|integer|min:1',
            ],
            [
                'room_code.required' => 'Kode ruangan wajib diisi.',
                'room_code.unique' => 'Kode ruangan sudah digunakan.',

                'room_name.required' => 'Nama ruangan wajib diisi.',

                'category.required' => 'Kategori wajib dipilih.',
                'category.in' => 'Kategori tidak valid.',

                'capacity.required' => 'Kapasitas wajib diisi.',
                'capacity.integer' => 'Kapasitas harus berupa angka.',
                'capacity.min' => 'Kapasitas minimal 1.',
            ]
        );

        $room->update($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil diperbarui');
    }

    public function destroy(Room $room)
    {
        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Ruangan berhasil dihapus');
    }
}