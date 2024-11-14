<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case DEBITCARD = 'debit-card';
    case CREDITCARD = 'credit-card';

    public function label(): string
    {
        return match ($this) {
            self::CASH => 'Cash',
            self::DEBITCARD => 'Debit Card',
            self::CREDITCARD => 'Credit Card',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::CASH => 'Payment made using physical currency.',
            self::DEBITCARD => 'Payment directly linked to a bank account, with immediate deduction.',
            self::CREDITCARD => 'Payment using a line of credit, allowing deferred payment.',
        };
    }

    /**
     * Get the icon for the payment method.
     */
    public function icon(): string
    {
        return match ($this) {
            self::CASH => 'tabler-cash',
            self::DEBITCARD, self::CREDITCARD => 'tabler-credit-card',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::CASH => 'warning',
            self::DEBITCARD => 'info',
            self::CREDITCARD => 'danger',
        };
    }
}
