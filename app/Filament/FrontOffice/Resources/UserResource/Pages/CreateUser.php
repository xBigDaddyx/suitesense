<?php

namespace App\Filament\FrontOffice\Resources\UserResource\Pages;

use App\Filament\FrontOffice\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
