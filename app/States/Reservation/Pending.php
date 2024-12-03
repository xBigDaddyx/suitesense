<?php

namespace App\States\Reservation;

use App\ModelStates\ReservationState;

class Pending extends ReservationState
{
    public static $name = 'pending';
    public function description(): string
    {
        return 'The reservation is awaiting confirmation.';
    }
    public function icon(): string
    {
        return 'tabler-clock';
    }
    public function label(): string
    {
        return 'Pending';
    }
    public function color(): string
    {
        return 'warning';
    }
}
