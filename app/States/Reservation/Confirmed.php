<?php

namespace App\States\Reservation;

use App\ModelStates\ReservationState;

class Confirmed extends ReservationState
{
    public static $name = 'confirmed';
    public function description(): string
    {
        return 'The reservation has been confirmed.';
    }
    public function icon(): string
    {
        return 'tabler-check';
    }
    public function label(): string
    {
        return 'Confirmed';
    }
    public function color(): string
    {
        return 'success';
    }
}
