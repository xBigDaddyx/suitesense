<?php

namespace App\States\Invoice;

use App\ModelStates\InvoiceState;


class Issued extends InvoiceState
{
    public static $name = 'issued';
    public function description(): string
    {
        return 'The invoice has been issued to the customer.';
    }
    public function icon(): string
    {
        return 'tabler-file';
    }
    public function label(): string
    {
        return 'Issued';
    }
    public function color(): string
    {
        return 'danger';
    }
}
