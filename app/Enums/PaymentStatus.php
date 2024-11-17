<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Canceled',
            self::REFUNDED => 'Refunded',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'Payment is currently pending.',
            self::COMPLETED => 'Payment has been successfully completed.',
            self::FAILED => 'Payment failed. Please try again.',
            self::REFUNDED => 'Payment has been refunded.',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'tabler-circle-dot',
            self::COMPLETED => 'tabler-circle-check',
            self::FAILED => 'tabler-circle-x',
            self::REFUNDED => 'tabler-backspace',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'info',
            self::FAILED => 'danger',
            self::REFUNDED => 'primary',
        };
    }
}
