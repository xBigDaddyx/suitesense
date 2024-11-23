<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Hotel::create([
            'name' => 'Hotel Suitify',
            'address' => '123 Main Street, Anytown, USA',
            'country' => 'US',
            'city' => 'Anytown',
            'phone' => '08123456789',
        ]);
    }
}
