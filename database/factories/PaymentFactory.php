<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\ReservationStats;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        // Mengambil reservation acak
        $reservation = Reservation::inRandomOrder()->first();

        // Menentukan status pembayaran
        $paymentStatus = $this->faker->randomElement(['completed', 'pending', 'refunded']);

        if ($reservation->status == ReservationStatus::COMPLETED->value) {
            $paymentStatus = PaymentStatus::COMPLETED->value;
        } else if ($reservation->status == ReservationStatus::CANCELLED->value) {
            $paymentStatus = PaymentStatus::REFUNDED->value;
        }

        return [
            'id' => Str::uuid(),
            'reservation_id' => $reservation->id, // Menghubungkan dengan reservation
            'status' => $paymentStatus, // Status pembayaran acak
            'amount' => $this->faker->randomFloat(2, 100, 1000), // Jumlah pembayaran acak
            'method' => $this->faker->randomElement(['credit-card', 'debit-card', 'cash']), // Metode pembayaran
            'number' => 'P' . $this->faker->unique()->randomNumber(5), // Nomor pembayaran unik
            'number_series' => $this->faker->randomNumber(3),
            'number_payment' => $this->faker->randomNumber(4),
            'type' => $this->faker->randomElement(['initial', 'balance', 'extend']), // Tipe pembayaran
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
