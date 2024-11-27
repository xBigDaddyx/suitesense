<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Filament\FrontOffice\Resources\RoomResource;
use App\Models\Reservation;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;


class ManageReservations extends ManageRelatedRecords
{

    protected static string $resource = RoomResource::class;

    protected static string $relationship = 'reservations';
    protected static ?string $badgeColor = 'danger';
    protected static ?string $badgeTooltip = 'There are pending reservations';
    protected static ?string $navigationIcon = 'tabler-calendar';

    public function form(Form $form): Form
    {
        return ReservationResource::form($form);
    }
    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->reservations()->pending()->count();
    }


    public static function getNavigationLabel(): string
    {
        return 'Manage Reservations';
    }



    public function table(Table $table): Table
    {
        return ReservationResource::table($table)
            ->heading('Reservations')
            ->description('provides a comprehensive overview of past interactions and reservations made by guests at our establishment.')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
            ]);
        // return $table
        //     ->recordTitle(fn(Reservation $record): string => "{$record->guest->name} ({$record->status})")
        //     ->columns([
        //         Tables\Columns\TextColumn::make('index')
        //             ->label(trans('frontOffice.reservation.indexLabel'))
        //             ->state(
        //                 static function (HasTable $livewire, stdClass $rowLoop): string {
        //                     return (string) (
        //                         $rowLoop->iteration +
        //                         ($livewire->getTableRecordsPerPage() * (
        //                             $livewire->getTablePage() - 1
        //                         ))
        //                     );
        //                 }
        //             ),
        //         Tables\Columns\TextColumn::make('guest.name')
        //             ->sortable()
        //             ->searchable()
        //             ->description(fn(Reservation $record): string => "{$record->guest->email} ({$record->guest->phone})")
        //             ->weight(FontWeight::SemiBold)
        //             ->label(trans('frontOffice.reservation.guestLabel')),
        //         Tables\Columns\TextColumn::make('check_in')

        //             ->label(trans('frontOffice.reservation.checkInLabel'))
        //             ->badge()
        //             ->icon('tabler-calendar')
        //             ->dateTime(),
        //         Tables\Columns\TextColumn::make('check_out')
        //             ->label(trans('frontOffice.reservation.checkOutLabel'))
        //             ->badge()
        //             ->icon('tabler-calendar')
        //             ->dateTime(),
        //         Tables\Columns\TextColumn::make('status')
        //             ->label(trans('frontOffice.reservation.statusLabel'))
        //             ->badge()
        //             // ->icons(
        //             //     fn(string $state): string => match ($state) {
        //             //         'pending' => 'tabler-circle-dot',
        //             //         'confirmed' => 'tabler-circle-check',
        //             //         'canceled' => 'tabler-circle-x',
        //             //         'completed' => 'tabler-circle-check',
        //             //     }
        //             // )
        //             ->color(fn(string $state): string => match ($state) {
        //                 'pending' => 'warning',
        //                 'confirmed' => 'success',
        //                 'canceled' => 'danger',
        //                 'completed' => 'success',
        //             })
        //             ->icon(fn(string $state): string => match ($state) {
        //                 'pending' => 'tabler-circle-dot',
        //                 'confirmed' => 'tabler-circle-check',
        //                 'canceled' => 'tabler-circle-x',
        //                 'completed' => 'tabler-circle-check',
        //             })
        //     ])
        //     ->filters([
        //         Tables\Filters\SelectFilter::make('status')
        //             ->label('Status')
        //             ->options([
        //                 'pending' => 'Pending',
        //                 'confirmed' => 'Confirmed',
        //                 'canceled' => 'Canceled',
        //                 'completed' => 'Completed',
        //             ]),
        //     ])
        //     ->headerActions([
        //         Tables\Actions\CreateAction::make()
        //             ->icon('tabler-plus')
        //             ->authorize(fn(): bool => auth()->user()->can('create_reservation')),
        //         Tables\Actions\AssociateAction::make()
        //             ->recordSelect(
        //                 fn(Select $select) => $select->placeholder('Select a reservations'),
        //             )
        //             ->recordSelectSearchColumns(['guest.name', 'check_in', 'check_out', 'status'])
        //             ->multiple()
        //             ->icon('tabler-link')
        //             ->authorize(fn(): bool => auth()->user()->can('associate_reservation')),
        //     ])
        //     ->actions([
        //         Tables\Actions\EditAction::make()
        //             ->button()
        //             ->authorize(fn(): bool => auth()->user()->can('update_reservation')),
        //         Tables\Actions\DissociateAction::make()
        //             ->button()
        //             ->authorize(fn(): bool => auth()->user()->can('dissociate_reservation')),
        //         Tables\Actions\DeleteAction::make()
        //             ->button()
        //             ->authorize(fn(): bool => auth()->user()->can('delete_reservation')),
        //     ])
        //     ->bulkActions([
        //         Tables\Actions\BulkActionGroup::make([
        //             Tables\Actions\DissociateBulkAction::make()
        //                 ->authorize(fn(): bool => auth()->user()->can('dissociate_reservation')),
        //             Tables\Actions\DeleteBulkAction::make()
        //                 ->authorize(fn(): bool => auth()->user()->can('delete_reservation')),
        //         ]),
        //     ]);
    }
}
