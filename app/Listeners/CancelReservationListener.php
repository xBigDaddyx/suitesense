<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Enums\ReservationStatus;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;

class CancelReservationListener
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
    public function handle(object $event): void
    {
        $record = $event->record;
        $user = $event->user;
        $data = $event->data;

        $record->status = ReservationStatus::CANCELLED->value;
        $record->cancelled_by = $user->id;
        $record->cancelled_at = now();
        $record->cancelled_reason = $data['reason'];
        switch ($record->save()) {
            case true:
                if ($data['is_refund'] === true) {
                    $payments = Payment::where('reservation_id', $record->id)->where('status', PaymentStatus::COMPLETED->value)->get();
                    $payments->each(function ($payment) {
                        $payment->refund();
                    });
                }
                Notifications\Notification::make()
                    ->title('Cancel Reservation Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Reservation has been cancelled successfully. Thank you for your service!')
                    ->broadcast($event->user);
                break;
            case false:
                Notifications\Notification::make()
                    ->title('Cancel Reservation Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the cancellation process. Please try again or contact support.')
                    ->broadcast($event->user);
                break;
        }
    }
}
