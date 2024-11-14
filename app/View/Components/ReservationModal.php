<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ReservationModal extends Component
{
    public $reservation;

    public function __construct($reservation)
    {
        $this->reservation = $reservation;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.reservation-modal');
    }
}
