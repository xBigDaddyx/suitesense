<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\States\Reservation\Confirmed;
use App\States\Room\Reserved;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RoomsStat extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'md' => 6,
        'xl' => 12,
    ];
    protected function getStats(): array
    {
        $occupancyRate = null;
        // Jumlah Kamar yang Tersedia
        $totalRooms = Room::count(); // Hitung jumlah total kamar yang ada di sistem
        $availableRooms = Room::available()->count(); // Hitung jumlah total kamar yang ada di sistem
        // Jumlah Kamar yang Dipesan dengan status 'completed'
        $bookedRooms = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->where('reservations.state', Confirmed::class) // Hanya yang statusnya completed
            ->count('reservations.room_id'); // Hitung jumlah kamar yang dipesan

        // Menghitung Occupancy Rate
        if ($totalRooms > 0) {
            $occupancyRate = ($bookedRooms / $totalRooms) * 100; // Hitung Occupancy Rate
        }
        // Jumlah kamar dalam status "cleaning" (Pembersihan)
        $roomsCleaning = Room::cleaning()->count();

        // Jumlah kamar dalam status "maintenance" (Perawatan)
        $roomsMaintenance = Room::maintenance()->count();

        return [
            Stat::make('Rooms available', $availableRooms)
                ->color('success')
                ->description('Total Available Rooms'),
            Stat::make('Rooms cleaning', $roomsCleaning)
                ->color('warning')
                ->description('Total rooms under cleaning status'),
            Stat::make('Rooms maintenance', $roomsMaintenance)
                ->description('Total rooms under maintenance status')
                ->color('danger'),
            Stat::make('Occupancy Rate', $occupancyRate ? number_format($occupancyRate, 2) . "%" : 0),

        ];
    }
}
