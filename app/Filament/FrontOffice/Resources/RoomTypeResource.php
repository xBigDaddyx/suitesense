<?php

namespace App\Filament\FrontOffice\Resources;

use App\Filament\FrontOffice\Resources\RoomTypeResource\Pages;
use App\Filament\FrontOffice\Resources\RoomTypeResource\RelationManagers;
use App\Models\RoomType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class RoomTypeResource extends Resource
{
    protected static ?string $model = RoomType::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 1;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.roomType.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {

        return [
            'Room Type' => $record->name,
            'Description' => $record->description,
        ];
    }
    public static function getGlobalSearchResultActions(Model $record): array
    {
        return [
            // Forms\Components\Actions\Action::make('edit')
            //     ->icon('tabler-pencil')
            //     ->color('warning')
            //     ->url(static::getUrl('edit', ['record' => $record]), shouldOpenInNewTab: true)
            //     ->authorize(fn(): bool => auth()->user()->can('view_room'))
            //     ->modalWidth(MaxWidth::FiveExtraLarge),
            // Forms\Components\Actions\Action::make('view')
            //     ->icon('tabler-eye')
            //     ->url(static::getUrl('view', ['record' => $record]))
            //     ->color('info')
            //     ->authorize(fn(): bool => auth()->user()->can('update_room') || auth()->user()->can('edit_room'))
            //     ->modalWidth(MaxWidth::FiveExtraLarge),

        ];
    }
    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
    {
        return $record->name;
    }
    public static function getNavigationLabel(): string
    {
        return trans('frontOffice.roomType.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.roomType.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.roomType.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.roomType.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.roomType.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Room Type Details')
                    ->description('Please provide the necessary information to add a new room type. Make sure to fill in all required fields.')
                    ->icon('tabler-files') // Custom icon for better visual appeal
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->maxLength(255),

                    ]),
                Forms\Components\Section::make('Room Type Facilities')
                    ->description('Please provide the necessary information to add a new room type. Make sure to fill in all required fields.')
                    ->icon('tabler-files') // Custom icon for better visual appeal
                    ->columns(2)
                    ->schema([
                        Forms\Components\Repeater::make('facilities')
                            ->grid(2)
                            ->columnSpanFull()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(255),
                            ])
                            ->minItems(1)
                            ->itemLabel(fn(array $state): ?string => $state['name'] ?? null)
                            ->collapsible()
                            ->collapseAllAction(
                                fn(Forms\Components\Actions\Action $action) => $action->label('Collapse all facilities'),
                            )
                            ->deleteAction(
                                fn(Forms\Components\Actions\Action $action) => $action->requiresConfirmation(),
                            )
                            ->addActionLabel('Add facilities'),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('frontOffice.roomType.indexLabel'))
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
                Tables\Columns\TextColumn::make('name')
                    ->weight(FontWeight::SemiBold)
                    ->label(trans('frontOffice.roomType.nameLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->tooltip(fn(Model $record): string => $record->description)
                    ->label(trans('frontOffice.roomType.descriptionLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('rooms_count')->counts('rooms')
                    ->color('primary')
                    ->label(trans('frontOffice.roomType.roomsCountLabel'))
                    ->icon('tabler-door'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('frontOffice.roomType.deletedAtLabel'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('frontOffice.roomType.deletedByLabel'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('frontOffice.roomType.createdByLabel'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_by')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label(trans('frontOffice.roomType.updatedByLabel'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('frontOffice.roomType.createdAtLabel'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(trans('frontOffice.roomType.updatedAtLabel'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageRoomTypes::route('/'),
        ];
    }
}
