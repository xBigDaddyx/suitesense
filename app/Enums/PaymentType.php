<?php

namespace App\Enums;

enum PaymentType: string
{
    case INITIAL = 'initial';
    case EXTEND = 'extend';
    case BALANCE = 'balance';

    public function label(): string
    {
        return match ($this) {
            self::INITIAL => 'Pembayaran Awal',
            self::EXTEND => 'Pembayaran Perpanjangan',
            self::BALANCE => 'Pembayaran Sisa Tagihan',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::INITIAL => 'Pembayaran awal untuk reservasi.',
            self::EXTEND => 'Pembayaran untuk perpanjangan reservasi.',
            self::BALANCE => 'Pembayaran untuk melunasi sisa tagihan yang belum dibayarkan.',
        };
    }
    /**
     * Get the icon for the payment method.
     */
    public function icon(): string
    {
        return match ($this) {
            self::INITIAL => 'tabler-calendar-stats',
            self::EXTEND => 'tabler-calendar-plus',
            self::BALANCE => 'tabler-file-invoice',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::INITIAL => 'primary',
            self::EXTEND => 'success',
            self::BALANCE => 'warning',
        };
    }
}
