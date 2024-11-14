<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewReservation extends ViewRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label(trans('frontOffice.reservation.editLabel'))
                ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                ->icon('tabler-pencil'),
            Actions\DeleteAction::make()
                ->label(trans('frontOffice.reservation.deleteLabel'))
                ->authorize(fn(): bool => auth()->user()->can('delete_reservation'))
                ->icon('tabler-trash'),
        ];
    }
}
