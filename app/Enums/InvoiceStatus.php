<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PAID = 'paid';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Sent',
            self::PAID => 'Paid',
            self::EXPIRED => 'Expired',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::DRAFT => 'Invoice is currently draft.',
            self::SENT => 'Invoice has been successfully sent.',
            self::PAID => 'Invoice paid. Please try again.',
            self::EXPIRED => 'Invoice has been expired.',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'tabler-circle-dot',
            self::SENT => 'tabler-circle-check',
            self::PAID => 'tabler-circle-x',
            self::EXPIRED => 'tabler-backspace',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::DRAFT => 'warning',
            self::SENT => 'info',
            self::PAID => 'danger',
            self::EXPIRED => 'primary',
        };
    }
}
