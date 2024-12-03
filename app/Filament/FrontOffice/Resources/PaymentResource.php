<?php

namespace App\Filament\FrontOffice\Resources;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Events\PaidPaymentEvent;
use App\Events\PayPaymentEvent;
use App\Filament\FrontOffice\Resources\PaymentResource\Pages;
use App\Filament\FrontOffice\Resources\PaymentResource\RelationManagers;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Reservation;
use App\States\Payment\Paid;
use App\States\Payment\Pending;
use App\States\Reservation\Confirmed;
use Carbon\Carbon;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Browsershot\Browsershot;
use stdClass;
use function Spatie\LaravelPdf\Support\pdf;
use Illuminate\Database\Query\Builder as QueryBuilder;

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
            'status' => ucfirst($record->state)
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
                    ->searchable('number')
                    ->required(),
                Forms\Components\TextInput::make('method')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                // Forms\Components\Select::make('status')
                //     ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                //         $status->value => $status->label(),
                //     ])->toArray()),
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
                    ->color('success')
                    ->copyable()
                    ->icon('tabler-file-certificate')
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->label(trans('frontOffice.payment.numberLabel')),
                // Tables\Columns\TextColumn::make('reservation.number')
                //     ->copyable()
                //     ->icon('tabler-file-certificate')
                //     ->badge()
                //     ->weight(FontWeight::Bold)
                //     ->label(trans('frontOffice.payment.reservationNumberLabel')),
                // Tables\Columns\TextColumn::make('invoice.number')
                //     ->copyable()
                //     ->icon('tabler-file-certificate')
                //     ->badge()
                //     ->weight(FontWeight::Bold)
                //     ->label(trans('frontOffice.payment.invoiceNumberLabel')),
                // Tables\Columns\TextColumn::make('reservation.room.roomType.name')
                //     ->label(trans('frontOffice.reservation.roomTypeLabel')),

                Tables\Columns\TextColumn::make('type')
                    ->label(trans('frontOffice.payment.typeLabel'))
                    ->badge()
                    ->color(fn(string $state): string => PaymentType::from($state)->color())
                    ->icon(fn(string $state): string => PaymentType::from($state)->icon())
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label(trans('frontOffice.payment.methodLabel'))
                    ->badge()
                    ->color(fn(string $state): string => PaymentMethod::from($state)->color())
                    ->icon(fn(string $state): string => PaymentMethod::from($state)->icon())
                    ->searchable(),
                Tables\Columns\TextColumn::make('total_amount')
                    ->weight(FontWeight::Bold)
                    ->color('info')
                    ->label(trans('frontOffice.reservation.totalPriceLabel'))
                    ->formatStateUsing(fn(string $state): string => trans('frontOffice.room.pricePrefix') . ' ' . number_format($state, 2)),
                Tables\Columns\TextColumn::make('paid_amount')

                    ->color('danger')
                    ->label(trans('frontOffice.payment.paidAmountLabel'))
                    ->formatStateUsing(fn(string $state): string => trans('frontOffice.room.pricePrefix') . ' ' . number_format($state, 2))
                    ->weight(FontWeight::Bold)
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->formatStateUsing(fn(Model $record): string => $record->state->label())
                    ->label(trans('frontOffice.payment.statusLabel'))
                    ->badge()
                    ->badge()
                    ->color(fn(Model $record): string => $record->state->color())
                    ->icon(fn(Model $record): string => $record->state->icon())
                    ->searchable(),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn(Model $record): bool => $record->state === Paid::class || $record->state !== Pending::class)
                    ->button()
                    ->authorize(fn(Model $record): bool => auth()->user()->can('update', $record)),
                Tables\Actions\Action::make('view_invoice')
                    // ->hidden(true)
                    ->modalHeading('')
                    ->slideOver()
                    ->button()
                    ->icon('tabler-file-search')
                    // ->action(function (Model $record) {
                    //     $record = $record->invoice;
                    //     return pdf()
                    //         ->view('filament.front-office.pages.view-invoice', compact('record'))
                    //         ->name('invoice-' . Carbon::now()->format('d-m-y') . '.pdf');
                    // return Browsershot::url(route('invoice.view', ['tenant' => Filament::getTenant(), 'record' => $record->invoice]))->save(storage_path('/app/public/reports/example.pdf'));
                    // return new Res($file, 200, array(
                    //     'Content-Type' => 'application/pdf',
                    //     'Content-Disposition' =>  'attachment; filename="ticket.pdf"'
                    // ));
                    // }),
                    // ->modalContent(fn(Model $record): View => view(
                    //     'filament.front-office.pages.view-invoice',
                    //     ['record' => $record->invoice],
                    // ))->modalSubmitAction(false)
                    // ->modalCloseButton(false)
                    // ->modalCancelAction(false),

                    ->url(function (Model $value) {

                        return route('invoice.view', ['tenant' => Filament::getTenant(), 'record' => $value->reservation->invoices->first()]);
                    })
                    ->openUrlInNewTab()
                    ->authorize(fn(Model $record): bool => auth()->user()->can('view_invoice', $record)),
                Tables\Actions\Action::make('pay')
                    ->visible(fn(Model $record): bool => $record->state->canTransitionTo(Paid::class))
                    ->button()
                    ->color('primary')
                    ->icon('tabler-cash')
                    ->requiresConfirmation()
                    ->fillForm(fn(Model $record): array => [
                        'paid_amount' => $record->paid_amount,
                    ])
                    ->form([
                        Forms\Components\Select::make('payment_method')
                            ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                $status->value => $status->label(),
                            ])->toArray())
                            ->required(),
                        Forms\Components\TextInput::make('paid_amount')
                            ->label('Paid Amount')
                            ->numeric()
                            ->inputMode('decimal')
                            ->required(),
                    ])
                    ->action(function (array $data, Model $record) {
                        $record->state->transitionTo(Paid::class);
                        $record->paid_at = Carbon::now();
                        $record->reservation->state->transitionTo(Confirmed::class);
                        $record->save();
                    })
                    ->authorize(fn(Model $record): bool => auth()->user()->can('paidPayment', $record)),
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
