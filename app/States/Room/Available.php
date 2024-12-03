<?php

namespace App\States\Room;

use App\ModelStates\RoomState;

class Available extends RoomState
{
    public static $name = 'available';
    public function description(): string
    {
        return 'The room is available for booking.';
    }
    public function icon(): string
    {
        return 'tabler-home';
    }
    public function label(): string
    {
        return 'Available';
    }
    public function color(): string
    {
        return 'success';
    }
}
