<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Pages;

use App\Filament\FrontOffice\Resources\ReservationResource;
use App\Filament\FrontOffice\Resources\RoomResource;
use App\Models\AdditionalFacility;
use App\Models\Reservation;
use App\Models\Room;
use App\States\Reservation\Confirmed;
use App\States\Room\Occupied;
use App\States\Room\Reserved;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;
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
                    ->steps([
                        Forms\Components\Wizard\Step::make('Guest Details')
                            ->description('Please provide the necessary guest information for adding a new reservation. Make sure to fill in all required fields.')
                            ->icon('tabler-user')
                            ->schema([
                                Forms\Components\Select::make('guest_id')
                                    ->allowHtml()
                                    ->columnSpanFull()
                                    ->relationship('guest', 'name')
                                    ->loadingMessage('Loading guests...')
                                    ->searchable(['name', 'email', 'identity_number', 'phone'])
                                    ->searchPrompt('Search guest by their name, identity number, email or phone.')
                                    ->native(false)
                                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "<span class='text-primary-500 font-bold'>{$record->name}</span> <br>Email : {$record->email}<br>Phone : {$record->phone}<br>Address : {$record->address}")
                                    ->editOptionForm([
                                        Forms\Components\Section::make('Guest Details')
                                            ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
                                            ->icon('tabler-user')
                                            ->columns(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->label(trans('frontOffice.guest.nameLabel'))
                                                    ->required(),
                                                Forms\Components\TextInput::make('identity_number')
                                                    ->required()
                                                    ->label(trans('frontOffice.guest.identityNumberLabel')),
                                                Forms\Components\TextInput::make('email')
                                                    ->label(trans('frontOffice.guest.emailLabel'))
                                                    ->email(),
                                                Forms\Components\TextInput::make('phone')
                                                    ->label(trans('frontOffice.guest.phoneLabel')),

                                            ]),
                                    ])
                                    ->createOptionAction(
                                        fn(Forms\Components\Actions\Action $action) => $action
                                            ->label('Create Guest')
                                            ->modalHeading('Create Guest')
                                            ->modalDescription('Please provide the necessary information to add a new guest.')
                                            ->modalSubmitActionLabel('Add Guest')
                                            ->modalIcon('tabler-user')
                                            ->icon('tabler-user-plus')
                                            ->color('primary')
                                            ->modalWidth('3xl'),
                                    )
                                    ->createOptionForm([
                                        Forms\Components\Section::make()
                                            ->columns(2)
                                            ->schema([
                                                Forms\Components\TextInput::make('name')

                                                    ->label(trans('frontOffice.guest.nameLabel'))
                                                    ->prefixIcon('tabler-user')
                                                    ->prefixIconColor('primary')
                                                    ->required(),
                                                Forms\Components\TextInput::make('identity_number')
                                                    ->prefixIcon('tabler-id-badge-2')
                                                    ->prefixIconColor('primary')
                                                    ->required()
                                                    ->label(trans('frontOffice.guest.identityNumberLabel')),
                                                Forms\Components\TextInput::make('email')
                                                    ->prefixIcon('tabler-mail')
                                                    ->prefixIconColor('primary')
                                                    ->label(trans('frontOffice.guest.emailLabel'))
                                                    ->email(),
                                                Forms\Components\TextInput::make('phone')
                                                    ->prefixIcon('tabler-phone')
                                                    ->prefixIconColor('primary')
                                                    ->label(trans('frontOffice.guest.phoneLabel')),
                                                Forms\Components\RichEditor::make('address')
                                                    ->columnSpanFull()
                                                    ->label(trans('frontOffice.guest.addressLabel')),

                                            ]),

                                    ])
                                    ->required(),
                            ])
                            ->columns(2),
                        Forms\Components\Wizard\Step::make('Reservation Details')
                            ->description('This section provides a detailed view of the reservation schedule and status.')
                            ->icon('tabler-calendar-clock')
                            ->columns(3)
                            ->schema([
                                Forms\Components\DatePicker::make('check_in')
                                    ->live()
                                    ->default(now())
                                    ->native(false)
                                    ->prefixIcon('tabler-calendar')
                                    ->prefixIconColor('primary')
                                    ->live(onBlur: true),
                                Forms\Components\DatePicker::make('check_out')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (ManageReservations $livewire, Set $set, Get $get) {
                                        $room = $livewire->getOwnerRecord();
                                        $totalNights = number_format(Carbon::parse($get('check_in'))->diffInDays(Carbon::parse($get('check_out'))), 0);
                                        $set('price', $room->price * $totalNights);
                                        $set('total_nights', $totalNights);
                                    })
                                    ->default(now()->addDays(1))
                                    ->native(false)
                                    ->prefixIcon('tabler-calendar')
                                    ->prefixIconColor('primary')
                                    ->afterOrEqual('check_in')
                                    ->live()
                                    ->required(),
                                \HusamTariq\FilamentTimePicker\Forms\Components\TimePickerField::make('estimate_arrival')
                                    ->required()
                                    ->label(trans('frontOffice.reservation.estimateArrivalLabel'))
                                    ->afterStateUpdated(function (ManageReservations $livewire, Set $set, Get $get) {
                                        $room = $livewire->getOwnerRecord();
                                        $totalNights = number_format(Carbon::parse($get('check_in'))->diffInDays(Carbon::parse($get('check_out'))), 0);
                                        $set('price', $room->price * $totalNights);
                                        $set('total_nights', $totalNights);
                                    }),
                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull()
                                    ->autosize()
                                    ->label(trans('frontOffice.reservation.notesLabel')),
                            ]),
                        Forms\Components\Wizard\Step::make('Additional Facilities')
                            ->description('This section provides a detailed view of the additional facilities provided by the hotel.')
                            ->icon('tabler-files')
                            ->columns(4)
                            ->schema([
                                \Awcodes\TableRepeater\Components\TableRepeater::make('additionalFacilities')
                                    ->live()

                                    ->label(trans('frontOffice.reservation.additionalFacilitiesLabel'))
                                    ->relationship()
                                    ->headers([
                                        \Awcodes\TableRepeater\Header::make('facility')->width('300px')->align('center'),
                                        \Awcodes\TableRepeater\Header::make('quantity')->align('center'),
                                        \Awcodes\TableRepeater\Header::make('unit')->align('center'),
                                        \Awcodes\TableRepeater\Header::make('price')->align('center'),
                                        \Awcodes\TableRepeater\Header::make('total_price')->label('Total Price')->align('right'),

                                    ])
                                    ->schema([
                                        Forms\Components\Select::make('additional_facility_id')
                                            ->distinct()
                                            ->live()
                                            ->relationship('facility', 'name')
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $additional = AdditionalFacility::find($state);
                                                $set('unit', $additional->unit);
                                                $set('price', $additional->price);
                                            }),
                                        Forms\Components\TextInput::make('quantity')
                                            ->live()
                                            ->readOnly(fn(Get $get): bool => $get('additional_facility_id') === null)
                                            ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                                $set('total_price', 0);
                                                if ($state !== null) {
                                                    $additional = AdditionalFacility::find($get('additional_facility_id'));
                                                    $set('total_price', $additional->price * $state);
                                                }
                                            })
                                            ->label(trans('frontOffice.reservation.quantityLabel')),
                                        Forms\Components\TextInput::make('unit')
                                            ->readOnly(),
                                        Forms\Components\TextInput::make('price')
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->numeric()
                                            ->readOnly()
                                            ->label(trans('frontOffice.reservation.priceLabel')),
                                        Forms\Components\TextInput::make('total_price')
                                            ->mask(RawJs::make('$money($input)'))
                                            ->stripCharacters(',')
                                            ->numeric()
                                            ->readOnly(),
                                    ])
                                    ->columnSpanFull(),
                                Forms\Components\Section::make('Invoice Details')
                                    ->columns(4)
                                    ->headerActions([
                                        Forms\Components\Actions\Action::make('calculate')
                                            ->disabled(fn(Get $get): bool => $get('total_facilities') !== null)
                                            ->color('warning')
                                            ->icon('tabler-calculator')
                                            ->action(function (Get $get, Set $set) {
                                                $total_facilities = 0;
                                                foreach ($get('additionalFacilities') as $additionalFacility) {
                                                    if ($additionalFacility['total_price'] !== null) {
                                                        $total_facilities += $additionalFacility['total_price'];
                                                    }
                                                }
                                                $total_facilities = $get('total_nights') !== null ? $total_facilities * $get('total_nights') : 0;
                                                $set('total_facilities', $total_facilities);
                                                return $set('price', $get('price') + $total_facilities);
                                            }),
                                    ])
                                    ->schema([
                                        Forms\Components\TextInput::make('total_nights')
                                            ->disabled(),
                                        Forms\Components\TextInput::make('total_facilities')
                                            ->prefix(trans('frontOffice.reservation.priceCurrency'))
                                            ->live()
                                            ->disabled(),
                                        Forms\Components\TextInput::make('price')
                                            ->prefix(trans('frontOffice.reservation.priceCurrency'))
                                            ->live()
                                            ->readOnly()
                                            ->label(trans('frontOffice.reservation.totalPriceLabel'))
                                            ->suffixAction(
                                                Forms\Components\Actions\Action::make('calculate')
                                                    ->label('Calculate')
                                                    ->color('primary')
                                                    ->icon('tabler-calculator'),
                                                // ->action(function (Set $set, Get $get, ManageReservations $livewire) {
                                                //     $room = null;
                                                //     if ($livewire->record) {
                                                //         $room = $livewire->getOwnerRecord();
                                                //     }
                                                //     if ($get('room_id') !== null) {
                                                //         $room = Room::find($get('room_id'));
                                                //     }
                                                //     $totalNights = number_format(Carbon::parse($get('check_in'))->diffInDays(Carbon::parse($get('check_out'))), 0);
                                                //     $set('price', $room ? $room->price * $totalNights : 0);
                                                // }),
                                            ),
                                    ]),

                            ]),


                    ])
                    ->hidden(fn(ManageReservations $livewire): bool => $livewire->getOwnerRecord()->state->canTransitionTo(Occupied::class))
                    ->modalWidth(MaxWidth::SixExtraLarge),
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
