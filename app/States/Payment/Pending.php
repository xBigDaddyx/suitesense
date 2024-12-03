<?php

namespace App\States\Payment;

use App\ModelStates\PaymentState;

class Pending extends PaymentState
{
    public static $name = 'pending';
    public function description(): string
    {
        return 'The payment is awaiting action.';
    }
    public function icon(): string
    {
        return 'tabler-clock';
    }
    public function label(): string
    {
        return 'Pending';
    }
    public function color(): string
    {
        return 'warning';
    }
}
