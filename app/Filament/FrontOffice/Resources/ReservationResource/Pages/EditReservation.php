<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReservation extends EditRecord
{
    protected static string $resource = ReservationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label(trans('frontOffice.reservation.viewLabel'))
                ->authorize(fn(): bool => auth()->user()->can('view_reservation'))
                ->icon('tabler-eye'),
            Actions\DeleteAction::make()
                ->label(trans('frontOffice.reservation.deleteLabel'))
                ->authorize(fn(): bool => auth()->user()->can('delete_reservation'))
                ->icon('tabler-trash'),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
