<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Order extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    use HasFactory;
    protected $fillable = [
        'external_id',
        'amount',
        'payer_email',
        'description',
        'invoice_duration',
        'callback_virtual_account_id',
        'should_send_email',
        'customer_id',
        'customer_notification_preference_id',
        'success_redirect_url',
        'failure_redirect_url',
        'payment_methods',
        'mid_label',
        'should_authenticate_credit_card',
        'currency',
        'reminder_time',
        'local',
        'reminder_time_unit',
        'items',
        'fees',
        'number',
        'number_series',
        'number_order',
    ];
    protected $casts = [
        'items' => 'array',
        'fees' => 'array',
    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            // Calculate the next number number in the series
            $model->number_series = 'SS-OR-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_order = (Order::where('number_series', $model->number_series)->max('number_order') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_order;
        });
    }
    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function customerNotificationPreference(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(NotificationPreference::class);
    }
}
