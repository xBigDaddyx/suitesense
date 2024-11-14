<?php

namespace App\View\Components;

use App\Models\Room;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RoomCard extends Component
{
    public $room;
    /**
     * Create a new component instance.
     */
    public function __construct(Room $room)
    {
        $this->room = $room;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.room-card');
    }
}
