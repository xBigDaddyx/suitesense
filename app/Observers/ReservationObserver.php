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
use App\Models\User;
use App\Models\Invoice;
use App\States\Payment\Pending;
use App\States\Reservation\Confirmed;
use App\States\Room\Reserved;
use Carbon\Carbon;
use Filament\Notifications;
use Filament\Notifications\Actions\Action;
use Filament\Facades\Filament;

class ReservationObserver
{

    /**
     * Handle the Reservation "created" event.
     */
    public function created(Reservation $reservation): void
    {
        $user = User::find($reservation->created_by);
        $reservation = Reservation::find($reservation->id);
        $reservation->room->state->transitionTo(Reserved::class);
        $reservation->room->save();
        $payment = $reservation->payments()->create([
            'total_amount' => $reservation->price + $reservation->additionalFacilities->sum('total_price'),
            'paid_amount' => 0,
            'type' => PaymentType::INITIAL->value,
        ]);
        $invoice = $reservation->invoices()->create([
            'total_amount' => $reservation->price + $reservation->additionalFacilities->sum('total_price'),
            'paid_amount' => 0,
            'outstanding_amount' => $reservation->price + $reservation->additionalFacilities->sum('total_price'),
        ]);
        if ($payment) {
            //Broadcast Notification
            Notifications\Notification::make()
                ->title('Reservation created successfully')
                ->success()
                ->color('primary')
                ->body('Please check the initial payment for this reservation ' . $reservation->number . 'on behalf of ' . $reservation->guest->name . '.')
                ->actions([
                    Action::make('view')
                        ->icon('tabler-file-invoice')
                        ->label('View Invoice')
                        ->button()
                        ->url(function () use ($reservation) {
                            return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                        }),
                    Action::make('view')
                        ->icon('tabler-cash')
                        ->label('View Payments')
                        ->button()
                        ->url(function () use ($reservation) {
                            return route('filament.frontOffice.resources.reservations.managePayments', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                        }),
                ])
                ->broadcast($user);
            //Database Notification
            Notifications\Notification::make()
                ->title('Reservation created successfully')
                ->success()
                ->color('primary')
                ->body('Please check the initial payment for this reservation ' . $reservation->number . 'on behalf of ' . $reservation->guest->name . '.')
                ->actions([
                    Action::make('view')
                        ->icon('tabler-file-invoice')
                        ->label('View Invoice')
                        ->button()
                        ->url(function () use ($reservation) {
                            return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                        }),
                    Action::make('view')
                        ->icon('tabler-cash')
                        ->label('View Payments')
                        ->button()
                        ->url(function () use ($reservation) {
                            return route('filament.frontOffice.resources.reservations.managePayments', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                        }),
                ])
                ->sendToDatabase($user);
        }

        // if ($reservation->room->status === RoomStatus::AVAILABLE->value) {
        //     $reservation->room->is_available = false;
        //     $reservation->room->status = RoomStatus::BOOKED->value;
        //     $reservation->room->save();
        // }
    }

    /**
     * Handle the Reservation "updated" event.
     */
    public function updated(Reservation $reservation): void
    {
        $user = User::findOrFail($reservation->created_by);
        switch ($reservation->state) {
            case Confirmed::class:
                $invoice = Invoice::create([
                    'reservation_id' => $reservation->id,
                    'total_amount' => $reservation->total_price,
                    'paid_amount' => 0,
                    'outstanding_amount' => $reservation->total_price,
                ]);
                if ($invoice) {
                    //Broadcast Notification
                    Notifications\Notification::make()
                        ->title('Invoice generated successfully')
                        ->success()
                        ->color('primary')
                        ->body('Please check the invoice for this reservation ' . $reservation->number . ' on behalf of ' . $reservation->guest->name . '.')
                        ->actions([
                            Action::make('view')
                                ->icon('tabler-file-invoice')
                                ->label('View Invoice')
                                ->button()
                                ->url(function () use ($reservation) {
                                    return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                                }),
                            Action::make('view')
                                ->icon('tabler-cash')
                                ->label('View Payments')
                                ->button()
                                ->url(function () use ($reservation) {
                                    return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                                }),
                        ])
                        ->broadcast($user);
                    //Database Notification
                    Notifications\Notification::make()
                        ->title('Invoice generated successfully')
                        ->success()
                        ->color('primary')
                        ->body('Please check the invoice for this reservation ' . $reservation->number . ' on behalf of ' . $reservation->guest->name . '.')
                        ->actions([
                            Action::make('view')
                                ->icon('tabler-file-invoice')
                                ->label('View Invoice')
                                ->button()
                                ->url(function () use ($reservation) {
                                    return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                                }),
                            Action::make('view')
                                ->icon('tabler-cash')
                                ->label('View Payments')
                                ->button()
                                ->url(function () use ($reservation) {
                                    return route('filament.frontOffice.resources.reservations.manageInvoices', ['tenant' => Filament::getTenant(), 'record' => $reservation]);
                                }),
                        ])
                        ->sendToDatabase($user);
                }
                break;
        }
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
