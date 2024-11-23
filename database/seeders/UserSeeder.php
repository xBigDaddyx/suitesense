<?php

namespace Database\Seeders;

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
        $user1 = User::create([
            'name' => 'Faisal Yusuf',
            'email' => 'admin@suitify.cloud',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('C@rtini#5'),
        ]);
        $user1->assignRole('Vendor');
    }
}
