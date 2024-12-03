<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Widgets;

use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource\Pages\ListReservations;
use App\Models\Reservation;
use App\Models\Room;
use App\States\Reservation\CheckedOut;
use App\States\Room\Reserved;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ItemNotFoundException;

class ReservationStats extends BaseWidget
{
    use InteractsWithPageTable;
    // protected static string $view = 'filament.front-office.widgets.reservation-stats';

    protected function getTablePage(): string
    {
        return ListReservations::class;
    }

    protected function getStats(): array
    {

        $mostBookedRoomType = $this->getPageTableQuery()->select(
            'room_types.name as room_type_name',
            DB::raw('COUNT(reservations.id) as total_reservations')
        )
            ->join('rooms', 'reservations.room_id', '=', 'rooms.id') // Gabung dengan tabel rooms
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id') // Gabung dengan tabel room_types
            ->where('reservations.state', Reserved::class) // Hanya hitung pemesanan dengan status completed
            ->whereNull('reservations.deleted_at') // Memastikan status deleted_at null
            ->groupBy('rooms.room_type_id', 'room_types.name', 'reservations.id') // Kelompokkan berdasarkan tipe kamar dan nama
            ->orderByDesc('total_reservations') // Urutkan dari yang paling banyak dipesan
            ->first(); // Ambil tipe kamar dengan jumlah terbanyak

        $totalRevenue = $this->getPageTableQuery()->join('rooms', 'reservations.room_id', '=', 'rooms.id') // Gabung dengan tabel rooms
            ->where('reservations.state', CheckedOut::class) // Filter hanya status completed
            ->sum('rooms.price'); // Hitung total dari kolom harga (price)


        // Jumlah Kamar yang Tersedia
        $totalRooms = Room::count(); // Hitung jumlah total kamar yang ada di sistem

        // Jumlah Kamar yang Dipesan dengan status 'completed'
        $bookedRooms = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->where('reservations.state', CheckedOut::class) // Hanya yang statusnya completed
            ->count('reservations.room_id'); // Hitung jumlah kamar yang dipesan
        $occupancyRate = null;
        // Menghitung Occupancy Rate
        if ($totalRooms > 0) {
            $occupancyRate = ($bookedRooms / $totalRooms) * 100; // Hitung Occupancy Rate
        }

        return [
            Stat::make('Total Reservations', $this->getPageTableQuery()->count()),
            Stat::make('Most Completed Reservation',  $mostBookedRoomType->room_type_name ?? '-'),
            Stat::make('Total Revenue', trans('frontOffice.room.pricePrefix') . $totalRevenue),
            Stat::make('Occupancy Rate', $occupancyRate ? number_format($occupancyRate, 2) . "%" : 0),
        ];
    }
}
