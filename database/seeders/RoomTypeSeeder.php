<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Single Room',
                'description' => 'A cozy room designed for solo travelers, equipped with a single bed, desk, and essential amenities. Perfect for guests needing a simple, affordable stay.',
                'facilities' => json_encode(['Single Bed', 'Free Wi-Fi', 'Air Conditioning', 'Flat-Screen TV']),
            ],
            [
                'name' => 'Double Room',
                'description' => 'Ideal for couples or friends, this room features a double bed, a comfortable seating area, and modern amenities for a relaxing stay.',
                'facilities' => json_encode(['Double Bed', 'Free Wi-Fi', 'Air Conditioning', 'Mini Bar', 'Flat-Screen TV']),
            ],
            [
                'name' => 'Twin Room',
                'description' => 'A spacious room with two single beds, ideal for friends or colleagues traveling together who prefer separate sleeping arrangements.',
                'facilities' => json_encode(['Two Single Beds', 'Free Wi-Fi', 'Air Conditioning', 'Flat-Screen TV', 'Work Desk']),
            ],
            [
                'name' => 'Deluxe Room',
                'description' => 'A luxurious, larger room with a king-sized bed, high-end furnishings, and enhanced amenities, including a mini-bar, spacious bathroom, and sometimes a view.',
                'facilities' => json_encode(['King Bed', 'Free Wi-Fi', 'Air Conditioning', 'Mini Bar', 'Flat-Screen TV', 'Ocean View']),
            ],
            [
                'name' => 'Suite',
                'description' => 'A premium room offering separate living and sleeping areas, often with upscale decor, a larger bathroom, and extras like a kitchenette or in-room entertainment.',
                'facilities' => json_encode(['King Bed', 'Free Wi-Fi', 'Air Conditioning', 'Mini Bar', 'Flat-Screen TV', 'Living Area', 'Kitchenette']),
            ],
            [
                'name' => 'Family Room',
                'description' => 'Designed for families, this room typically includes multiple beds or bunk beds, ample storage, and kid-friendly amenities.',
                'facilities' => json_encode(['Two Double Beds', 'Free Wi-Fi', 'Air Conditioning', 'Flat-Screen TV', 'Kid-Friendly Amenities']),
            ],
            [
                'name' => 'Executive Room',
                'description' => 'Tailored for business travelers, featuring a workspace, fast Wi-Fi, and additional conveniences like access to executive lounges or complimentary breakfast.',
                'facilities' => json_encode(['King Bed', 'Free Wi-Fi', 'Air Conditioning', 'Work Desk', 'Executive Lounge Access', 'Complimentary Breakfast']),
            ],
            [
                'name' => 'Junior Suite',
                'description' => 'A smaller suite with an open-plan layout, combining a sleeping area and sitting area, ideal for guests who want a bit more space than a standard room.',
                'facilities' => json_encode(['Queen Bed', 'Free Wi-Fi', 'Air Conditioning', 'Mini Bar', 'Flat-Screen TV', 'Sitting Area']),
            ],
            [
                'name' => 'Presidential Suite',
                'description' => 'The epitome of luxury, with multiple rooms, premium furnishings, private balconies, and exclusive services like a personal concierge.',
                'facilities' => json_encode(['King Bed', 'Free Wi-Fi', 'Air Conditioning', 'Mini Bar', 'Private Balcony', 'Personal Concierge', 'Jacuzzi']),
            ],
            [
                'name' => 'Accessible Room',
                'description' => 'A room designed to accommodate guests with disabilities, featuring accessible bathrooms, wider doors, and other ADA-compliant amenities.',
                'facilities' => json_encode(['Accessible Bathroom', 'Wider Doors', 'Free Wi-Fi', 'Flat-Screen TV', 'Support Handles']),
            ],
        ];

        foreach ($roomTypes as $type) {
            RoomType::create($type);
        }
    }
}
