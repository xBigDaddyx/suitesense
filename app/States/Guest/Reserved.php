<?php

namespace App\States\Guest;

use App\ModelStates\GuestState;


class Reserved extends GuestState
{
    public static $name = 'reserved';
    public function description(): string
    {
        return 'The guest is reserved but not yet occupied.';
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
        return 'warning';
    }
}
