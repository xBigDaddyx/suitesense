<?php

namespace App\Enums;

enum ReservationStatus: string
{

    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::COMPLETED => 'Completed',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'The reservation has been created but is awaiting confirmation. This status indicates that the reservation is not yet finalized and may still be subject to change.',
            self::CONFIRMED => 'The reservation has been reviewed and accepted, ensuring that the booking is officially secured. This status indicates that the reservation is set and expected to proceed as planned.',
            self::CANCELLED => 'The reservation has been withdrawn or terminated either by the user or the system. This status indicates that the booking will not take place, and no further actions are required.',
            self::COMPLETED => 'The reservation has successfully concluded, marking the booking as finished. This status shows that the service associated with the reservation was delivered as scheduled.',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'tabler-circle-dot',
            self::CONFIRMED => 'tabler-circle-check',
            self::CANCELLED => 'tabler-circle-x',
            self::COMPLETED => 'tabler-circle-check',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::PENDING => 'warning',
            self::CONFIRMED => 'info',
            self::CANCELLED => 'danger',
            self::COMPLETED => 'success',
        };
    }
}
