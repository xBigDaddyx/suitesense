<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::CANCELED => 'Canceled',
        };
    }
    public function description(): string
    {
        return match ($this) {
            self::PENDING => 'The reservation has been created but is awaiting confirmation. This status indicates that the reservation is not yet finalized and may still be subject to change.',
            self::PAID => 'The reservation has been reviewed and accepted, ensuring that the booking is officially secured. This status indicates that the reservation is set and expected to proceed as planned.',
            self::CANCELED => 'The reservation has been withdrawn or terminated either by the user or the system. This status indicates that the booking will not take place, and no further actions are required.',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::PENDING => 'tabler-circle-dot',
            self::PAID => 'tabler-circle-check',
            self::CANCELED => 'tabler-circle-x',
        };
    }
    public function color(): string
    {

        return match ($this) {
            self::PENDING => 'warning',
            self::PAID => 'info',
            self::CANCELED => 'danger',
        };
    }
}
