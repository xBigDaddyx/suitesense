<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Customer extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    use HasFactory;
    protected $fillable = [
        'phone_number',
        'given_name',
        'surname',
        'email',
        'mobile_number',
        'number',
        'number_series',
        'number_customer',
        'address',
    ];
    protected $casts = [
        'address' => 'array',
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-CU-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_customer = (Customer::where('number_series', $model->number_series)->max('number_customer') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_customer;
        });
    }
}
