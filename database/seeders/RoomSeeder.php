<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        Room::create([
            'room_code' => 'A101',
            'room_name' => 'Ruang A101',
            'category' => 'kelas',
            'capacity' => 40,
        ]);

        Room::create([
            'room_code' => 'A102',
            'room_name' => 'Ruang A102',
            'category' => 'kelas',
            'capacity' => 35,
        ]);

        Room::create([
            'room_code' => 'LAB01',
            'room_name' => 'Laboratorium Komputer',
            'category' => 'laboratorium',
            'capacity' => 30,
        ]);
    }
}