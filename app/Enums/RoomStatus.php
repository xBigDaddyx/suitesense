<?php

namespace App\Enums;

enum RoomStatus: string
{
    case AVAILABLE = 'available';    // Kamar tersedia
    case BOOKED = 'booked';          // Kamar sudah dipesan
    case OCCUPIED = 'occupied';      // Kamar sedang dihuni
    case MAINTENANCE = 'maintenance'; // Kamar dalam perawatan

    /**
     * Get a human-readable description for the enum value.
     */
    public function description(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Kamar tersedia untuk dipesan.',
            self::BOOKED => 'Kamar telah dipesan oleh tamu.',
            self::OCCUPIED => 'Kamar sedang dihuni oleh tamu.',
            self::MAINTENANCE => 'Kamar sedang dalam perawatan.',
        };
    }

    /**
     * Get a short, display-friendly label for the enum value.
     */
    public function label(): string
    {
        return match ($this) {
            self::AVAILABLE => 'Tersedia',
            self::BOOKED => 'Dipesan',
            self::OCCUPIED => 'Dihuni',
            self::MAINTENANCE => 'Perawatan',
        };
    }
}
