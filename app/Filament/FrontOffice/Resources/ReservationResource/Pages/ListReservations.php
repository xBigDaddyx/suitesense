<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Pages;

use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListReservations extends ListRecords
{

    use ExposesTableToWidgets;
    protected static string $resource = ReservationResource::class;
    protected function getHeaderWidgets(): array
    {
        return [
            ReservationResource\Widgets\ReservationStats::class,
        ];
    }
    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All reservations'),
            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('reservations.status', ReservationStatus::PENDING->value))
                ->icon(ReservationStatus::PENDING->icon())
                ->badge(Reservation::query()->where('reservations.status', ReservationStatus::PENDING->value)->count())
                ->badgeColor('danger'),
            'confirmed' => Tab::make('Confirmed')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('reservations.status', ReservationStatus::CONFIRMED->value))
                ->icon(ReservationStatus::CONFIRMED->icon())
                ->badge(Reservation::query()->where('reservations.status', ReservationStatus::CONFIRMED->value)->count())
                ->badgeColor('info'),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('reservations.status', ReservationStatus::CANCELLED->value))
                ->icon(ReservationStatus::CANCELLED->icon())
                ->badge(Reservation::query()->where('reservations.status', ReservationStatus::CANCELLED->value)->count())
                ->badgeColor('warning'),
            'completed' => Tab::make('Completed')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('reservations.status', ReservationStatus::COMPLETED->value))
                ->icon(ReservationStatus::COMPLETED->icon())
                ->badge(Reservation::query()->where('reservations.status', ReservationStatus::COMPLETED->value)->count())
                ->badgeColor('success'),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->authorize(fn(): bool => auth()->user()->can('create_reservation'))
                ->icon('tabler-plus'),
        ];
    }
}
