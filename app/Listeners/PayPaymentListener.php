<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\RoomStatus;
use App\Events\PayPaymentEvent;
use App\Models\Payment;
use App\States\Payment\Paid;
use App\States\Reservation\Confirmed;
use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;
use Filament\Notifications\Actions\Action;

class PayPaymentListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PayPaymentEvent $event): void
    {
        $record = $event->record;
        $record->update([
            'paid_amount' => $event->amount,
            'payment_method' => $event->payment_method,
        ]);
        $record->save();
    }
}
