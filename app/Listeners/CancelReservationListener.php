<?php

namespace App\Listeners;

use App\Enums\ReservationStatus;
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

        $record->status = ReservationStatus::CANCELLED->value;
        $record->cancelled_by = $user->id;
        $record->cancelled_at = now();
        switch ($record->save()) {
            case true:
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
