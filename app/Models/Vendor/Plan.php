<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Plan extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = [
        'name',
        'description',
        'price',
        'currency',
        'features',
        'is_active',
        'duration_in_days',
        'number',
        'number_series',
        'number_plan'
    ];
    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',

    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-PL-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_plan = (Plan::where('number_series', $model->number_series)->max('number_plan') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_plan;
        });
    }
    public function subscriptions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}
