<?php

namespace App\Observers;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ReservationStatus;
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
            'type' => PaymentType::INITIAL->value,
        ]);
        if ($reservation->room->status === RoomStatus::AVAILABLE->value) {
            $reservation->room->is_available = false;
            $reservation->room->status = RoomStatus::BOOKED->value;
            $reservation->room->save();
        }
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        switch ($reservation->status) {
            case ReservationStatus::CONFIRMED->value:
                $reservation->room->is_available = false;
                $reservation->room->status = RoomStatus::OCCUPIED->value;
                $reservation->room->save();
                break;
            case ReservationStatus::COMPLETED->value:
                $reservation->room->is_available = false;
                $reservation->room->status = RoomStatus::MAINTENANCE->value;
                $reservation->room->save();
                break;
        }
        // if ($reservation->is_extended === true) {
        //     $balance = Payment::where('reservation_id', $reservation->id)->where('status', PaymentStatus::PENDING->value)->where('type', PaymentType::BALANCE->value);
        //     if (!empty($balance) || $balance !== null) {
        //         $amount = $reservation->total_price - $balance->amount;
        //     } else {
        //         $amount = $reservation->total_price;
        //     }
        //     Payment::create([
        //         'reservation_id' => $reservation->id,
        //         'amount' => $amount,
        //         'status' => PaymentStatus::PENDING->value,
        //         'method' => PaymentMethod::CASH->value,
        //         'type' => PaymentType::INITIAL->value,
        //     ]);
        // }
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
