<?php

namespace App\States\Invoice;

use App\ModelStates\InvoiceState;

class Draft extends InvoiceState
{
    public static $name = 'draft';
    public function description(): string
    {
        return 'The invoice is in draft and not yet finalized.';
    }
    public function icon(): string
    {
        return 'tabler-pencil';
    }
    public function label(): string
    {
        return 'Draft';
    }
    public function color(): string
    {
        return 'danger';
    }
}
