<?php

namespace App\Filament\FrontOffice\Pages;

use App\Enums\RoomStatus;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\OccupancyRate;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\ReservationStats;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\RoomsStat;
use App\Filament\FrontOffice\Widgets\Room;

use App\Models\RoomType;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;

class RoomsDashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;
    protected static ?string $navigationIcon = 'tabler-door';
    protected static string $routePath = 'rooms-status';
    protected static ?string $title = 'Room Status';
    public function getColumns(): int|string|array
    {
        return 12;
    }
    public function filtersForm(Form $form): Form
    {
        return $form

            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label(trans('frontOffice.room.statusLabel'))
                            ->options(collect(RoomStatus::cases())->mapWithKeys(fn($status) => [
                                $status->value => $status->label(),
                            ])->toArray())
                            ->default('available'),
                        Forms\Components\Select::make('room_type')
                            ->label(trans('frontOffice.room.typeLabel'))
                            ->options(RoomType::all()->pluck('name', 'id')),
                        Forms\Components\TextInput::make('name')
                            ->label(trans('frontOffice.room.nameLabel')),
                        Forms\Components\TextInput::make('description')
                            ->label(trans('frontOffice.roomType.descriptionLabel'))
                    ])
                    ->columns(4),
            ]);
    }
    public function getWidgets(): array
    {
        return [
            // ReservationStats::class,
            Room::class,


        ];
    }
}
