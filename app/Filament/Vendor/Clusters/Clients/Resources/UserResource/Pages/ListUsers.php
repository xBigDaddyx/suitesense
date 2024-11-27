<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\UserResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
