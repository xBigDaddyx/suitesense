<?php

namespace App\Filament\FrontOffice\Resources;

use App\Filament\FrontOffice\Resources\AdditionalFacilityResource\Pages;
use App\Filament\FrontOffice\Resources\AdditionalFacilityResource\RelationManagers;
use App\Models\AdditionalFacility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class AdditionalFacilityResource extends Resource
{
    protected static ?string $model = AdditionalFacility::class;


    protected static ?int $navigationSort = 1;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.additionalFacility.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    // public static function getGlobalSearchResultDetails(Model $record): array
    // {

    //     return [
    //         'Room Type' => $record->reservation->room->roomType->name,
    //         'Total Price' => trans('frontOffice.room.pricePrefix') . ' ' . $record->reservation->total_price,
    //         'status' => $record->status
    //     ];
    // }
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
        return $record->number;
    }
    public static function getNavigationLabel(): string
    {
        return trans('frontOffice.additionalFacility.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.additionalFacility.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.additionalFacility.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.additionalFacility.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.additionalFacility.title');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(trans('frontOffice.additionalFacility.nameLabel'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label(trans('frontOffice.additionalFacility.descriptionLabel'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix(trans('frontOffice.additionalFacility.pricePrefix')),
                Forms\Components\TextInput::make('unit')
                    ->label(trans('frontOffice.additionalFacility.unitLabel'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('index')
                    ->label(trans('frontOffice.invoice.indexLabel'))
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('unit'),
                Tables\Columns\TextColumn::make('price')
                    ->money()
                    ->sortable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageAdditionalFacilities::route('/'),
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
