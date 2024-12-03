<?php

namespace App\States\Reservation;

use App\ModelStates\ReservationState;


class Cancelled extends ReservationState
{
    public static $name = 'cancelled';
    public function description(): string
    {
        return 'The reservation has been cancelled.';
    }
    public function icon(): string
    {
        return 'tabler-x-circle';
    }
    public function label(): string
    {
        return 'Cancelled';
    }
    public function color(): string
    {
        return 'danger';
    }
}
