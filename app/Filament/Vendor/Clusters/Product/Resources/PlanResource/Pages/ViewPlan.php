<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\PlanResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlan extends ViewRecord
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
