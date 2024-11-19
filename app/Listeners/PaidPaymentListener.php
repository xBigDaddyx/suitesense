<?php

namespace App\Listeners;

use App\Enums\PaymentStatus;
use App\Events\PaidPaymentEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Filament\Notifications;

class PaidPaymentListener
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
    public function handle(PaidPaymentEvent $event): void
    {
        $record = $event->record;
        $record->status = PaymentStatus::COMPLETED->value;
        $exec = $record->save();
        switch ($exec) {
            case true:
                Notifications\Notification::make()
                    ->title('Payment Successful! ✅')
                    ->success()
                    ->color('primary')
                    ->body('The payment has been processed successfully. Thank you!')
                    ->broadcast($event->user);
                break;
            case false:
                Notifications\Notification::make()
                    ->title('Payment Failed! ❌')
                    ->danger()
                    ->color('danger')
                    ->body('An error occurred during the payment process. Please try again or contact support.')
                    ->broadcast($event->user);
                break;
        }
    }
}
