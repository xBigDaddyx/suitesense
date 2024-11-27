<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Order extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = [
        'transaction_details',
        'customer_details',
        'item_details',
        'payment_type',
        'status',
        'number',
        'number_series',
        'number_order'
    ];
    protected $casts = [
        'transaction_details' => 'array',
        'customer_details' => 'array',
        'item_details' => 'array',
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-ORD-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_order = (Order::where('number_series', $model->number_series)->max('number_order') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_order;
        });
    }
}
