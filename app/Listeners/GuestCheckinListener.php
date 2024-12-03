<?php

namespace App\Listeners;

use App\Enums\GuestStatus;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Events\GuestCheckinEvent;
use App\States\Guest\CheckedIn as GuestCheckedIn;
use App\States\Reservation\CheckedIn;
use App\States\Room\Occupied;
use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;
use Filament\Notifications\Actions\Action;

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
        $record->state->transitionTo(CheckedIn::class);
        $record->checked_in_by = $event->user->id;
        $record->guest_status->transitionTo(GuestCheckedIn::class);
        $record->guest_check_in_at = now();
        $record->room->state->transitionTo(Occupied::class);
        switch ($record->save()) {
            case true:
                //Broadcast Notification
                Notifications\Notification::make()
                    ->title('Check-In Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest name ' . $record->guest->name . ' has been checked in successfully to room ' . $record->room->name . '.')
                    ->actions([
                        Action::make('view')
                            ->label('View Reservation')
                            ->icon('tabler-calendar-event')
                            ->button()
                            ->url(function () use ($record) {
                                return route('filament.frontOffice.resources.rooms.manageReservations', ['tenant' => Filament::getTenant(), 'record' =>  $record->room]);
                            }),
                    ])
                    ->broadcast($event->user);
                //Database Notification
                Notifications\Notification::make()
                    ->title('Check-In Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest name ' . $record->guest->name . ' has been checked in successfully to room ' . $record->room->name . '.')
                    ->actions([
                        Action::make('view')
                            ->label('View Reservation')
                            ->icon('tabler-calendar-event')
                            ->button()
                            ->url(function () use ($record) {
                                return route('filament.frontOffice.resources.rooms.manageReservations', ['tenant' => Filament::getTenant(), 'record' =>  $record->room]);
                            }),
                    ])
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
