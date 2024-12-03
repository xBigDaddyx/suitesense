<?php

namespace App\Filament\FrontOffice\Widgets;

use App\Enums\RoomStatus;
use App\Models\Room as RoomModel;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class Room extends Widget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = 'full';
    public $rooms;

    public function getRooms()
    {
        $status = $this->filters['state'];
        $type = $this->filters['room_type'];
        $name = $this->filters['name'];
        $description = $this->filters['description'];

        $this->rooms = RoomModel::query()->with('roomType')

            ->when($status, function ($query, $status) {
                $query->whereState('state', $status);
            })
            ->when($type, function ($query, $type) {
                $query->where('room_type_id', $type);
            })
            ->when($name, function ($query, $name) {
                $query->where('name', 'like', '%' . $name . '%');
            })
            ->when($description, function ($query, $description) {
                $query->whereHas('roomType', function ($queryType) use ($description) {
                    $queryType->where('description', 'like', '%' . $description . '%');
                });
            })
            ->orderBy('room_type_id')
            ->get();


        return $this->rooms;
    }
    public function getGroupedRooms()
    {
        $rooms = $this->getRooms();
        return $rooms->groupBy(function ($room) {
            return $room->roomType->name;  // Group by roomType's name
        });
    }

    protected static string $view = 'filament.front-office.widgets.rooms';
}
