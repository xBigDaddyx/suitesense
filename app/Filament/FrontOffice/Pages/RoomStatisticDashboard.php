<?php

namespace App\Filament\FrontOffice\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\AdrChart;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\OccupancyRate;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\ReservationCancellationRateChart;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\ReservationStats;
use App\Filament\FrontOffice\Resources\ReservationResource\Widgets\TotalRevenueChart;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\BookedRoomsChart;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\OccupancyPieChart;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\OccupancyRateChart;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\RevenueParChart;
use App\Filament\FrontOffice\Resources\RoomResource\Widgets\RoomsStat;
use App\Filament\FrontOffice\Widgets\RoomAvailable;
use Carbon\Carbon;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;

class RoomStatisticDashboard extends \Filament\Pages\Dashboard
{
    use HasFiltersForm;
    protected static ?string $navigationIcon = 'tabler-door-enter';
    protected static string $routePath = 'room-statistics';
    protected static ?string $title = 'Room Statistics';

    public function filtersForm(Form $form): Form
    {
        return $form

            ->schema([
                Section::make()
                    ->schema([
                        DatePicker::make('startDate'),
                        // ->afterStateHydrated(function (DatePicker $component, string $state) {
                        //     $component->state(Carbon::now()->startOfMonth()->format('Y-m-d'));
                        // }),
                        DatePicker::make('endDate')
                        // ->afterStateHydrated(function (DatePicker $component, string $state) {
                        //     $component->state(Carbon::now()->endOfMonth()->format('Y-m-d'));
                        // }),
                    ])
                    ->columns(3),
            ]);
    }
    public function getColumns(): int|string|array
    {
        return 12;
    }
    public function getWidgets(): array
    {
        return [
            RoomsStat::class,
            BookedRoomsChart::class,
            OccupancyRateChart::class,
            RevenueParChart::class,
            ReservationCancellationRateChart::class,
            AdrChart::class,
            TotalRevenueChart::class,

            // ReservationStats::class,
            // RoomAvailable::class,


        ];
    }
}
