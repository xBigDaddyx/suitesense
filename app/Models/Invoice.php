<?php

namespace App\Models;

use App\ModelStates\InvoiceState;
use Carbon\Carbon;
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

    protected $fillable = ['reservation_id', 'invoice_number', 'total_amount', 'paid_amount', 'outstanding_amount', 'state'];

    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            $model->number_series = 'SS/INV/' . Carbon::now()->format('m') . '/' . Carbon::now()->format('Y');
            $model->number_invoice = (Invoice::where('number_series', $model->number_series)->max('number_invoice') ?? 0) + 1;
            $model->number = $model->number_series . '/' . $model->number_invoice;
        });
    }

    protected $casts = [
        'state' => InvoiceState::class,
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function updateAmounts()
    {
        $this->paid_amount = $this->reservation->payments->sum('paid_amount');
        $this->outstanding_amount = $this->total_amount - $this->paid_amount;
        $this->status = $this->paid_amount >= $this->total_amount ? 'paid' : ($this->paid_amount > 0 ? 'partially_paid' : 'unpaid');
        $this->save();
    }
}
