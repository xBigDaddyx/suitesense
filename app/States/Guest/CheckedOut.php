<?php

namespace App\States\Guest;

use App\ModelStates\GuestState;


class CheckedOut extends GuestState
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
