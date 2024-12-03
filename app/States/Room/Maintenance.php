<?php

namespace App\States\Room;

use App\ModelStates\RoomState;

class Maintenance extends RoomState
{
    public static $name = 'maintenance';
    public function description(): string
    {
        return 'The room is under maintenance.';
    }
    public function icon(): string
    {
        return 'tabler-wrecking-ball';
    }
    public function label(): string
    {
        return 'Maintenance';
    }
    public function color(): string
    {
        return 'warning';
    }
}
