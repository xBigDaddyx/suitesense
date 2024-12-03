<?php

namespace App\States\Invoice;

use App\ModelStates\InvoiceState;


class Paid extends InvoiceState
{
    public static $name = 'paid';
    public function description(): string
    {
        return 'The invoice has been paid in full.';
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
