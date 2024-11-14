<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model implements HasMedia
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    use InteractsWithMedia;

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            if ($model->hotel_id == null) {
                $model->hotel_id = auth()->user()->hotel_id;
            }
        });
    }
    protected $fillable = [
        'hotel_id',
        'name',
        'room_type_id',
        'price',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];
    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Fit::Contain, 600, 600)
            ->nonQueued();
    }
    public function scopeAvailableBetween(Builder $query, string $checkInDate, string $checkOutDate, string $roomTypeId): Builder
    {
        return $query->where('is_available', true)
            ->when($roomTypeId, fn($q) => $q->where('room_type_id', $roomTypeId))
            ->whereDoesntHave('reservations', function ($query) use ($checkInDate, $checkOutDate) {
                $query->where(function ($q) use ($checkInDate, $checkOutDate) {
                    $q->where('check_in', '<', $checkOutDate)
                        ->where('check_out', '>', $checkInDate);
                });
            });
    }
    public function getAvailableDates(string $startDate, string $endDate): array
    {
        $availableDates = [];

        // Get reservations for this room within the date range
        $reservations = $this->reservations()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('check_in', '<', $endDate)
                    ->where('check_out', '>', $startDate)
                    ->where('status', '!==', 'completed');
            })
            ->orderBy('check_in')
            ->get();

        // Start tracking availability from the start date
        $currentDate = Carbon::parse($startDate);

        foreach ($reservations as $reservation) {
            $checkIn = Carbon::parse($reservation->check_in);
            $checkOut = Carbon::parse($reservation->check_out);

            // Add each day between the current date and the reservation's check-in as available
            while ($currentDate < $checkIn) {
                $availableDates[] = $currentDate->toDateString();
                $currentDate->addDay();
            }

            // Move the current date forward to the day after the reservation's check-out
            $currentDate = $checkOut->copy()->addDay();
        }

        // Add any remaining dates up to the end date as available
        while ($currentDate <= Carbon::parse($endDate)) {
            $availableDates[] = $currentDate->toDateString();
            $currentDate->addDay();
        }

        return $availableDates;
    }
    public function getUnavailableDates(string $checkInDate): array
    {
        // Determine the year and month from the check-in date
        $startDate = Carbon::parse($checkInDate)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        $unavailableDates = [];

        // Get reservations for this room that overlap with the month of the selected check-in date
        // Exclude reservations with status 'completed'
        $reservations = $this->reservations()
            ->where('status', '!=', 'completed')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('check_in', '<=', $endDate)
                    ->where('check_out', '>=', $startDate);
            })
            ->get();

        // Loop through each reservation to get the range of unavailable dates
        foreach ($reservations as $reservation) {
            // Calculate the period within the reservation that overlaps with the selected month
            $reservationStart = Carbon::parse($reservation->check_in)->max($startDate);
            $reservationEnd = Carbon::parse($reservation->check_out)->min($endDate);

            $period = CarbonPeriod::create($reservationStart, $reservationEnd);

            // Add each date in this period to the unavailable dates
            foreach ($period as $date) {
                $unavailableDates[] = $date->toDateString();
            }
        }

        return $unavailableDates;
    }
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_available', true)
            ->whereDoesntHave('reservations', function ($query) {
                $query->where('status', 'confirmed')->orWhere('status', 'pending');  // Mengasumsikan status reservasi 'confirmed'
            });
    }
    public function hotel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
    public function reservations(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Reservation::class);
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasManyThrough
    {
        return $this->hasManyThrough(Payment::class, Reservation::class);
    }

    public function roomType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }
}