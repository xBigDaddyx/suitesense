<?php

namespace App\States\Guest;

use App\ModelStates\GuestState;


class CheckedIn extends GuestState
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
        return 'success';
    }
}
