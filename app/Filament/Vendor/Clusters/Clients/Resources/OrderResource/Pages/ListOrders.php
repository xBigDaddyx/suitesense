<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\OrderResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
