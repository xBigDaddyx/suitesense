<?php

namespace App\Listeners;

use App\Events\GuestCheckoutEvent;
use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;
use App\Enums\GuestStatus;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\States\Guest\CheckedOut as GuestCheckedOut;
use App\States\Reservation\CheckedOut;
use App\States\Room\Cleaning;
use Filament\Notifications\Actions\Action;

class GuestCheckoutListener
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
    public function handle(GuestCheckoutEvent $event): void
    {
        $record = $event->record;
        $data = $event->data;


        $record->state->transitionTo(CheckedOut::class);
        $record->checked_out_by = $event->user->id;
        $record->guest_status->transitionTo(GuestCheckedOut::class);
        $record->guest_check_out_at = now();
        $record->room->state->transitionTo(Cleaning::class);
        switch ($record->save()) {
            case true:
                Notifications\Notification::make()
                    ->title('Check-Out Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest name ' . $record->guest->name . ' has been checked out successfully to room ' . $record->room->name . '.')
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
                Notifications\Notification::make()
                    ->title('Check-Out Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('Guest name ' . $record->guest->name . ' has been checked out successfully to room ' . $record->room->name . '.')
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
                Notifications\Notification::make()
                    ->title('Check-Out Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the check-out process. Please try again or contact support.')
                    ->broadcast($event->user);
                Notifications\Notification::make()
                    ->title('Check-Out Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the check-out process. Please try again or contact support.')
                    ->sendToDatabase($event->user);
                break;
        }
    }
}
