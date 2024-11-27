<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomer extends CreateRecord
{
    protected static string $resource = CustomerResource::class;
}
