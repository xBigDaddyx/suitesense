<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hotel =  Hotel::where('name', 'Hotel Suitify')->first();
        $user1 = User::create([
            'name' => 'Faisal Yusuf',
            'email' => 'admin@suitify.cloud',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('C@rtini#5'),
            'phone' => '08123456789',
            'gender' => 'male',
            'identity_number' => '1234567890',
            'address' => '123 Main Street, Anytown, USA',
            'country' => 'US',
            'city' => 'Anytown',
            'postal_code' => '12345',
            'status' => 'active',
            'hotel_id' => $hotel->id,
        ]);
        $user1->assignRole('Vendor');
        $user1->hotels()->attach($hotel, ['department' => 'Vendor']);
    }
}
