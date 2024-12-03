<?php

namespace App\States\Payment;

use App\ModelStates\PaymentState;

class Refunded extends PaymentState
{
    public static $name = 'refunded';
    public function description(): string
    {
        return 'The payment is being refunded.';
    }
    public function icon(): string
    {
        return 'tabler-refresh';
    }
    public function label(): string
    {
        return 'Refunded';
    }
    public function color(): string
    {
        return 'info';
    }
}
