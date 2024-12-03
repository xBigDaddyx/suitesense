<?php

namespace App\States\Room;

use App\ModelStates\RoomState;

class Reserved extends RoomState
{
    public static $name = 'reserved';
    public function description(): string
    {
        return 'The room is reserved but not yet occupied.';
    }
    public function icon(): string
    {
        return 'tabler-calendar-event';
    }
    public function label(): string
    {
        return 'Reserved';
    }
    public function color(): string
    {
        return 'primary';
    }
}
