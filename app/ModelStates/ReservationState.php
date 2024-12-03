<?php

namespace App\ModelStates;

use App\States\Reservation\CheckedIn;
use App\States\Reservation\CheckedOut;
use App\States\Reservation\Confirmed;
use App\States\Reservation\Pending;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ReservationState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;
    abstract public function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->registerState([Pending::class, Confirmed::class, CheckedIn::class, CheckedOut::class])
            ->allowTransition(Pending::class, Confirmed::class)
            ->allowTransition(Confirmed::class, CheckedIn::class)
            ->allowTransition(CheckedIn::class, CheckedOut::class)
        ;
    }
}
