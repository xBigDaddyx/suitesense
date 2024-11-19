<?php

namespace App\Models;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ReservationStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Ramsey\Uuid\Type\Decimal;

class Reservation extends Model
{
    use HasUuids;
    use SoftDeletes;
    use Userstamps;
    protected static function booted(): void
    {
        parent::booted();
        self::creating(static function ($model) {
            if ($model->status === null) {
                $model->status = ReservationStatus::PENDING->value;
            }

            // Calculate the next number number in the series
            $model->number_series = 'SS-RV-' . Carbon::now()->format('m') . '-' . Carbon::now()->format('Y');
            $model->number_reservation = (Reservation::where('number_series', $model->number_series)->max('number_reservation') ?? 0) + 1;
            // Compose the full number
            $model->number = $model->number_series . '-' . $model->number_reservation;
        });
    }
    protected $fillable = [
        'reason',
        'cancelled_by',
        'cancelled_at',
        'cancelled_reason',
        'total_price',
        'guest_check_in_at',
        'guest_check_out_at',
        'guest_status',
        'checked_in_by',
        'checked_out_by',
        'reservation_source',
        'has_payment',
        'estimate_arrival',
        'room_id',
        'guest_id',
        'check_in',
        'check_out',
        'status',
        'number',
        'number_series',
        'number_reservation',
        'is_completed_payment',
        'is_extended',
    ];
    protected $casts = [
        'has_payment' => 'boolean',
        'estimate_arrival' => 'datetime',
        'is_completed_payment' => 'boolean',
        'is_extended' => 'boolean',

    ];
    public function getTotalNightsAttribute()
    {
        return number_format(Carbon::parse($this->check_in)->diffInDays(Carbon::parse($this->check_out)), 0);
    }

    public function extend($newCheckOutDate): Payment
    {
        $originalCheckOut = Carbon::parse($this->check_out);
        $newCheckOut = Carbon::parse($newCheckOutDate);

        if ($newCheckOut->lessThanOrEqualTo($originalCheckOut)) {
            throw new \Exception('Tanggal baru harus setelah tanggal check-out asli.');
        }

        // Hitung hari tambahan
        $additionalDays = $originalCheckOut->diffInDays($newCheckOut);

        // Validasi ketersediaan kamar untuk hari tambahan
        $isAvailable = Room::where('id', $this->room_id)
            ->whereHas('reservations', function ($query) use ($originalCheckOut, $newCheckOut) {
                $query->whereBetween('check_in', [$originalCheckOut, $newCheckOut])
                    ->orWhereBetween('check_out', [$originalCheckOut, $newCheckOut]);
            })->exists();
        if (!$isAvailable) {
            throw new \Exception('Kamar tidak tersedia untuk perpanjangan.');
        }

        // Hitung biaya tambahan
        $roomPrice = $this->room->price;
        $additionalAmount =  $roomPrice * $additionalDays;
        // Perbarui tanggal check-out
        $this->check_out = $newCheckOut;

        // Tambahkan biaya ke total harga
        $this->total_price += $additionalAmount;
        $this->is_extended = true;
        $this->save();

        // Buat payment untuk perpanjangan
        $payment = Payment::create([
            'reservation_id' => $this->id,
            'amount' => $additionalAmount,
            'status' => PaymentStatus::PENDING->value,
            'method' => PaymentMethod::CASH->value,
            'type' => PaymentType::EXTEND->value,
        ]);

        return $payment;
    }
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }
    public function room(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
    public function guest(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id');
    }
    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
