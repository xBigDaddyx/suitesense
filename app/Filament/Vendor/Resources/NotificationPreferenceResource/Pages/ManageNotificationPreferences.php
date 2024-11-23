<?php

namespace App\Filament\Vendor\Resources\NotificationPreferenceResource\Pages;

use App\Filament\Vendor\Resources\NotificationPreferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageNotificationPreferences extends ManageRecords
{
    protected static string $resource = NotificationPreferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
