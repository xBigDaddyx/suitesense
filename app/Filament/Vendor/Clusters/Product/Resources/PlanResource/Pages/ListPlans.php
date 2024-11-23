<?php

namespace App\Filament\Vendor\Clusters\Product\Resources\PlanResource\Pages;

use App\Filament\Vendor\Clusters\Product\Resources\PlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
