<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Enums\Gender;
use App\Filament\FrontOffice\Resources\RoomResource;
use App\States\Reservation\CheckedIn;
use App\States\Reservation\CheckedOut;
use App\States\Reservation\Confirmed;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class GuestHistories extends ManageRelatedRecords
{
    protected static string $resource = RoomResource::class;

    protected static string $relationship = 'reservations';
    protected static ?string $navigationIcon = 'tabler-users';
    public function isReadOnly(): bool
    {
        return false;
    }
    public static function getNavigationLabel(): string
    {
        return 'Guest Histories';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Guest Histories')
            ->description('provides a comprehensive overview of past interactions and reservations made by guests at our establishment.')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('frontOffice.user.indexLabel'))
                    ->state(
                        static function (HasTable $livewire, stdClass $rowLoop): string {
                            return (string) (
                                $rowLoop->iteration +
                                ($livewire->getTableRecordsPerPage() * (
                                    $livewire->getTablePage() - 1
                                ))
                            );
                        }
                    ),
                Tables\Columns\TextColumn::make('guest.name')
                    ->weight(FontWeight::SemiBold)
                    ->icon('tabler-user')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.nameLabel')),
                Tables\Columns\TextColumn::make('guest.email')
                    ->icon('tabler-mail')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.emailLabel')),
                Tables\Columns\TextColumn::make('guest.gender')
                    ->color(fn($state): string => Gender::from($state)->color())
                    ->icon(fn($state): string => Gender::from($state)->icon())
                    ->label(trans('frontOffice.user.genderLabel')),
                Tables\Columns\TextColumn::make('phone')
                    ->icon('tabler-phone')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.phoneLabel')),
                Tables\Columns\TextColumn::make('guest.address')
                    ->icon('tabler-map')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.addressLabel')),
                Tables\Columns\TextColumn::make('check_in')
                    ->date()
                    ->weight(FontWeight::SemiBold)
                    ->icon('tabler-calendar')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.checkInLabel')),
                Tables\Columns\TextColumn::make('check_out')
                    ->date()
                    ->weight(FontWeight::SemiBold)
                    ->icon('tabler-calendar')
                    ->sortable()
                    ->searchable()
                    ->label(trans('frontOffice.user.checkOutLabel')),
            ])
            ->modifyQueryUsing(fn(Builder $query): Builder => $query->whereState('state', CheckedOut::class)->orWhereState('state', CheckedIn::class))
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
