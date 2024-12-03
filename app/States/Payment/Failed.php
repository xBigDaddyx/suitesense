<?php

namespace App\States\Payment;

use App\ModelStates\PaymentState;

class Failed extends PaymentState
{
    public static $name = 'failed';

    public function description(): string
    {
        return 'The payment attempt failed.';
    }
    public function icon(): string
    {
        return 'tabler-alert-circle';
    }
    public function label(): string
    {
        return 'Failed';
    }
    public function color(): string
    {
        return 'danger';
    }
}
