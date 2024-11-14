<?php

namespace App\Filament\FrontOffice\Resources;

use App\Filament\FrontOffice\Resources\RoomResource\Pages;
use App\Filament\FrontOffice\Resources\RoomResource\RelationManagers;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Query\Builder as QueryBuilder;
use stdClass;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;
    protected static bool $isScopedToTenant = true;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.room.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'type'];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {

        return [
            'Room Type' => $record->type,
            'Price' => trans('frontOffice.room.pricePrefix') . ' ' . $record->price,
            'Is Available' => $record->is_available ? 'Yes' : 'No',
        ];
    }
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            Forms\Components\Actions\Action::make('edit')
                ->icon('tabler-pencil')
                ->color('warning')
                ->url(static::getUrl('edit', ['record' => $record]), shouldOpenInNewTab: true)
                ->authorize(fn(): bool => auth()->user()->can('view_room'))
                ->modalWidth(MaxWidth::FiveExtraLarge),
            Forms\Components\Actions\Action::make('view')
                ->icon('tabler-eye')
                ->url(static::getUrl('view', ['record' => $record]))
                ->color('info')
                ->authorize(fn(): bool => auth()->user()->can('update_room') || auth()->user()->can('edit_room'))
                ->modalWidth(MaxWidth::FiveExtraLarge),

        ];
    }
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }
    public static function getNavigationLabel(): string
    {
        return trans('frontOffice.room.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.room.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.room.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.room.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.room.title');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Details')
                    ->description('Please provide the necessary information to add a new room. Make sure to fill in all required fields.')
                    ->icon('tabler-door') // Custom icon for better visual appeal
                    ->columns(2)
                    ->schema([
                        // Forms\Components\Select::make('hotel_id')
                        //     ->relationship('hotel', 'name')
                        //     ->required()
                        //     ->placeholder('Select a hotel') // Placeholder for better UX
                        //     ->searchable() // Allow searching through hotels
                        //     ->reactive() // React to changes for dynamic updates
                        //     ->label('Hotel') // Custom label for clarity
                        //     ->helperText('Please select the hotel where this room is located.'), // Helper text for guidance
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Enter room name (e.g., Room 01, Room 02)') // Placeholder for better UX
                            ->label('Room Name') // Custom label for clarity
                            ->helperText('Specify the name of room being added.'), // Helper text for guidance

                        Forms\Components\Select::make('room_type_id')
                            ->relationship('roomType', 'name')
                            ->required()
                            ->placeholder('Enter room type (e.g., Deluxe, Suite)') // Placeholder for better UX
                            ->label('Room Type') // Custom label for clarity
                            ->helperText('Specify the type of room being added.'), // Helper text for guidance

                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix(trans('frontOffice.room.pricePrefix'))
                            ->placeholder('Enter price (in USD)') // Placeholder for better UX
                            ->minValue(0) // Ensure price cannot be negative
                            ->label('Room Price') // Custom label for clarity
                            ->helperText('Enter the room price per night. Use numbers only.'), // Helper text for guidance

                        Forms\Components\Toggle::make('is_available')
                            ->columnSpanFull()
                            ->onIcon('tabler-check')
                            ->offIcon('tabler-x')
                            ->onColor('success')
                            ->offColor('danger')
                            ->required()
                            ->label('Available') // Custom label for clarity
                            ->inline() // Display toggle inline for a cleaner look
                            ->default(true) // Default value for availability
                            ->helperText('Toggle to mark the room as available or unavailable.'), // Helper text for guidance
                        \Filament\Forms\Components\SpatieMediaLibraryFileUpload::make('image')
                            ->columnSpanFull()
                            ->panelLayout('grid')
                            ->moveFiles()
                            ->collection('room-photos')
                            ->label('Room Photos') // Custom label for clarity
                            ->helperText('Upload an image for the room.') // Helper text for guidance
                            ->multiple()
                            ->image()
                            ->responsiveImages()
                            ->columns(2),
                    ]),

            ])
            ->columns(2); // Use a two-column layout for better organization
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('frontOffice.room.indexLabel'))
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
                \Filament\Tables\Columns\SpatieMediaLibraryImageColumn::make('image')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->conversion('preview')
                    ->collection('room-photos')
                    ->label(trans('frontOffice.room.imageLabel')),
                // Tables\Columns\TextColumn::make('id')
                //     ->label('ID'),
                // Tables\Columns\TextColumn::make('hotel.name')
                //     ->label(trans('frontOffice.room.hotelLabel')),
                Tables\Columns\TextColumn::make('roomType.name')
                    ->icon('tabler-files')
                    ->color('primary')
                    ->limit(25)
                    ->tooltip(fn(Room $record): string => $record->roomType->description)
                    ->description(function (Room $record, Tables\Columns\TextColumn $column): string {
                        $state = $record->roomType->description;

                        if (strlen($state) >= $column->getCharacterLimit()) {
                            return substr($state, 0, $column->getCharacterLimit()) . '...';
                        }
                        return $state;
                    })
                    ->label(trans('frontOffice.room.typeLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->suffix('/night')
                    ->label(trans('frontOffice.room.priceLabel'))
                    ->money()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_available')
                    ->summarize(

                        Count::make()
                            ->prefix('Total available: ')
                            ->query(fn(QueryBuilder $query) => $query->where('is_available', true))
                            ->label(trans('frontOffice.room.availableLabel'))
                    )
                    ->label(trans('frontOffice.room.availableLabel'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->label(trans('frontOffice.room.deletedAtLabel'))
                    ->icon('tabler-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->label(trans('frontOffice.room.deletedByLabel'))
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('tabler-user')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->label(trans('frontOffice.room.createdByLabel'))
                    ->icon('tabler-user')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->label(trans('frontOffice.room.updatedByLabel'))
                    ->icon('tabler-user')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('frontOffice.room.createdAtLabel'))
                    ->icon('tabler-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(trans('frontOffice.room.updatedAtLabel'))
                    ->icon('tabler-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_available')
                    ->label(trans('frontOffice.room.availableLabel'))
                    ->options([
                        true => 'Available',
                        false => 'Not Available',
                    ])
                    ->placeholder('Select availability'),

                Tables\Filters\Filter::make('created_at')
                    ->label(trans('frontOffice.room.createdAtLabel'))
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->button()
                    ->authorize(fn(User $user): bool => auth()->user()->can('view_room')),
                Tables\Actions\EditAction::make()
                    ->button()
                    ->authorize(fn(User $user): bool => auth()->user()->can('update_room')),
                Tables\Actions\DeleteAction::make()
                    ->button()
                    ->requiresConfirmation()
                    ->authorize(fn(User $user): bool => auth()->user()->can('delete_room')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->authorize(fn(User $user): bool => auth()->user()->can('delete_room')),
                    Tables\Actions\ForceDeleteBulkAction::make()
                        ->authorize(fn(User $user): bool => auth()->user()->can('force_delete_room')),
                    Tables\Actions\RestoreBulkAction::make()
                        ->authorize(fn(User $user): bool => auth()->user()->can('restore_room')),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // RelationManagers\ReservationsRelationManager::class,
            // RelationManagers\UsersRelationManager::class,
        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ManageReservations::class,
            Pages\GuestHistories::class,
            Pages\ViewCalendar::class,
            Pages\EditRoom::class,
            Pages\ViewRoom::class,
        ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
            'view' => Pages\ViewRoom::route('/{record}/view'),
            'manageReservations' => Pages\ManageReservations::route('/{record}/manage-reservations'),
            'guestHistories' => Pages\GuestHistories::route('/{record}/guest-histories'),
            'viewCalendar' => Pages\ViewCalendar::route('/{record}/calendar')
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
