<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Filament\FrontOffice\Resources\RoomResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
