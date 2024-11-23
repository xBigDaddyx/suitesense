<?php

namespace App\Models\Vendor;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Str;

class License extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected $fillable = ['key', 'type', 'is_active', 'expires_at', 'subscription_id', 'customer_id', 'number', 'number_series', 'number_license'];
    protected $casts = [
        'is_active' => 'boolean',

    ];
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            if ($model->key === null) {
                $model->key = $model->generateLicenseKey();
            }
            // Calculate the next number number in the series
            $model->number_series = 'SS-LI-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_license = (License::where('number_series', $model->number_series)->max('number_license') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_license;
        });
    }
    /**
     * Generate a unique license key with a checksum.
     */
    public static function generateLicenseKey()
    {
        // Generate random segments for the license key
        $key = strtoupper(Str::random(8)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(12));

        // Add checksum for validation
        $checksum = self::generateChecksum($key);

        // Return key with checksum at the end
        return $key . '-' . $checksum;
    }

    /**
     * Generate a checksum for the license key using the first 4 characters of a SHA1 hash.
     */
    public static function generateChecksum($key)
    {
        // Generate a checksum by hashing the key and taking the first 4 characters of the hash
        return strtoupper(substr(sha1($key), 0, 4)); // 4 characters checksum
    }

    public function customer(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    public function subscription(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
    public function isValid()
    {
        return $this->is_active && $this->subscription && $this->subscription->isActive();
    }
}
