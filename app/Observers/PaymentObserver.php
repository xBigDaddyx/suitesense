<?php

namespace App\Observers;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Models\Payment;
use App\Models\Reservation;
use App\States\Payment\Paid;
use App\States\Reservation\Confirmed;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Notifications;
use Filament\Notifications\Actions;
use Spatie\ModelStates\State;

class PaymentObserver
{
    public function changeState(Payment $payment, $state): void
    {
        $payment->state->transitionTo($state);
    }
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void {}
    public function updating(Payment $payment): void
    {
        //
    }
    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
