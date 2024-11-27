<?php

namespace App\Filament\Vendor\Clusters\Clients\Resources\UserResource\Pages;

use App\Filament\Vendor\Clusters\Clients\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
