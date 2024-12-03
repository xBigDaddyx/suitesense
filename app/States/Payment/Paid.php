<?php

namespace App\States\Payment;

use App\ModelStates\PaymentState;

class Paid extends PaymentState
{
    public static $name = 'paid';
    public function description(): string
    {
        return 'The payment has been successfully completed.';
    }
    public function icon(): string
    {
        return 'tabler-check';
    }
    public function label(): string
    {
        return 'Paid';
    }
    public function color(): string
    {
        return 'success';
    }
}
