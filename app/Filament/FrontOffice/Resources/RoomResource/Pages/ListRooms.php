<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;


use App\Filament\FrontOffice\Resources\RoomResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            RoomResource\Widgets\RoomsStat::class,
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('tabler-plus')
                ->authorize(fn(User $user): bool => auth()->user()->can('create_room')),
        ];
    }
}
