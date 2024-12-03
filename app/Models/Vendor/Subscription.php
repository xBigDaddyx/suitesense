<?php

namespace App\Models\Vendor;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Subscription extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = ['customer_id', 'order_id', 'plan_id', 'starts_at', 'ends_at', 'is_active', 'number', 'number_series', 'number_subscription'];
    protected $casts = [
        'is_active' => 'boolean',

    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            if ($model->ends_at === null) {
                $model->ends_at = Carbon::parse($model->starts_at)->addDays(Plan::find($model->plan_id)->duration_in_days ?? Carbon::now());
            }
            // Calculate the next number number in the series
            $model->number_series = 'SS-SV-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_subscription = (Subscription::where('number_series', $model->number_series)->max('number_subscription') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_subscription;
        });
    }
    public function hotel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    public function plan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    public function licenses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(License::class);
    }
    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isActive()
    {
        return $this->is_active && $this->ends_at > now();
    }

    /** @return BelongsTo<\App\Models\Vendor\Hotel, self> */
    public function latestHotel(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Vendor\Hotel::class);
    }

}
