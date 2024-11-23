<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\LicenseResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\LicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLicenses extends ListRecords
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
