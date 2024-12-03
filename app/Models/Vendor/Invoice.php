<?php

namespace App\Models\Vendor;

use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use App\ModelStates\InvoiceState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\ModelStates\HasStates;
use Wildside\Userstamps\Userstamps;

class Invoice extends Model
{
    use HasStates;
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = [
        'state',
        'payment_id',
        'paid_by',
        'paid_at',
        'due_date',
        'invoice_date',
        'customer_details',
        'payment_type',
        'item_details',
        'amount',
        'notes',
        'virtual_accounts',
        'status',
        'number',
        'number_series',
        'number_invoice',
        'order_id',
        'reservation_id',
        'invoice_type'
    ];
    protected $casts = [
        'customer_details' => 'array',
        'item_details' => 'array',
        'state' => InvoiceState::class,
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS/INV/' . Carbon::now()->format('m') . '/' . Carbon::now()->format('Y');
            $model->number_invoice = (Invoice::where('number_series', $model->number_series)->max('number_invoice') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '/' . $model->number_invoice;
        });
    }
    public function paidBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by', 'id');
    }
    public function reservation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
    public function payment(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Payment::class);
    }
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'draft');
    }
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
    public function scopeSent(Builder $query): Builder
    {
        return $query->where('status', 'sent');
    }
    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }
    public function scopeExpired(Builder $query): Builder
    {
        return $query->where('status', 'expired');
    }
}
