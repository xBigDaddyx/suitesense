<?php

namespace App\ModelStates;

use App\States\Guest\CheckedIn;
use App\States\Guest\CheckedOut;
use App\States\Guest\Reserved;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class GuestState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;
    abstract public function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Reserved::class)
            ->registerState([Reserved::class, CheckedIn::class, CheckedOut::class])
            ->allowTransition(Reserved::class, CheckedIn::class)
            ->allowTransition(CheckedIn::class, CheckedOut::class)
        ;
    }
}
