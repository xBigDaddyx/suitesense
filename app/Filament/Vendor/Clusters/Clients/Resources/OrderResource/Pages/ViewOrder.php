<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\OrderResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
