<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class AdditionalFacility extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;

    protected $fillable = [
        'unit',
        'name',
        'description',
        'price',
    ];
    public function reservations(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Reservation::class)
            ->using(ReservationFacility::class)
            ->withPivot('quantity')
            ->withTimestamps();
    }
}
