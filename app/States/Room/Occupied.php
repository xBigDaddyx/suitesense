<?php

namespace App\States\Room;

use App\ModelStates\RoomState;

class Occupied extends RoomState
{
    public static $name = 'occupied';
    public function description(): string
    {
        return 'The room is currently occupied.';
    }
    public function icon(): string
    {
        return 'tabler-user';
    }
    public function label(): string
    {
        return 'Occupied';
    }
    public function color(): string
    {
        return 'danger';
    }
}
