<?php

namespace App\States\Room;

use App\ModelStates\RoomState;

class Cleaning extends RoomState
{
    public static $name = 'cleaning';
    public function description(): string
    {
        return 'The room is currently being cleaned.';
    }
    public function icon(): string
    {
        return 'tabler-vacuum-cleaner';
    }
    public function label(): string
    {
        return 'Cleaning';
    }
    public function color(): string
    {
        return 'info';
    }
}
