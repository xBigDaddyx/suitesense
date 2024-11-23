<?php

namespace App\Filament\Vendor\Clusters\Subscriptions\Resources\SubscriptionResource\Pages;

use App\Filament\Vendor\Clusters\Subscriptions\Resources\SubscriptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubscriptions extends ListRecords
{
    protected static string $resource = SubscriptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
