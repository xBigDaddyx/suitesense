<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\OrderResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\OrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
