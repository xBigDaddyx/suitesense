<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Cart extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = [
        'item_details',
        'total_details',
        'duration',
        'start_date',
        'end_date',
        'number',
        'number_series',
        'number_cart',
    ];
    protected $casts = [
        'item_details' => 'array',
        'total_details' => 'array',
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-CT-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_cart = (Cart::where('number_series', $model->number_series)->max('number_cart') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_cart;
        });
    }
}
