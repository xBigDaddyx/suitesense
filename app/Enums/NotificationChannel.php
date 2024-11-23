<?php

namespace App\Enums;

enum NotificationChannel: string
{
    case EMAIL = 'email';
    case SMS = 'sms';
    case WHATSAPP = 'whatsapp';
    case VIBER = 'viber';
    case XENDIT_ENUM_DEFAULT_FALLBACK = 'UNKNOWN_ENUM_VALUE';


    public function label(): string
    {
        return match ($this) {
            self::EMAIL => 'Email',
            self::SMS => 'Sms',
            self::WHATSAPP => 'Whatsapp',
            self::VIBER => 'Viber',
            self::XENDIT_ENUM_DEFAULT_FALLBACK => 'UNKNOWN ENUM VALUE',
        };
    }
}
