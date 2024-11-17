<?php

namespace App\Enums;

enum GuestStatus: string
{
    case PENDING = 'pending';
    case CHECKIN = 'checked-in';
    case CHECKOUT = 'checked-out';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CHECKIN => 'Checked In',
            self::CHECKOUT => 'Checked Out',
        };
    }
}
