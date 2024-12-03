<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;


use App\Filament\FrontOffice\Resources\RoomResource;
use App\Models\Room;
use App\Models\User;
use App\ModelStates\RoomState;
use App\States\Room\Available;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListRooms extends ListRecords
{
    protected static string $resource = RoomResource::class;
    public function getTabs(): array
    {

        return [
            'all' => Tab::make('All Rooms'),
            'available' => Tab::make('Available')
                ->modifyQueryUsing(fn(Builder $query) => $query->available())
                ->icon('tabler-home')
                ->badge(Room::query()->available()->count())
                ->badgeColor('primary'),
            'cleaning' => Tab::make('Cleaning')
                ->modifyQueryUsing(fn(Builder $query) => $query->cleaning())
                ->icon('tabler-vacuum-cleaner')
                ->badge(Room::query()->cleaning()->count())
                ->badgeColor('primary'),
            'maintenance' => Tab::make('Maintenance')
                ->modifyQueryUsing(fn(Builder $query) => $query->maintenance())
                ->icon('tabler-wrecking-ball')
                ->badge(Room::query()->maintenance()->count())
                ->badgeColor('primary'),
            'occupied' => Tab::make('Occupied')
                ->modifyQueryUsing(fn(Builder $query) => $query->occupied())
                ->icon('tabler-user')
                ->badge(Room::query()->occupied()->count())
                ->badgeColor('primary'),
            'reserved' => Tab::make('Reserved')
                ->modifyQueryUsing(fn(Builder $query) => $query->reserved())
                ->icon('tabler-calendar-event')
                ->badge(Room::query()->reserved()->count())
                ->badgeColor('primary'),


        ];
    }
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
