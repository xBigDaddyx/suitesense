<?php

namespace Database\Factories;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\Guest;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Str;

class ReservationFactory extends Factory
{
    protected $model = Reservation::class;

    public function definition()
    {
        $id = Str::uuid();
        // Tentukan check-in dan check-out
        $check_in = $this->faker->dateTimeInInterval('-12 months', '+12 months');
        $check_out = $this->faker->dateTimeInInterval($check_in, '+2 months');

        // Tentukan guest_check_in_at dan guest_check_out_at
        $guest_check_in_at = null;
        $guest_check_out_at = null;
        $checked_in_by = null;

        // Tentukan status guest dan timestamp check-in/check-out
        $guest_status = $this->faker->randomElement(['pending', 'checked-in', 'checked-out']);

        // Tentukan status reservasi dan field yang terkait jika statusnya cancelled
        $status = $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']);
        $cancelled_at = null;
        $cancelled_by = null;
        $cancelled_reason = null;

        // Tentukan pembayaran status dan is_completed_payment
        $payment_status = 'pending';
        $is_completed_payment = false;

        // Jika status reservasi adalah 'completed', set status pembayaran dan is_completed_payment
        if ($status == 'completed') {
            $payment_status = 'completed'; // Pembayaran harus completed
            $is_completed_payment = true; // Pembayaran telah selesai
        }

        // Jika status reservasi adalah 'cancelled', set status pembayaran dan is_completed_payment
        if ($status == 'cancelled') {
            $payment_status = 'refund'; // Pembayaran dibatalkan (refund)
            $is_completed_payment = false; // Pembayaran belum selesai
        }

        // Jika status tamu adalah 'checked-in', set guest_check_in_at dan checked_in_by
        if ($guest_status == 'checked-in') {
            $guest_check_in_at = $check_in;
            $checked_in_by = 1; // Atur checked_in_by dengan ID user yang melakukan check-in (misal 1)
        }

        // Jika status tamu adalah 'checked-out', set guest_check_out_at
        if ($guest_status == 'checked-out') {
            $guest_check_out_at = $check_out;
        }

        // Jika status reservasi adalah 'cancelled', set cancelled_at, cancelled_by, dan cancelled_reason
        if ($status == 'cancelled') {
            $cancelled_at = now(); // Waktu pembatalan
            $cancelled_by = 1; // ID user yang melakukan pembatalan (misal 1)
            $cancelled_reason = $this->faker->sentence(); // Alasan pembatalan acak
        }



        return [
            'id' => $id,
            'total_price' => $this->faker->numberBetween(100, 1000), // Harga total
            'guest_id' => Guest::inRandomOrder()->first()->id, // Ambil guest secara acak
            'room_id' => Room::inRandomOrder()->first()->id, // Ambil room secara acak
            'check_in' => $check_in, // Check-in dalam waktu 1 bulan
            'check_out' => $check_out, // Check-out setelah 1 bulan
            'status' => $status, // Status reservasi
            'number' => 'R' . $this->faker->unique()->randomNumber(5), // Nomor reservasi unik
            'number_series' => $this->faker->randomNumber(3),
            'number_reservation' => $this->faker->randomNumber(5),
            'guest_status' => $guest_status, // Status guest
            'guest_check_in_at' => $guest_check_in_at, // Timestamp check-in
            'guest_check_out_at' => $guest_check_out_at, // Timestamp check-out
            'checked_in_by' => $checked_in_by, // ID pengguna yang melakukan check-in
            'cancelled_at' => $cancelled_at, // Timestamp pembatalan jika status 'cancelled'
            'cancelled_by' => $cancelled_by, // ID pengguna yang membatalkan
            'cancelled_reason' => $cancelled_reason, // Alasan pembatalan
            'is_completed_payment' => $is_completed_payment, // Menentukan apakah pembayaran selesai
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
