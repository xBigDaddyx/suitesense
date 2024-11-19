<?php

namespace App\Filament\FrontOffice\Resources\AdditionalFacilityResource\Pages;

use App\Filament\FrontOffice\Resources\AdditionalFacilityResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAdditionalFacilities extends ManageRecords
{
    protected static string $resource = AdditionalFacilityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
