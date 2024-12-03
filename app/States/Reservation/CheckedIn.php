<?php

namespace App\States\Reservation;

use App\ModelStates\ReservationState;


class CheckedIn extends ReservationState
{
    public static $name = 'checked-in';
    public function description(): string
    {
        return 'The guest has checked into the room.';
    }
    public function icon(): string
    {
        return 'tabler-login';
    }
    public function label(): string
    {
        return 'Checked In';
    }
    public function color(): string
    {
        return 'info';
    }
}
