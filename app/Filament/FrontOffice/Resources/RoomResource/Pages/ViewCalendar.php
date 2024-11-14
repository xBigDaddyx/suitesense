<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Filament\FrontOffice\Resources\RoomResource;
use App\Models\Reservation;
use App\Models\Room;
use Carbon\Carbon;
use Filament\Resources\Pages\ManageRelatedRecords;

class ViewCalendar extends ManageRelatedRecords
{
    protected static string $resource = RoomResource::class;
    protected static string $view = 'filament.front-office.resources.room-resource.pages.view-calendar';
    protected static ?string $navigationIcon = 'tabler-calendar';
    protected static ?string $navigationLabel = 'View Calendar';
    protected static string $relationship = 'reservations';
}
