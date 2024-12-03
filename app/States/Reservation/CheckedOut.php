<?php

namespace App\States\Reservation;

use App\ModelStates\ReservationState;


class CheckedOut extends ReservationState
{
    public static $name = 'checked-out';
    public function description(): string
    {
        return 'The guest has checked out of the room.';
    }
    public function icon(): string
    {
        return 'tabler-logout';
    }
    public function label(): string
    {
        return 'Checked Out';
    }
    public function color(): string
    {
        return 'primary';
    }
}
