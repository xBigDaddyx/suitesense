<?php

namespace App\Filament\FrontOffice\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\OccupancyRate;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\RoomsStat;
use App\Filament\FrontOffice\Widgets\RoomAvailable;
use Filament\Pages\Page;

class AvailableRoomDashboard extends \Filament\Pages\Dashboard
{
    protected static ?string $navigationIcon = 'tabler-door';
    protected static string $routePath = 'available-rooms';
    protected static ?string $title = 'Rooms dashboard';
    public function getColumns(): int|string|array
    {
        return 12;
    }
    public function getWidgets(): array
    {
        return [
            RoomsStat::class,
            RoomAvailable::class,


        ];
    }
}
