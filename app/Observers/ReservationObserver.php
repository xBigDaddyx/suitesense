<?php

namespace App\Observers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\RoomStatus;
use App\Models\Payment;
use App\Models\Reservation;

class ReservationObserver
{
    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $reservation->total_price,
            'status' => PaymentStatus::PENDING->value,
            'method' => PaymentMethod::CASH->value,
        ]);
        if ($reservation->room->status === RoomStatus::AVAILABLE->value) {
            $reservation->room->status = RoomStatus::BOOKED->value;
            $reservation->room->save();
        }
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "deleted" event.
     */
    public function deleted(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "restored" event.
     */
    public function restored(Reservation $reservation): void
    {
        //
    }

    /**
     * Handle the Reservation "force deleted" event.
     */
    public function forceDeleted(Reservation $reservation): void
    {
        //
    }
}
