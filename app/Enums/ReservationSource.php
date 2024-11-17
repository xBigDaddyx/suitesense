<?php

namespace App\Enums;

enum ReservationSource: string
{
    case PHONE = 'phone';
    case ONSITE = 'on-site';
    case EMAIL = 'email';
    case WEBSITE = 'website';

    public function label(): string
    {
        return match ($this) {
            self::PHONE => 'By Phone',
            self::ONSITE => 'On Site',
            self::EMAIL => 'By Email',
            self::WEBSITE => 'On Website',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::PHONE => 'Reservation made via phone',
            self::ONSITE => 'Reservation made onsite',
            self::EMAIL => 'Reservation made via email',
            self::WEBSITE => 'Reservation made through the website',
        };
    }

    /**
     * Get the icon for the payment method.
     */
    public function icon(): string
    {
        return match ($this) {
            self::PHONE => 'tabler-phone',
            self::ONSITE => 'tabler-building',
            self::EMAIL => 'tabler-mail',
            self::WEBSITE => 'tabler-browser',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::PHONE => 'warning',
            self::ONSITE => 'success',
            self::EMAIL => 'info',
            self::WEBSITE => 'primary',
        };
    }
}
