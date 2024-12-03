<?php

namespace App\Filament\FrontOffice\Resources;

use App\Enums\GuestStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Enums\ReservationSource;
use App\Enums\ReservationStatus;
use App\Enums\RoomStatus;
use App\Events\CancelReservationEvent;
use App\Events\GuestCheckinEvent;
use App\Events\GuestCheckoutEvent;
use App\Filament\FrontOffice\Resources\ReservationResource\Pages;
use App\Filament\FrontOffice\Resources\ReservationResource\Pages\CreateReservation;
use App\Filament\FrontOffice\Resources\ReservationResource\Pages\EditReservation;
use App\Filament\FrontOffice\Resources\ReservationResource\RelationManagers;
use App\Filament\FrontOffice\Resources\RoomResource\Pages\ManageReservations;
use App\Models\AdditionalFacility;
use App\Models\Guest;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use App\Policies\ReservationPolicy;
use App\States\Guest\CheckedIn as GuestCheckedIn;
use App\States\Guest\CheckedOut as GuestCheckedOut;
use App\States\Reservation\CheckedIn;
use App\States\Reservation\CheckedOut;
use Carbon\Carbon;
use Closure;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists\Infolist;
use Filament\Infolists;
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
use Filament\Notifications;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Enums\IconSize;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Wizard\Step;
use function Filament\authorize;
use Illuminate\Contracts\View\View;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;
    protected static ?int $navigationSort = 1;
    public static function getNavigationIcon(): string|Htmlable|null
    {
        return trans('frontOffice.reservation.icon');
    }
    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery();
    }
    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'check_in', 'check_out'];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    public static function getGlobalSearchResultDetails(Model $record): array
    {

        return [
            'Room Type' => $record->room->roomType->type,
            'Price' => trans('frontOffice.room.pricePrefix') . ' ' . $record->price,
            'Status' => $record->status
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
        return trans('frontOffice.reservation.label');
    }

    public static function getPluralLabel(): string
    {
        return trans('frontOffice.reservation.pluralLabel');
    }

    public static function getLabel(): string
    {
        return trans('frontOffice.reservation.single');
    }

    public static function getNavigationGroup(): ?string
    {
        return trans('frontOffice.reservation.group');
    }

    public function getTitle(): string
    {
        return trans('frontOffice.reservation.title');
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(3)
                    ->schema([
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Guest Details')
                                ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
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
                                    Forms\Components\Textarea::make('notes')
                                        ->label(trans('frontOffice.reservation.notesLabel')),
                                ]),
                        ]),
                        Forms\Components\Group::make([
                            Forms\Components\Section::make('Reservation Details')

                                ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
                                ->icon('tabler-file-text')
                                ->columns(2)
                                ->schema([
                                    \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('reservation_source')->required()
                                        ->columnSpanFull()
                                        ->label(trans('frontOffice.reservation.reservationSourceLabel'))
                                        ->columns(2)
                                        ->descriptions(collect(ReservationSource::cases())->mapWithKeys(fn($description) => [
                                            $description->value => $description->description(),
                                        ])->toArray())
                                        ->icons(collect(ReservationSource::cases())->mapWithKeys(fn($icon) => [
                                            $icon->value => $icon->icon(),
                                        ])->toArray())
                                        ->options(collect(ReservationSource::cases())->mapWithKeys(fn($source) => [
                                            $source->value => $source->label(),
                                        ])->toArray())
                                        ->color('primary'),
                                    Forms\Components\Select::make('room_type')
                                        ->columnSpanFull()
                                        ->label(trans('frontOffice.reservation.roomTypeLabel'))
                                        ->options(collect(RoomType::all())->mapWithKeys(fn($roomType) => [
                                            $roomType->id => '<strong>' . $roomType->name . '</strong><br>' . $roomType->description ?? '-',
                                        ])->toArray())
                                        ->searchable()
                                        ->native(false)
                                        ->required()
                                        ->allowHtml()
                                        ->live(),
                                    Forms\Components\Section::make('Select Room')

                                        ->visible(fn(Get $get): bool => $get('room_type') !== null)
                                        ->schema([
                                            // Forms\Components\Placeholder::make('price')
                                            //     ->label(trans('frontOffice.reservation.priceLabel'))
                                            //     ->content(fn(Get $get): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format(Room::find($get('room_id'))->price, 2) . '/night')
                                            //     ->visible(fn(Get $get): bool => $get('room_id') !== null),
                                            \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('room_id')
                                                ->live()
                                                ->hiddenLabel()
                                                ->columns(4)
                                                ->visible(fn(Get $get): bool => $get('room_type') !== null)
                                                ->descriptions(function (Get $get) {
                                                    $rooms = Room::where('room_type_id', $get('room_type'))->where('is_available', true)->get();
                                                    return $rooms->mapWithKeys(function ($status) {
                                                        return [$status->id => trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($status->price, 2) . '/night'];
                                                    })->toArray();
                                                })
                                                ->icons(function (Get $get) {
                                                    $rooms = Room::where('room_type_id', $get('room_type'))->where('is_available', true)->get();
                                                    return $rooms->mapWithKeys(function ($status) {
                                                        return [$status->id => 'tabler-door'];
                                                    })->toArray();
                                                })
                                                ->options(function (Get $get) {
                                                    $rooms = Room::availableBetween($get('check_in'), $get('check_out'), $get('room_type'))->get();
                                                    return $rooms->mapWithKeys(fn($status) => [
                                                        $status->id => $status->name . ' (Available)',
                                                    ])->toArray();
                                                })
                                                ->color('primary'),
                                        ]),
                                ]),
                        ])->columnSpan(2),
                    ])->hiddenOn(ManageReservations::class),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Guest Details')
                        ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
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
                            Forms\Components\Textarea::make('notes')
                                ->label(trans('frontOffice.reservation.notesLabel')),
                        ]),
                    Forms\Components\Section::make('Reservation Schedule & Status')
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
                                }),



                        ]),
                    Forms\Components\Section::make('Additional Facilities')
                        ->description('This section provides a detailed view of the additional facilities provided by the hotel.')
                        ->icon('tabler-files')
                        ->columns(4)
                        ->schema([
                            //         Forms\Components\Repeater::make('additionalFacilities')
                            //             ->label(trans('frontOffice.reservation.additionalFacilitiesLabel'))
                            //             ->relationship()
                            //             ->schema([
                            //                 Forms\Components\Select::make('additional_facility_id')
                            //                     ->live()
                            //                     ->relationship('facility', 'name')
                            //                     ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            //                         $additional = AdditionalFacility::find($state);
                            //                         $set('unit', $additional->unit);
                            //                         $set('price', $additional->price);
                            //                     }),
                            //                 Forms\Components\TextInput::make('quantity')
                            //                     ->label(trans('frontOffice.reservation.quantityLabel'))
                            //                     ->required(),
                            //                 Forms\Components\Select::make('unit')
                            //                     ->label(trans('frontOffice.reservation.unitLabel')),

                            //             ])
                            \Awcodes\TableRepeater\Components\TableRepeater::make('additionalFacilities')
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
                                        ->live()
                                        ->relationship('facility', 'name')
                                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                                            $additional = AdditionalFacility::find($state);
                                            $set('unit', $additional->unit);
                                            $set('price', $additional->price);
                                        }),
                                    Forms\Components\TextInput::make('quantity')
                                        ->live()

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
                                ->columnSpan('full'),
                        ]),
                ])->hiddenOn([CreateReservation::class, EditReservation::class]),
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Invoice Details')
                        ->description('This section provides a detailed view of the invoice details.')
                        ->icon('tabler-file-invoice')
                        ->columns(4)
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->prefix(trans('frontOffice.reservation.priceCurrency'))
                                ->live()
                                ->readOnly()
                                ->label(trans('frontOffice.reservation.totalPriceLabel'))
                                ->suffixAction(
                                    Forms\Components\Actions\Action::make('calculate')
                                        ->label('Calculate')
                                        ->color('primary')
                                        ->icon('tabler-calculator')
                                        ->action(function (Set $set, Get $get, ManageReservations|CreateReservation $livewire) {
                                            $room = null;
                                            if ($livewire->record) {
                                                $room = $livewire->getOwnerRecord();
                                            }
                                            if ($get('room_id') !== null) {
                                                $room = Room::find($get('room_id'));
                                            }
                                            $totalNights = number_format(Carbon::parse($get('check_in'))->diffInDays(Carbon::parse($get('check_out'))), 0);
                                            $set('price', $room ? $room->price * $totalNights : 0);
                                        }),
                                ),
                            Forms\Components\Placeholder::make('total_nights')
                                ->content(fn(Get $get): string => number_format(Carbon::parse($get('check_in'))->diffInDays(Carbon::parse($get('check_out'))), 0))
                        ]),
                ]),

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
                    ->copyable()
                    ->icon('tabler-file-certificate')
                    ->badge()
                    ->weight(FontWeight::Bold)
                    ->label(trans('frontOffice.reservation.numberLabel'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->color('success')
                    ->weight(FontWeight::SemiBold)
                    ->icon('tabler-door')
                    ->label(trans('frontOffice.room.nameLabel'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.roomType.name')
                    ->limit(25)
                    ->tooltip(fn(Model $record): string => $record->room->roomType->description)
                    ->description(function (Model $record, Tables\Columns\TextColumn $column): string {
                        $state = $record->room->roomType->description;

                        if (strlen($state) >= $column->getCharacterLimit()) {
                            return substr($state, 0, $column->getCharacterLimit()) . '...';
                        }
                        return $state;
                    })
                    ->weight(FontWeight::SemiBold)
                    ->icon('tabler-files')
                    ->label(trans('frontOffice.room.typeLabel'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guest.name')
                    ->copyable()
                    ->copyMessage('Phone copied sucessfully')
                    ->tooltip(fn(Model $record): string => 'Phone : ' . $record->guest->phone)
                    ->description(fn(Model $record): string => $record->guest->email ?? '-')
                    ->icon('tabler-user')
                    ->weight(FontWeight::SemiBold)
                    ->label(trans('frontOffice.reservation.guestNameLabel'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('check_in')
                    ->date()
                    ->alignCenter()
                    ->label(trans('frontOffice.reservation.checkInLabel'))
                    ->badge()
                    ->icon('tabler-calendar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('check_out')
                    ->alignCenter()
                    ->label(trans('frontOffice.reservation.checkOutLabel'))
                    ->badge()
                    ->icon('tabler-calendar')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('estimate_arrival')
                    ->color('danger')
                    ->time('H:i A')
                    ->alignCenter()
                    ->label(trans('frontOffice.reservation.estimateArrivalLabel'))
                    ->icon('tabler-clock')
                    ->sortable(),
                Tables\Columns\TextColumn::make('state')
                    ->label(trans('frontOffice.reservation.statusLabel'))
                    ->formatStateUsing(fn(Model $record): string => $record->state->label())
                    ->tooltip(fn(Model $record): string => $record->state->description())
                    ->color(fn(Model $record): string => $record->state->color())
                    ->icon(fn(Model $record): string => $record->state->icon())
                    ->badge()
                    ->alignCenter(),
                // Tables\Columns\TextColumn::make('status')
                //     ->label(trans('frontOffice.reservation.statusLabel'))
                //     ->formatStateUsing(fn(string $state): string => ReservationStatus::from($state)->label())
                //     ->tooltip(fn(Model $record): string => ReservationStatus::from($record->status)->description())
                //     ->color(fn(string $state): string => ReservationStatus::from($state)->color())
                //     ->icon(fn(string $state): string => ReservationStatus::from($state)->icon())
                //     ->badge()
                //     ->alignCenter(),
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
                Tables\Filters\SelectFilter::make('status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('cancel')
                        ->modalHeading('Cancel Reservation')
                        ->modalDescription('Are you sure you want to cancel this reservation?')
                        ->modalSubmitActionLabel('Yes, cancel it')
                        ->modalIcon('tabler-exclamation')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('tabler-x')
                        ->form([
                            Forms\Components\Section::make()

                                ->schema([
                                    Forms\Components\Toggle::make('is_refund')
                                        ->visible(fn(Reservation $record): bool => $record->is_completed_payment === true)
                                        ->inline()
                                        ->label(trans('frontOffice.reservation.refundLabel'))
                                        ->default(false)
                                        ->onIcon('tabler-check')
                                        ->offIcon('tabler-x')
                                        ->onColor('primary')
                                        ->offColor('danger'),
                                    Forms\Components\Textarea::make('reason')
                                        ->label(trans('frontOffice.reservation.reasonLabel'))
                                        ->required(),
                                ]),



                        ])
                        ->action(function (array $data, Reservation $record) {
                            event(new CancelReservationEvent($record, $data, auth()->user()));
                        })
                        ->authorize(fn(Reservation $record): bool => auth()->user()->can('cancelReservation', $record)),
                    Tables\Actions\Action::make('view_payments')
                        ->color('warning')
                        ->icon('tabler-cash')
                        ->url(fn(Reservation $record): string => route('filament.frontOffice.resources.reservations.managePayments', ['tenant' => Filament::getTenant(), 'record' => $record])),
                    Tables\Actions\Action::make('extend')
                        ->visible(fn(Reservation $record): bool => $record->guest_status === GuestStatus::CHECKIN->value)
                        ->icon('tabler-calendar-plus')
                        ->color('primary')
                        ->requiresConfirmation()
                        ->form([
                            Forms\Components\DatePicker::make('extend_date')
                                ->prefixIcon('tabler-calendar')
                                ->prefixIconColor('primary')
                                ->default(fn(Reservation $record): string =>  $record->check_out)
                                ->minDate(fn(Reservation $record): string =>  $record->check_out)
                        ])
                        ->action(function (array $data, Reservation $record) {
                            $record->extend($data['extend_date']);
                            $record->is_completed_payment = false;
                            $record->save();
                        })
                        ->authorize(fn(Reservation $record): bool => auth()->user()->can('extendReservation', $record)),
                    Tables\Actions\Action::make('check_in')
                        ->visible(fn(Model $record): bool => $record->state->canTransitionTo(CheckedIn::class) && $record->guest_status->canTransitionTo(GuestCheckedIn::class))
                        ->requiresConfirmation()
                        ->modalHeading('Check In')
                        ->modalDescription('Are you sure you want to check in this reservation?')
                        ->modalSubmitActionLabel('Yes, check in it')
                        ->modalIcon('tabler-door-enter')
                        ->icon('tabler-door-enter')
                        ->color('primary')
                        ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                        ->fillForm(fn(Reservation $record): array => [
                            'guest_check_in_at' => $record->check_in,
                            'payments' => $record->payments,
                            'price' => $record->price,
                        ])
                        ->form([
                            // Forms\Components\Section::make('Check In Details')
                            //     ->description('Please provide the necessary information to check in this reservation. Make sure to fill in all required fields.')
                            //     ->icon('tabler-file-text')
                            //     ->schema([
                            // Forms\Components\DateTimePicker::make('guest_check_in_at')
                            //     ->native(false)
                            //     ->default(fn(Reservation $record): string =>  $record->check_in)
                            //     ->minDate(fn(Reservation $record): string =>  $record->check_in)
                            //     ->maxDate(fn(Reservation $record): string =>  $record->check_out)
                            //     ->label(trans('frontOffice.reservation.checkInLabel'))
                            //     ->prefixIcon('tabler-calendar')
                            //     ->prefixIconColor('primary')
                            //     ->required(),
                            \App\Forms\Components\RoomDetail::make('room_detail'),




                            // \Awcodes\TableRepeater\Components\TableRepeater::make('payments')
                            //     ->headers([
                            //         \Awcodes\TableRepeater\Header::make('amount')->align('center'),
                            //         \Awcodes\TableRepeater\Header::make('status')->align('center'),
                            //         \Awcodes\TableRepeater\Header::make('method')->align('center'),
                            //         \Awcodes\TableRepeater\Header::make('type')->align('center'),
                            //         \Awcodes\TableRepeater\Header::make('%')->align('center'),
                            //     ])
                            //     ->schema([
                            //         Forms\Components\TextInput::make('amount')
                            //             ->live()
                            //             ->maxValue(fn(Get $get): ?int => $get('../../total_price'))
                            //             ->required()
                            //             ->numeric()
                            //             ->prefix(trans('frontOffice.reservation.priceCurrency')),
                            //         Forms\Components\Select::make('status')
                            //             ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                            //                 $status->value => $status->label(),
                            //             ])->toArray())
                            //             ->required(),
                            //         Forms\Components\Select::make('method')

                            //             ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                            //                 $status->value => $status->label(),
                            //             ])->toArray())
                            //             ->required(),
                            //         Forms\Components\Select::make('type')

                            //             ->options(collect(PaymentType::cases())->mapWithKeys(fn($status) => [
                            //                 $status->value => $status->label(),
                            //             ])->toArray())
                            //             ->required(),
                            //         Forms\Components\Placeholder::make('percentage')
                            //             ->hiddenLabel()
                            //             ->content(fn(Get $get): string => number_format(($get('amount') / $get('../../total_price'))  * 100, 2) . '%'),
                            //     ])
                            //     ->columnSpan('full'),

                        ])
                        ->action(function (array $data, Reservation $record): void {
                            event(new GuestCheckinEvent($data, $record, auth()->user()));
                        })->modalWidth(MaxWidth::FiveExtraLarge)
                        ->authorize(fn(Reservation $record): bool => auth()->user()->can('checkInReservation', $record)),
                    Tables\Actions\Action::make('check_out')
                        ->slideOver()
                        ->visible(fn(Model $record): bool => $record->state->canTransitionTo(CheckedOut::class) && $record->guest_status->canTransitionTo(GuestCheckedOut::class))
                        ->requiresConfirmation()
                        ->modalHeading('Check Out')
                        ->modalDescription('Are you sure you want to check out this reservation?')
                        ->modalSubmitActionLabel('Yes, check out it')
                        ->modalIcon('tabler-door-exit')
                        ->icon('tabler-door-exit')
                        ->color('danger')
                        ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                        ->fillForm(fn(Reservation $record): array => [
                            'reservation_id' => $record->id,
                            'guest_check_out_at' => $record->check_out,
                            'payments' => $record->payments,
                            'price' => $record->price,
                        ])
                        ->modalContent(fn(Reservation $record): View => view(
                            'components.check-out',
                            ['record' => $record],
                        ))
                        ->form([
                            Forms\Components\Textarea::make('feedback'),
                        ])
                        ->action(function (array $data, Reservation $record): void {
                            event(new GuestCheckoutEvent($data, $record, auth()->user()));
                        })->modalWidth(MaxWidth::FiveExtraLarge)
                        ->authorize(fn(Reservation $record): bool => auth()->user()->can('checkOutReservation', $record)),
                    Tables\Actions\ViewAction::make()
                        ->modalWidth(MaxWidth::FitContent),
                    Tables\Actions\EditAction::make()
                        ->modalWidth(MaxWidth::FitContent)
                        ->color('primary'),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                \CodeWithDennis\SimpleAlert\Components\Infolists\SimpleAlert::make('example')
                    ->visible(fn(Model $record): bool => $record->status === 'pending')
                    ->icon(fn(Model $record): string => ReservationStatus::from($record->status)->icon())
                    ->color(fn(Model $record): string => ReservationStatus::from($record->status)->color())
                    ->columnSpanFull()
                    ->title(fn(Model $record): string => ReservationStatus::from($record->status)->label() . ' Reservation!')
                    ->description(fn(Model $record): string => ReservationStatus::from($record->status)->description()),

                Infolists\Components\Group::make([
                    Infolists\Components\Section::make('Reservation Details')
                        ->description('This section provides comprehensive information about each reservation, allowing hotel staff to view and manage booking details with ease.')
                        ->icon('tabler-file-text')

                        ->schema([
                            Infolists\Components\Fieldset::make('Room Details')
                                ->columns([
                                    'xl' => 3,
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('room.name')
                                        ->weight(FontWeight::SemiBold)
                                        ->label(trans('frontOffice.reservation.roomNameLabel'))
                                        ->icon('tabler-door')
                                        ->iconColor('primary'),
                                    Infolists\Components\TextEntry::make('room.type')
                                        ->label(trans('frontOffice.reservation.roomTypeLabel'))
                                        ->icon('tabler-files')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),
                                    Infolists\Components\TextEntry::make('room.price')
                                        ->money(trans('frontOffice.reservation.priceCurrency'))
                                        ->label(trans('frontOffice.reservation.roomPriceLabel'))
                                        ->icon('tabler-file-invoice')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),
                                ]),
                            Infolists\Components\Fieldset::make('Guest Details')
                                ->columns([
                                    'xl' => 3,
                                ])
                                ->schema([
                                    Infolists\Components\TextEntry::make('guest.identity_number')
                                        ->label(trans('frontOffice.reservation.guestIdentityNumberLabel'))
                                        ->icon('tabler-id')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),
                                    Infolists\Components\TextEntry::make('guest.name')
                                        ->label(trans('frontOffice.reservation.guestNameLabel'))
                                        ->icon('tabler-pencil')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),
                                    Infolists\Components\TextEntry::make('guest.phone')
                                        ->label(trans('frontOffice.reservation.guestPhoneLabel'))
                                        ->icon('tabler-phone')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),
                                    Infolists\Components\TextEntry::make('guest.email')
                                        ->label(trans('frontOffice.reservation.guestEmailLabel'))
                                        ->icon('tabler-mail')
                                        ->iconColor('primary')
                                        ->weight(FontWeight::SemiBold),

                                ]),
                        ])->grow(false),
                ]),

                Infolists\Components\Group::make([
                    Infolists\Components\Section::make('Reservation Schedule & Status')
                        ->icon('tabler-calendar-clock')
                        ->description('This section provides a detailed view of the reservation schedule and status.')
                        ->columns([
                            'xl' => 3
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('check_in')
                                ->weight(FontWeight::Bold)
                                ->color('danger')
                                ->icon('tabler-calendar')
                                ->iconColor('danger')
                                ->date()
                                ->label(trans('frontOffice.reservation.checkInLabel')),
                            Infolists\Components\TextEntry::make('check_out')
                                ->weight(FontWeight::Bold)
                                ->color('danger')
                                ->icon('tabler-calendar')
                                ->iconColor('danger')
                                ->date()
                                ->label(trans('frontOffice.reservation.checkOutLabel')),
                            Infolists\Components\TextEntry::make('estimate_arrival')
                                ->weight(FontWeight::Bold)
                                ->color('danger')
                                ->icon('tabler-clock')
                                ->iconColor('danger')
                                ->time()
                                ->label(trans('frontOffice.reservation.estimateArrivalLabel')),
                            Infolists\Components\TextEntry::make('state')
                                ->label(trans('frontOffice.reservation.statusLabel'))
                                ->formatStateUsing(fn(Model $record): string => $record->state->label())
                                ->tooltip(fn(Model $record): string => $record->state->description())
                                ->color(fn(Model $record): string => $record->state->color())
                                ->icon(fn(Model $record): string => $record->state->icon())
                                ->badge(),
                            // Infolists\Components\TextEntry::make('status')
                            //     ->formatStateUsing(fn(string $state): string => ReservationStatus::from($state)->label())
                            //     ->tooltip(fn(Model $record): string => ReservationStatus::from($record->status)->description())
                            //     ->color(fn(string $state): string => ReservationStatus::from($state)->color())
                            //     ->icon(fn(string $state): string => ReservationStatus::from($state)->icon())
                            //     ->badge()
                            //     ->label(trans('frontOffice.reservation.statusLabel')),
                        ])->grow(false),
                    Infolists\Components\Section::make('Reservation Invoice')
                        ->description('This section provides comprehensive information about invoice.')
                        ->icon('tabler-file-dollar')
                        ->columns([
                            'sm' => 2,
                            'xl' => 3,
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('price')
                                ->weight(FontWeight::Bold)
                                ->color('primary')
                                ->icon('tabler-file-invoice')
                                ->iconColor('primary')
                                ->label(trans('frontOffice.reservation.totalPriceLabel'))
                                ->money(trans('frontOffice.reservation.priceCurrency')),
                            Infolists\Components\TextEntry::make('total_nights')
                                ->formatStateUsing(fn(string $state): string => __("{$state} nights"))
                                ->weight(FontWeight::Bold)
                                ->icon('tabler-calendar-plus')
                                ->iconColor('primary')
                                ->color('primary')
                                ->label(trans('frontOffice.reservation.totalNightsLabel'))

                        ])->headerActions([
                            Infolists\Components\Actions\Action::make('view_invoice')
                                ->visible(fn(Model $record): bool => auth()->user()->can('view_reservation_invoice') && $record->has_payment)
                                ->label(trans('frontOffice.reservation.viewInvoiceLabel'))
                                ->icon('tabler-file-search')
                                ->action(function () {
                                    // ...
                                }),
                        ]),
                ]),


            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ManageInvoices::class,
            Pages\ManagePayments::class,
            Pages\EditReservation::class,
            Pages\ViewReservation::class,
        ]);
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReservations::route('/'),
            'create' => Pages\CreateReservation::route('/create'),
            'view' => Pages\ViewReservation::route('/{record}'),
            'edit' => Pages\EditReservation::route('/{record}/edit'),
            'managePayments' => Pages\ManagePayments::route('/{record}/manage-payments'),
            'manageInvoices' => Pages\ManageInvoices::route('/{record}/manage-invoices'),
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
