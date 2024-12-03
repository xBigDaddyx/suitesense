<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Filament\FrontOffice\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRoom extends ViewRecord
{
    protected static string $resource = RoomResource::class;
    protected static string $view = 'filament.front-office.resources.room-resource.pages.view-room';
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    public function getContentTabIcon(): ?string
    {
        return 'tabler-door';
    }
}
