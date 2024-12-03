<?php

namespace App\Filament\FrontOffice\Resources;

use App\Enums\InvoiceStatus;
use App\Filament\FrontOffice\Resources\InvoiceResource\Pages;
use App\Filament\FrontOffice\Resources\InvoiceResource\RelationManagers;
use App\Models\Invoice;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use stdClass;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?int $navigationSort = 1;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.invoice.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['number'];
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
        return trans('frontOffice.invoice.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.invoice.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.invoice.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.invoice.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.invoice.title');
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('due_date'),
                Forms\Components\DateTimePicker::make('invoice_date')
                    ->required(),
                Forms\Components\TextInput::make('customer_details')
                    ->required(),
                Forms\Components\TextInput::make('payment_type')
                    ->required()
                    ->maxLength(255)
                    ->default('virtual_account'),
                Forms\Components\TextInput::make('item_details')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('virtual_accounts'),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('draft'),
                Forms\Components\TextInput::make('order_id'),
                Forms\Components\TextInput::make('number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_series')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('number_invoice')
                    ->numeric(),
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
                Tables\Columns\TextColumn::make('number')
                    ->weight(FontWeight::Bold)
                    ->icon('tabler-file-certificate')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('due_date')
                    ->date()
                    ->alignCenter()
                    ->label(trans('frontOffice.invoice.dueDateLabel'))
                    ->badge()
                    ->icon('tabler-calendar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->alignCenter()
                    ->label(trans('frontOffice.invoice.invoiceDateLabel'))
                    ->badge()
                    ->icon('tabler-calendar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->weight(FontWeight::Bold)
                    ->money(trans('frontOffice.reservation.priceCurrency'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_type')
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(trans('frontOffice.invoice.statusLabel'))
                    ->formatStateUsing(fn(string $state): string => InvoiceStatus::from($state)->label())
                    ->tooltip(fn(Model $record): string => InvoiceStatus::from($record->status)->description())
                    ->color(fn(string $state): string => InvoiceStatus::from($state)->color())
                    ->icon(fn(string $state): string => InvoiceStatus::from($state)->icon())
                    ->badge()
                    ->alignCenter()
                    ->formatStateUsing(fn(string $state): string => ucfirst($state))
                    ->searchable(),
                Tables\Columns\TextColumn::make('notes')
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_invoice')
                    ->label('View')
                    ->color('warning')
                    ->icon('tabler-file-search')
                    ->url(fn(Model $record): string => route('invoice.view', ['tenant' => Filament::getTenant(), 'record' => $record]))
                    ->openUrlInNewTab()
                    ->authorize(fn(Model $record): bool => auth()->user()->can('view_invoice', $record)),
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
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'view' => Pages\ViewInvoice::route('/{record}'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
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
