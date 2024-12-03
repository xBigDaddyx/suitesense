<?php

namespace App\Filament\FrontOffice\Widgets;

use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room as RoomModel;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class Reservations extends Widget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = 'full';
    public $reservations;

    public function getReservations()
    {
        $status = $this->filters['state'];
        $type = $this->filters['room_type'];
        $name = $this->filters['name'];
        $description = $this->filters['description'];

        $this->reservations = Reservation::query()->with('room')

            ->when($status, function ($query, $status) {
                $query->whereState('state', $status);
            })
            ->when($type, function ($query, $type) {
                $query->where('room.room_type_id', $type);
            })
            ->when($name, function ($query, $name) {
                $query->where('room.name', 'like', '%' . $name . '%');
            })
            ->when($description, function ($query, $description) {
                $query->whereHas('room.roomType', function ($queryType) use ($description) {
                    $queryType->where('description', 'like', '%' . $description . '%');
                });
            })
            ->orderBy('state')
            ->get();


        return $this->reservations;
    }
    public function getGroupedReservations()
    {
        $reservations = $this->getReservations();
        return $reservations->groupBy(function ($reservation) {
            return $reservation->room->roomType->name;  // Group by roomType's name
        });
    }

    protected static string $view = 'filament.front-office.widgets.reservations';
}
