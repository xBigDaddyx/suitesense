<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Invoice extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = [
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
        'order_id'
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-INV-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_invoice = (Invoice::where('number_series', $model->number_series)->max('number_invoice') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_invoice;
        });
    }
}
