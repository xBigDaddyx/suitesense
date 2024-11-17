<?php

namespace App\Filament\FrontOffice\Resources;

use App\Filament\FrontOffice\Resources\PaymentResource\Pages;
use App\Filament\FrontOffice\Resources\PaymentResource\RelationManagers;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?int $navigationSort = 1;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.payment.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'method'];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {

        return [
            'Room Type' => $record->reservation->room->roomType->name,
            'Total Price' => trans('frontOffice.room.pricePrefix') . ' ' . $record->reservation->total_price,
            'status' => $record->status
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
        return $record->number;
    }
    public static function getNavigationLabel(): string
    {
        return trans('frontOffice.payment.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.payment.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.payment.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.payment.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.payment.title');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('reservation_id')
                    ->relationship('reservation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deleted_by')
                    ->numeric(),
                Forms\Components\TextInput::make('created_by')
                    ->numeric(),
                Forms\Components\TextInput::make('updated_by')
                    ->numeric(),
            ]);
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

                Tables\Columns\TextColumn::make('number')
                    ->label(trans('frontOffice.payment.numberLabel')),
                Tables\Columns\TextColumn::make('reservation.number')
                    ->label(trans('frontOffice.payment.reservationNumberLabel')),
                Tables\Columns\TextColumn::make('reservation.room.roomType.name')
                    ->label(trans('frontOffice.reservation.roomTypeLabel')),
                Tables\Columns\TextColumn::make('reservation.total_price')
                    ->label(trans('frontOffice.reservation.totalPriceLabel'))
                    ->numeric(),
                Tables\Columns\TextColumn::make('method')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_by')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
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
