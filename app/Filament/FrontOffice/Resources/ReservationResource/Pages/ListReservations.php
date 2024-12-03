<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Pages;

use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Models\Reservation;
use App\States\Reservation\Cancelled;
use App\States\Reservation\CheckedIn;
use App\States\Reservation\CheckedOut;
use App\States\Reservation\Confirmed;
use App\States\Reservation\Pending;
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
                ->modifyQueryUsing(fn(Builder $query) => $query->whereState('reservations.state', Pending::class))
                // ->icon()
                ->badge(Reservation::query()->whereState('reservations.state', Pending::class)->count())
                ->badgeColor('primary'),
            'confirmed' => Tab::make('Confirmed')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereState('reservations.state', Confirmed::class))
                // ->icon()
                ->badge(Reservation::query()->whereState('reservations.state', Confirmed::class)->count())
                ->badgeColor('primary'),
            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereState('reservations.state', Cancelled::class))
                // ->icon()
                ->badge(Reservation::query()->whereState('reservations.state', Cancelled::class)->count())
                ->badgeColor('primary'),
            'checkedIn' => Tab::make('Checked In')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereState('reservations.state', CheckedIn::class))
                // ->icon()
                ->badge(Reservation::query()->whereState('reservations.state', CheckedIn::class)->count())
                ->badgeColor('primary'),
            'checkedOut' => Tab::make('Checked out')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereState('reservations.state', CheckedOut::class))
                // ->icon()
                ->badge(Reservation::query()->whereState('reservations.state', CheckedOut::class)->count())
                ->badgeColor('primary'),


        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make()
            //     ->authorize(fn(): bool => auth()->user()->can('create_reservation'))
            //     ->icon('tabler-plus'),
        ];
    }
}
