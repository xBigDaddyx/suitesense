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

        if ($record->is_completed_payment) {
            $record->status = ReservationStatus::COMPLETED->value;
            $record->checked_out_by = $event->user->id;
            $record->guest_status = GuestStatus::CHECKOUT->value;
            if ($data['guest_check_out_at'] === null) {
                $record->guest_check_out_at = now();
            } else {
                $record->guest_check_out_at = $data['guest_check_out_at'];
            }
            $record->room->status = RoomStatus::AVAILABLE->value;
            switch ($record->save()) {
                case true:
                    Notifications\Notification::make()
                        ->title('Check-Out Successful! ✅')
                        ->success()
                        ->color('primary')
                        ->body('Guest check-out is complete. Thank you for your service!')
                        ->broadcast($event->user);
                    break;
                case false:
                    Notifications\Notification::make()
                        ->title('Check-Out Failed! ❌')
                        ->danger()
                        ->color('danger')
                        ->body('An error occurred during the check-out process. Please try again or contact support.')
                        ->broadcast($event->user);
                    break;
            }
        } else {
            Notifications\Notification::make()
                ->title('Reservation payment is required')
                ->seconds(20)
                ->danger()
                ->color('danger')
                ->body('This pending payment on the reservation needs to be resolved.')
                ->actions([
                    Notifications\Actions\Action::make('view')
                        ->label('View Payment')
                        ->url(function () use ($record) {
                            return route('filament.frontOffice.resources.reservations.managePayments', ['tenant' => Filament::getTenant(), 'record' => $record]);
                        }, true)
                        ->button(),
                ])->broadcast($event->user);
        }
    }
}
