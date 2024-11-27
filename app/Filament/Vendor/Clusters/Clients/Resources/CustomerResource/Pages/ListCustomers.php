<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
