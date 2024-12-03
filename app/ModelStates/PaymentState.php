<?php

namespace App\ModelStates;

use App\States\Payment\Failed;
use App\States\Payment\Paid;
use App\States\Payment\Pending;
use App\States\Payment\Refunded;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class PaymentState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;
    abstract public function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->registerState([Pending::class, Paid::class, Failed::class, Refunded::class])
            ->allowTransition(Pending::class, Paid::class)
            ->allowTransition(Pending::class, Failed::class)
            ->allowTransition(Paid::class, Refunded::class)
        ;
    }
}
