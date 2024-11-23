<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\LicenseResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\LicenseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLicense extends EditRecord
{
    protected static string $resource = LicenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
