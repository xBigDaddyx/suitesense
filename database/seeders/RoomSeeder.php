<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use App\Models\RoomType;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ensure each room type has at least one room
        $roomTypes = RoomType::all();
        foreach ($roomTypes as $type) {
            Room::factory()->create([
                'room_type_id' => $type->id,
            ]);
        }

        // Generate additional random rooms to reach a total of 10
        $additionalRooms = 30 - $roomTypes->count();
        Room::factory($additionalRooms)->create();
    }
}
