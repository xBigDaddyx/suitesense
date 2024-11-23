<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\LicenseResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\LicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLicense extends ViewRecord
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
