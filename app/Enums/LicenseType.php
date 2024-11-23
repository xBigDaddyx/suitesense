<?php

namespace App\Enums;

enum LicenseType: string
{
    case PRODUCTION = 'production';
    case TRIAL = 'trial';


    public function label(): string
    {
        return match ($this) {
            self::PRODUCTION => 'Production',
            self::TRIAL => 'Trial',
        };
    }
    public function icon(): string
    {
        return match ($this) {
            self::PRODUCTION => 'tabler-file-certificate',
            self::TRIAL => 'tabler-file-invoice',
        };
    }
    public function color(): string
    {
        return match ($this) {
            self::PRODUCTION => 'success',
            self::TRIAL => 'warning',
        };
    }
}
