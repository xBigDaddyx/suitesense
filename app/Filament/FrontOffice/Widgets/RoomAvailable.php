<?php

namespace App\Filament\FrontOffice\Widgets;

use App\Models\Room;
use Filament\Widgets\Widget;

class RoomAvailable extends Widget
{
    protected int | string | array $columnSpan = 'full';
    public $availableRooms;

    public function getRooms()
    {
        return $this->availableRooms = Room::available()  // Only available room
            ->orderBy('room_type_id')  // Sort by room_type_id
            ->get();
    }
    public function getGroupedRooms()
    {
        $availableRooms = $this->getRooms();
        return $availableRooms->groupBy(function ($room) {
            return $room->roomType->name;  // Group by roomType's name
        });
    }

    protected static string $view = 'filament.front-office.widgets.room-available';
}
