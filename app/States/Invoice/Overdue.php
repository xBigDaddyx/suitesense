<?php

namespace App\States\Invoice;

use App\ModelStates\InvoiceState;


class Overdue extends InvoiceState
{
    public static $name = 'overdue';
    public function description(): string
    {
        return 'The payment for this invoice is overdue.';
    }
    public function icon(): string
    {
        return 'tabler-alert-circle';
    }
    public function label(): string
    {
        return 'Overdue';
    }
    public function color(): string
    {
        return 'danger';
    }
}
