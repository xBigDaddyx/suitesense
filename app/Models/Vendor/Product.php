<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Product extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'number',
        'number_series',
        'number_product',
    ];

    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-PR-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_product = (Product::where('number_series', $model->number_series)->max('number_product') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_product;
        });
    }
}
