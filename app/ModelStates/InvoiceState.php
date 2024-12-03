<?php

namespace App\ModelStates;

use App\States\Invoice\Draft;
use App\States\Invoice\Issued;
use App\States\Invoice\Overdue;
use App\States\Invoice\Paid;
use App\States\Payment\Refunded;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class InvoiceState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;
    abstract public function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Draft::class)
            ->registerState([Draft::class, Issued::class, Paid::class, Overdue::class])
            ->allowTransition(Draft::class, Issued::class)
            ->allowTransition(Issued::class, Paid::class)
            ->allowTransition(Issued::class, Overdue::class)
        ;
    }
}
