<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = [
            ['floor' => 1, 'name' => 'Ruang Rapat A', 'description' => 'Ruang rapat lantai 1 dengan kapasitas 10 orang'],
            ['floor' => 2, 'name' => 'Ruang Rapat B', 'description' => 'Ruang rapat lantai 2 dengan kapasitas 12 orang'],
            ['floor' => 3, 'name' => 'Ruang Rapat C', 'description' => 'Ruang rapat lantai 3 dengan kapasitas 8 orang'],
            ['floor' => 4, 'name' => 'Ruang Rapat D', 'description' => 'Ruang rapat lantai 4 dengan kapasitas 15 orang'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(
                ['name' => $room['name'], 'floor' => $room['floor']],
                $room
            );
        }
    }
}
