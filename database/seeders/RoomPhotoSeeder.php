<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomPhotoSeeder extends Seeder
{
    public function run(): void
    {
        $mappings = [
            'Ruang A.13' => 'room-photos/ruang-a13.jpeg',
            'Ruang A.14' => 'room-photos/ruang-a14.jpeg',
            'Ruang A.15' => 'room-photos/ruang-a15.jpeg',
            'Lab Big Data' => 'room-photos/lab-big-data.jpeg',
            'Lab. Komputer Dasar' => 'room-photos/lab-komputer-dasar.jpeg',
        ];

        foreach ($mappings as $code => $photo) {
            Room::where('room_code', $code)->update(['photo' => $photo]);
        }
    }
}
