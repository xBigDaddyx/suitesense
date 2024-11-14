<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Models\Reservation;
use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomsStat extends BaseWidget
{
    protected function getStats(): array
    {
        $totalRooms = Room::where('is_available', true)->count();
        $reservedRooms  = Reservation::where('status', 'confirmed') // Assuming 'confirmed' is the booking status
            ->whereHas('room') // Make sure to get the rooms associated with reservations
            ->count();
        $occupancyRate = $totalRooms > 0 ? round(($reservedRooms / $totalRooms) * 100, 2) : 0;
        return [
            Stat::make('Available', $totalRooms)
                ->chart([9, 2, 3, 4, 5, 6, 10])
                ->description('Rooms available')
                ->descriptionIcon('tabler-check')
                ->color('success'),
            Stat::make('Available', Room::where('is_available', false)->count())
                ->chart([10, 7, 6, 8, 4, 3, 2])
                ->description('Rooms unavailable')
                ->descriptionIcon('tabler-x')
                ->color('warning'),
            Stat::make('Occupancy Rate', $occupancyRate . '%')
                // ->description()
                ->descriptionIcon('tabler-x')
                ->color('warning'),

        ];
    }
}
