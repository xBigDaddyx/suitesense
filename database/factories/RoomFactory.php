<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Fetch a random room type with a UUID
        $roomType = RoomType::inRandomOrder()->first();

        return [
            'name' => $this->generateRoomNumber(),
            'room_type_id' => $roomType->id, // Assign a random room type
            'is_available' => $this->faker->boolean,
            'price' => $this->generatePrice($roomType->name),
            'hotel_id' => '9d70d287-f794-4b59-bd50-c84726480d37',
        ];
    }

    /**
     * Generate a unique room number (101-110 for example purposes).
     *
     * @return string
     */
    private function generateRoomNumber()
    {
        static $roomNumber = 100;
        $roomNumber++;
        return (string) $roomNumber;
    }

    /**
     * Generate a price based on room type.
     *
     * @param string $roomTypeName
     * @return float
     */
    private function generatePrice($roomTypeName)
    {
        switch ($roomTypeName) {
            case 'Single Room':
                return 50.00;
            case 'Double Room':
                return 75.00;
            case 'Twin Room':
                return 70.00;
            case 'Deluxe Room':
                return 120.00;
            case 'Suite':
                return 200.00;
            case 'Family Room':
                return 150.00;
            case 'Executive Room':
                return 180.00;
            case 'Junior Suite':
                return 160.00;
            case 'Presidential Suite':
                return 300.00;
            case 'Accessible Room':
                return 90.00;
            default:
                return 100.00;
        }
    }
}
