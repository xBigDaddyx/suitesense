<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Membuat 30 payment records secara otomatis
        \App\Models\Payment::factory(50)->create();
    }
}
