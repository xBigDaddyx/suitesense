<?php

namespace App\ModelStates;

use App\States\Room\Available;
use App\States\Room\Cleaning;
use App\States\Room\Maintenance;
use App\States\Room\Occupied;
use App\States\Room\Reserved;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class RoomState extends State
{
    abstract public function color(): string;
    abstract public function label(): string;
    abstract public function icon(): string;
    abstract public function description(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Available::class)
            ->registerState([Available::class, Maintenance::class, Reserved::class, Occupied::class, Cleaning::class])
            ->allowTransition(Available::class, Reserved::class)
            ->allowTransition(Reserved::class, Occupied::class)
            ->allowTransition(Occupied::class, Available::class)
            ->allowTransition(Reserved::class, Available::class)
            ->allowTransition(Occupied::class, Cleaning::class)
            ->allowTransition(Cleaning::class, Available::class)
            ->allowTransition(Cleaning::class, Maintenance::class)
            ->allowTransition(Maintenance::class, Available::class)
            ->allowTransition(Available::class, Maintenance::class)

        ;
    }
}
