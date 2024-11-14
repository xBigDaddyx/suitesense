<?php

namespace App\Filament\FrontOffice\Resources\RoomTypeResource\Pages;

use App\Filament\FrontOffice\Resources\RoomTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRoomTypes extends ManageRecords
{
    protected static string $resource = RoomTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('tabler-plus')
                ->label(trans('frontOffice.roomType.createLabel'))
                ->authorize(fn(RoomTypeResource $resource): bool => auth()->user()->can('create_room_type')),
        ];
    }
}
