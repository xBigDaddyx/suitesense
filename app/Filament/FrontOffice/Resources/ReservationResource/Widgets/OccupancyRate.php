<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Widgets;

use App\Models\Room;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OccupancyRate extends BaseWidget
{
    protected function getStats(): array
    {
        $occupancy_rate = (Room::where('is_available', true)->count() / Room::count()) * 100;
        return [

            Stat::make('Occupancy Rate', round($occupancy_rate, 2) . '%')
                // ->description()
                ->descriptionIcon('tabler-x')
                ->color('warning'),

        ];
    }
}
