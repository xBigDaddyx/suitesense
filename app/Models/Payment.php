<?php

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Models\Vendor\Invoice;
use App\ModelStates\PaymentState;
use App\States\Payment\Paid;
use App\States\Payment\Pending;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;
use Wildside\Userstamps\Userstamps;

class Payment extends Model
{
    use HasStates;
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    use HasFactory;
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-PY-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_payment = (Payment::where('number_series', $model->number_series)->max('number_payment') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_payment;
            $model->hotel_id = auth()->user()->hotel_id;
        });
    }

    protected $fillable = [
        'reservation_id',
        'total_amount',
        'paid_amount',
        'state',
        'paid_by',
        'paid_at',
        'number',
        'number_series',
        'number_payment',
        'type'
    ];
    protected $casts = [
        'state' => PaymentState::class,
    ];
    public function getOutstandingAmountAttribute(): float
    {
        return $this->total_amount - $this->paid_amount;
    }
    public function reservation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    public function invoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
