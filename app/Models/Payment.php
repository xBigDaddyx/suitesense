<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Payment extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-PY-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_payment = (Payment::where('number_series', $model->number_series)->max('number_payment') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_payment;
        });
    }
    protected $fillable = [
        'reservation_id',
        'method',
        'amount',
        'status',
        'number',
        'number_series',
        'number_payment',
    ];
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
    public function reservation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
