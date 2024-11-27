<?php

namespace App\Listeners;

use App\Enums\GuestStatus;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Events\GuestCheckinEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;

class GuestCheckinListener
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
    public function handle(GuestCheckinEvent $event): void
    {
        $record = $event->record;
        $data = $event->data;

        $record->status = ReservationStatus::CONFIRMED->value;
        $record->has_payment = true;
        $record->checked_in_by = $event->user->id;
        $record->guest_status = GuestStatus::CHECKIN->value;
        if ($data['guest_check_in_at'] === null) {
            $record->guest_check_in_at = now();
        } else {
            $record->guest_check_in_at = $data['guest_check_in_at'];
        }
        $record->room->status = RoomStatus::OCCUPIED->value;
        switch ($record->save()) {
            case true:
                //Broadcast Notification
                Notifications\Notification::make()
                    ->title('Check-In Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest check-in has been completed. Ready for the next steps!')
                    ->broadcast($event->user);
                //Database Notification
                Notifications\Notification::make()
                    ->title('Check-In Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest check-in has been completed. Ready for the next steps!')
                    ->sendToDatabase($event->user);
                break;
            case false:
                //Broadcast Notification
                Notifications\Notification::make()
                    ->title('Check-In Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the check-in process. Please try again or contact support.')
                    ->broadcast($event->user);
                //Database Notification
                Notifications\Notification::make()
                    ->title('Check-In Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the check-in process. Please try again or contact support.')
                    ->sendToDatabase($event->user);
                break;
        }
    }
}
