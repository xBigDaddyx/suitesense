<?php

namespace App\Models;

use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Ramsey\Uuid\Type\Decimal;

class Reservation extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            if ($model->status === null) {
                $model->status = ReservationStatus::PENDING->value;
            }

            // Calculate the next number number in the series
            $model->number_series = 'SS-RV-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_reservation = (Reservation::where('number_series', $model->number_series)->max('number_reservation') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_reservation;
        });
    }
    protected $fillable = [
        'estimate_arrival',
        'room_id',
        'guest_id',
        'check_in',
        'check_out',
        'status',
        'number',
        'number_series',
        'number_reservation',
    ];
    public function getTotalNightsAttribute()
    {
        return number_format(Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out)), 0);
    }
    public function getTotalPriceAttribute()
    {
        return $this->total_nights * $this->room->price;
    }
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function guest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id');
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
