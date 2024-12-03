<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ReservationFacility extends Pivot
{
    protected $table = 'reservation_facility';
    public function reservation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Reservation::class, 'reservation_id', 'id');
    }
    public function facility(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(AdditionalFacility::class, 'additional_facility_id', 'id');
    }
}
