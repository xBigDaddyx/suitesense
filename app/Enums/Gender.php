<?php

namespace App\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';


    public function label(): string
    {
        return match ($this) {
            self::MALE => 'Laki - Laki',
            self::FEMALE => 'Perempuan',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::MALE => 'tabler-gender-male',
            self::FEMALE => 'tabler-gender-female',
        };
    }
    public function color(): string
    {
        return match ($this) {
            self::MALE => 'warning',
            self::FEMALE => 'info',
        };
    }
}
