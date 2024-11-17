<?php

namespace App\Filament\FrontOffice\Resources;

use App\Enums\GuestStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Enums\ReservationSource;
use App\Enums\ReservationStatus;
use App\Filament\FrontOffice\Resources\ReservationResource\Pages;
use App\Filament\FrontOffice\Resources\ReservationResource\RelationManagers;
use App\Filament\FrontOffice\Resources\RoomResource\Pages\ManageReservations;
use App\Models\Guest;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\User;
use Carbon\Carbon;
use Closure;
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
use Filament\Support\Enums\IconSize;
use Filament\Support\RawJs;
use Illuminate\Database\Eloquent\Collection;

use function Filament\authorize;

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
            'Total Price' => trans('frontOffice.room.pricePrefix') . ' ' . $record->total_price,
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
                Forms\Components\Group::make([
                    Forms\Components\Section::make('Guest Details')
                        ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
                        ->icon('tabler-user')
                        ->columns(3)
                        ->schema([
                            \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('reservation_source')
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
                            Forms\Components\Select::make('guest_id')
                                ->columnSpan(2)
                                ->relationship('guest', 'name')
                                ->loadingMessage('Loading guests...')
                                ->searchable(['name', 'email', 'identity_number', 'phone'])
                                ->searchPrompt('Search guest by their name, identity number, email or phone.')
                                ->native(false)
                                ->getOptionLabelUsing(function ($value): ?string {
                                    $user = User::find($value);
                                    if ($user) {
                                        return '<strong class="text-primary-500">' . $user->name . '</strong> /';
                                    }
                                    return null;
                                })
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
                                ->required(),

                            Forms\Components\Placeholder::make('identity_number')
                                ->visible(fn(Get $get): bool => $get('guest_id') !== null)
                                ->content(fn(Get $get): string => Guest::find($get('guest_id'))->identity_number ?? '-')
                                ->label(trans('frontOffice.guest.identityNumberLabel')),
                            Forms\Components\Placeholder::make('email')
                                ->visible(fn(Get $get): bool => $get('guest_id') !== null)
                                ->content(fn(Get $get): string => Guest::find($get('guest_id'))->email ?? '-')
                                ->label(trans('frontOffice.guest.emailLabel')),
                            Forms\Components\Placeholder::make('phone')
                                ->visible(fn(Get $get): bool => $get('guest_id') !== null)
                                ->content(fn(Get $get): string => Guest::find($get('guest_id'))->phone ?? '-')
                                ->label(trans('frontOffice.guest.phoneLabel')),
                            Forms\Components\Placeholder::make('address')
                                ->visible(fn(Get $get): bool => $get('guest_id') !== null)
                                ->content(fn(Get $get): string => Guest::find($get('guest_id'))->address ?? '-')
                                ->label(trans('frontOffice.guest.addressLabel')),
                        ]),
                ]),
                Forms\Components\Group::make([
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
                                // ->disabledDates(function (Get $get) {
                                //     if ($get('room_id') === null) {
                                //         return [];
                                //     }

                                //     return Room::find($get('room_id'))->getUnavailableDates($get('check_in'));
                                // })
                                // ->rules([
                                //     fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                //         $check = Reservation::whereBetween('check_in', [Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'), Carbon::parse($get('check_out'))->format('Y-m-d H:i:s')])
                                //             ->orWhereBetween('check_out', [Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'), Carbon::parse($get('check_out'))->format('Y-m-d H:i:s')])
                                //             ->orWhere(fn($q) => $q->where('check_in', '<', Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'))->where('check_out', '>', Carbon::parse($get('check_out'))))
                                //             ->exists();
                                //         if ($check) {
                                //             $fail("The date is not available or already booked.");
                                //         }
                                //     },
                                // ])
                                ->live(),
                            Forms\Components\DatePicker::make('check_out')
                                ->live()
                                // ->disabledDates(function (Get $get) {
                                //     if ($get('room_id') === null) {
                                //         return [];
                                //     }

                                //     return Room::find($get('room_id'))->getUnavailableDates($get('check_in'));
                                // })
                                ->default(now()->addDays(1))
                                ->native(false)
                                ->prefixIcon('tabler-calendar')
                                ->prefixIconColor('primary')
                                ->afterOrEqual('check_in')
                                // ->rules([
                                //     fn(Get $get): Closure => function (string $attribute, $value, Closure $fail) use ($get) {
                                //         $check = Reservation::whereBetween('check_in', [Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'), Carbon::parse($get('check_out'))->format('Y-m-d H:i:s')])
                                //             ->orWhereBetween('check_out', [Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'), Carbon::parse($get('check_out'))->format('Y-m-d H:i:s')])
                                //             ->orWhere(fn($q) => $q->where('check_in', '<', Carbon::parse($get('check_in'))->format('Y-m-d H:i:s'))->where('check_out', '>', Carbon::parse($get('check_out'))))
                                //             ->exists();
                                //         if ($check) {
                                //             $fail("The date is not available or already booked.");
                                //         }
                                //     },
                                // ])
                                ->live()
                                ->required(),
                            Forms\Components\TimePicker::make('estimate_arrival')
                                ->prefixIcon('tabler-clock')
                                ->prefixIconColor('primary')
                                ->label(trans('frontOffice.reservation.estimateArrivalLabel')),
                            Forms\Components\Select::make('status')
                                ->hiddenOn('create')
                                ->label(trans('frontOffice.reservation.statusLabel'))
                                ->options(collect(ReservationStatus::cases())->mapWithKeys(fn($status) => [
                                    $status->value => $status->label(),
                                ])->toArray())
                                ->allowHtml()
                                ->native(false),
                        ]),
                ]),
                Forms\Components\Section::make('Reservation Details')
                    ->hiddenOn(ManageReservations::class)
                    ->aside()
                    ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
                    ->icon('tabler-file-text')
                    ->columns(2)
                    ->schema([

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
                                        //$rooms = Room::where('room_type_id', $get('room_type'))->where('is_available', true)->get();
                                        return $rooms->mapWithKeys(fn($status) => [
                                            $status->id => $status->name . ' (Available)',
                                        ])->toArray();
                                    })
                                    ->color('primary'),
                            ]),
                        // Forms\Components\Select::make('room_id')
                        //     ->visible(fn(Get $get): bool => $get('room_type') !== null)
                        //     ->label(trans('frontOffice.reservation.roomNameLabel'))
                        //     ->relationship('room', 'name', modifyQueryUsing: fn(Get $get, Builder $query): Builder => $query->where('room_type_id', $get('room_type'))->where('is_available', true))
                        //     ->allowHtml()
                        //     ->native(false)
                        //     ->required()
                        //     ->live(),






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
                    ->description(fn(Model $record): string => $record->guest->email ?? '-')
                    ->icon('tabler-user')
                    ->weight(FontWeight::SemiBold)
                    ->label(trans('frontOffice.reservation.guestNameLabel'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('guest.phone')
                    ->icon('tabler-phone')
                    ->label(trans('frontOffice.reservation.guestPhoneLabel'))
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
                // Tables\Columns\SelectColumn::make('status')
                //     ->tooltip(fn(Model $record): string => ReservationStatus::from($record->status)->description())
                //     ->options(collect(ReservationStatus::cases())->mapWithKeys(fn($status) => [
                //         $status->value => $status->label(),
                //     ])->toArray())
                //     ->selectablePlaceholder(false),
                Tables\Columns\TextColumn::make('status')
                    ->label(trans('frontOffice.reservation.statusLabel'))
                    ->formatStateUsing(fn(string $state): string => ReservationStatus::from($state)->label())
                    ->tooltip(fn(Model $record): string => ReservationStatus::from($record->status)->description())
                    ->color(fn(string $state): string => ReservationStatus::from($state)->color())
                    ->icon(fn(string $state): string => ReservationStatus::from($state)->icon())
                    ->badge()
                    ->alignCenter(),
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
                Tables\Actions\Action::make('check_in')
                    ->visible(fn(Reservation $record): bool => $record->status === ReservationStatus::PENDING->value && $record->guest_status === GuestStatus::PENDING->value)
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Check In')
                    ->modalDescription('Are you sure you want to check in this reservation?')
                    ->modalSubmitActionLabel('Yes, check in it')
                    ->modalIcon('tabler-door-enter')
                    ->icon('tabler-door-enter')
                    ->color('primary')
                    ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                    ->fillForm(fn(Reservation $record): array => [
                        'has_payment' => $record->has_payment,
                        'price' => $record->price,
                        'total_price' => $record->total_price,
                        'status' => ReservationStatus::from($record->status)->value,
                    ])
                    ->form([
                        Forms\Components\Section::make('Check In Details')
                            ->description('Please provide the necessary information to check in this reservation. Make sure to fill in all required fields.')
                            ->icon('tabler-file-text')
                            ->schema([
                                Forms\Components\DateTimePicker::make('guest_check_in_at')
                                    ->native(false)
                                    ->default(Carbon::now())
                                    ->format('Y-m-d H:i:s')
                                    ->displayFormat('Y-m-d H:i:s')
                                    ->minDate(fn(Reservation $record): string =>  $record->check_in)
                                    ->maxDate(fn(Reservation $record): string =>  $record->check_out)
                                    ->label(trans('frontOffice.reservation.checkInLabel'))
                                    ->prefixIcon('tabler-calendar')
                                    ->prefixIconColor('primary')
                                    ->required(),
                                Forms\Components\Section::make('Room Details')
                                    ->columns(4)
                                    ->schema([
                                        Forms\Components\Placeholder::make('room')
                                            ->label(trans('frontOffice.reservation.roomNameLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->room->name),
                                        Forms\Components\Placeholder::make('room_type')
                                            ->label(trans('frontOffice.reservation.roomTypeLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->room->roomType->name),
                                        Forms\Components\Placeholder::make('room')
                                            ->label(trans('frontOffice.reservation.totalNightsLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->total_nights),
                                        Forms\Components\Placeholder::make('price')
                                            ->label(trans('frontOffice.reservation.totalPriceLabel'))
                                            ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->total_price, 2)),
                                        Forms\Components\Placeholder::make('price_pernight')
                                            ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->room->price, 2)),

                                    ]),

                            ])->columns(2),


                        Forms\Components\Repeater::make('payments')
                            ->collapsible()
                            ->columnSpanFull()
                            ->relationship('payments')
                            ->columns(4)
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->maxValue(fn(Get $get): ?int => $get('../../total_price'))
                                    ->required()
                                    ->numeric()
                                    ->prefix(trans('frontOffice.reservation.priceCurrency')),

                                \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('status')
                                    ->columnSpan(3)
                                    ->columns(4)
                                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->label(),
                                    ])->toArray())

                                    ->icons(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->icon(),
                                    ])->toArray())
                                    ->iconSize(IconSize::Small)
                                    ->color('primary')
                                    ->required(),
                                \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('method')
                                    ->columnSpanFull()
                                    ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->label(),
                                    ])->toArray())
                                    ->descriptions(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->description(),
                                    ])->toArray())
                                    ->icons(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->icon(),
                                    ])->toArray())
                                    ->iconSize(IconSize::Large)
                                    ->color('primary')
                                    ->required(),
                            ])->minItems(1)
                            ->defaultItems(1)
                            ->itemLabel(fn(array $state): ?string => $state['method'] . ' - ' . trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($state['amount'], 2) . ' / ' . $state['status'])
                    ])
                    ->action(function (array $data, Reservation $record): void {

                        $record->status = ReservationStatus::CONFIRMED->value;
                        $record->has_payment = true;
                        $record->checked_in_by = auth()->user()->id;
                        $record->guest_status = GuestStatus::CHECKIN->value;
                        if ($record->guest_check_in_at !== null) {
                            $record->guest_check_in_at = now();
                        }

                        $record->save();
                        if ($record->save()) {
                            Notifications\Notification::make()
                                ->title('Reservation Status Changed')
                                ->success()
                                ->color('primary')
                                ->body('The status of the reservation has been changed.')
                                ->send();
                        }
                    })->modalWidth(MaxWidth::FiveExtraLarge),
                Tables\Actions\Action::make('check_out')
                    ->visible(fn(Reservation $record): bool => $record->status === ReservationStatus::CONFIRMED->value && $record->guest_status === GuestStatus::CHECKIN->value)
                    ->button()
                    ->requiresConfirmation()
                    ->modalHeading('Check Out')
                    ->modalDescription('Are you sure you want to check out this reservation?')
                    ->modalSubmitActionLabel('Yes, check out it')
                    ->modalIcon('tabler-door-exit')
                    ->icon('tabler-door-exit')
                    ->color('danger')
                    ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                    ->form([
                        Forms\Components\Section::make('Check Out Details')
                            ->description('Please provide the necessary information to check in this reservation. Make sure to fill in all required fields.')
                            ->icon('tabler-file-text')
                            ->schema([
                                Forms\Components\DateTimePicker::make('guest_check_out_at')
                                    ->native(false)
                                    ->default(Carbon::now())
                                    ->format('Y-m-d H:i:s')
                                    ->displayFormat('Y-m-d H:i:s')
                                    ->minDate(fn(Reservation $record): string =>  $record->check_in)
                                    ->maxDate(fn(Reservation $record): string =>  $record->check_out)
                                    ->label(trans('frontOffice.reservation.checkOutLabel'))
                                    ->prefixIcon('tabler-calendar')
                                    ->prefixIconColor('primary')
                                    ->required(),
                                Forms\Components\Section::make('Room Details')
                                    ->columns(4)
                                    ->schema([
                                        Forms\Components\Placeholder::make('room')
                                            ->label(trans('frontOffice.reservation.roomNameLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->room->name),
                                        Forms\Components\Placeholder::make('room_type')
                                            ->label(trans('frontOffice.reservation.roomTypeLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->room->roomType->name),
                                        Forms\Components\Placeholder::make('room')
                                            ->label(trans('frontOffice.reservation.totalNightsLabel'))
                                            ->content(fn(Reservation $record): string =>  $record->total_nights),
                                        Forms\Components\Placeholder::make('price')
                                            ->label(trans('frontOffice.reservation.totalPriceLabel'))
                                            ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->total_price, 2)),
                                        Forms\Components\Placeholder::make('price_pernight')
                                            ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->room->price, 2)),

                                    ]),

                            ])->columns(2),
                        Forms\Components\Repeater::make('payments')
                            ->columnSpanFull()
                            ->relationship('payments')
                            ->columns(4)
                            ->schema([
                                Forms\Components\TextInput::make('amount')
                                    ->mask(RawJs::make('$money($input)'))
                                    ->stripCharacters(',')
                                    ->maxValue(fn(Get $get): ?int => $get('../../total_price'))
                                    ->required()
                                    ->numeric()
                                    ->prefix(trans('frontOffice.reservation.priceCurrency')),

                                \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('status')
                                    ->columnSpan(3)
                                    ->columns(4)
                                    ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->label(),
                                    ])->toArray())

                                    ->icons(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->icon(),
                                    ])->toArray())
                                    ->iconSize(IconSize::Small)
                                    ->color('primary')
                                    ->required(),
                                \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('method')
                                    ->columnSpanFull()
                                    ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->label(),
                                    ])->toArray())
                                    ->descriptions(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->description(),
                                    ])->toArray())
                                    ->icons(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                                        $status->value => $status->icon(),
                                    ])->toArray())
                                    ->iconSize(IconSize::Large)
                                    ->color('primary')
                                    ->required(),
                            ])->minItems(1)
                            ->defaultItems(1)
                    ])->action(function (array $data, Reservation $record): void {

                        $record->status = ReservationStatus::CONFIRMED->value;
                        $record->has_payment = true;
                        $record->checked_in_by = auth()->user()->id;
                        $record->guest_status = GuestStatus::CHECKIN->value;
                        if ($record->guest_check_in_at !== null) {
                            $record->guest_check_in_at = now();
                        }

                        $record->save();
                        if ($record->save()) {
                            Notifications\Notification::make()
                                ->title('Reservation Status Changed')
                                ->success()
                                ->color('primary')
                                ->body('The status of the reservation has been changed.')
                                ->send();
                        }
                    })->modalWidth(MaxWidth::FiveExtraLarge),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->authorize(fn(): bool => auth()->user()->can('view_reservation')),
                    Tables\Actions\EditAction::make()
                        ->color('primary')
                        ->authorize(fn(): bool => auth()->user()->can('update_reservation')),

                    // Tables\Actions\Action::make('change_status')
                    //     ->requiresConfirmation()
                    //     ->modalHeading('Change Status')
                    //     ->modalDescription('Are you sure you want to change the status of this reservation?')
                    //     ->modalSubmitActionLabel('Yes, change it')
                    //     ->modalIcon('tabler-circle-dot')
                    //     ->icon('tabler-circle-dot')
                    //     ->color('primary')
                    //     ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                    //     ->fillForm(fn(Reservation $record): array => [
                    //         'has_payment' => $record->has_payment,
                    //         'price' => $record->price,
                    //         'total_price' => $record->total_price,
                    //         'status' => ReservationStatus::from($record->status)->value,
                    //     ])
                    //     ->form([
                    //         Forms\Components\Section::make('Reservation Details')

                    //             ->description('Please provide the necessary information to add a new reservation. Make sure to fill in all required fields.')
                    //             ->icon('tabler-file-text')

                    //             ->schema([
                    //                 Forms\Components\Select::make('status')
                    //                     ->options(collect(ReservationStatus::cases())->mapWithKeys(fn($status) => [
                    //                         $status->value => $status->label(),
                    //                     ])->toArray())
                    //                     ->distinct()
                    //                     ->required()
                    //                     ->live(),
                    //                 Forms\Components\Toggle::make('has_payment')
                    //                     ->inline(false)
                    //                     ->onIcon('tabler-check')
                    //                     ->offIcon('tabler-x')
                    //                     ->visible(fn(Get $get): bool => $get('status') === 'confirmed')
                    //                     ->label(trans('frontOffice.reservation.hasPaymentLabel'))
                    //                     ->default(false)
                    //                     ->required()
                    //                     ->live(),
                    //                 Forms\Components\Section::make('Room Details')
                    //                     ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment'))
                    //                     ->columns(4)
                    //                     ->schema([
                    //                         Forms\Components\Placeholder::make('room')
                    //                             ->label(trans('frontOffice.reservation.roomNameLabel'))
                    //                             ->content(fn(Reservation $record): string =>  $record->room->name)
                    //                             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment')),
                    //                         Forms\Components\Placeholder::make('room_type')
                    //                             ->label(trans('frontOffice.reservation.roomTypeLabel'))
                    //                             ->content(fn(Reservation $record): string =>  $record->room->roomType->name)
                    //                             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment')),
                    //                         Forms\Components\Placeholder::make('room')
                    //                             ->label(trans('frontOffice.reservation.totalNightsLabel'))
                    //                             ->content(fn(Reservation $record): string =>  $record->total_nights)
                    //                             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment')),
                    //                         Forms\Components\Placeholder::make('price')
                    //                             ->label(trans('frontOffice.reservation.totalPriceLabel'))
                    //                             ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->total_price, 2))
                    //                             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment')),
                    //                         Forms\Components\Placeholder::make('price_pernight')
                    //                             ->content(fn(Reservation $record): string =>  trans('frontOffice.reservation.priceCurrency') . ' ' . number_format($record->room->price, 2))
                    //                             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment')),

                    //                     ]),

                    //             ])->columns(2),


                    //         Forms\Components\Repeater::make('payments')

                    //             ->visible(fn(Get $get): bool => $get('status') === 'confirmed' && $get('has_payment'))
                    //             ->columnSpanFull()
                    //             ->relationship('payments')
                    //             ->columns(3)
                    //             // ->deletable(fn(Get $get): bool => !$get('has_payment'))
                    //             ->schema([
                    //                 Forms\Components\TextInput::make('amount')
                    //                     ->required()
                    //                     ->numeric()
                    //                     ->prefix(trans('frontOffice.reservation.priceCurrency')),
                    //                 Forms\Components\Select::make('status')
                    //                     ->options(collect(PaymentStatus::cases())->mapWithKeys(fn($status) => [
                    //                         $status->value => $status->label(),
                    //                     ])->toArray())
                    //                     ->required(),
                    //                 \JaOcero\RadioDeck\Forms\Components\RadioDeck::make('method')
                    //                     ->columnSpanFull()
                    //                     ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                    //                         $status->value => $status->label(),
                    //                     ])->toArray())
                    //                     ->descriptions(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                    //                         $status->value => $status->description(),
                    //                     ])->toArray())
                    //                     ->icons(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                    //                         $status->value => $status->icon(),
                    //                     ])->toArray())
                    //                     ->iconSize(IconSize::Large)
                    //                     ->color('primary')
                    //                     ->required(),

                    //                 // Forms\Components\Select::make('method')
                    //                 //     ->options(collect(PaymentMethod::cases())->mapWithKeys(fn($status) => [
                    //                 //         $status->value => $status->label(),
                    //                 //     ])->toArray())
                    //                 //     ->required(),

                    //             ])->minItems(1)
                    //     ])
                    //     ->action(function (array $data, Reservation $record): void {
                    //         $record->status = $data['status'];
                    //         $record->save();
                    //         if ($record->save()) {
                    //             Notifications\Notification::make()
                    //                 ->title('Reservation Status Changed')
                    //                 ->success()
                    //                 ->color('primary')
                    //                 ->body('The status of the reservation has been changed.')
                    //                 ->send();
                    //         }
                    //     })->modalWidth(MaxWidth::FiveExtraLarge),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('update_reservation')
                        ->requiresConfirmation()
                        ->modalHeading('Change Status')
                        ->modalDescription('Are you sure you want to change the status of this reservation?')
                        ->modalSubmitActionLabel('Yes, change it')
                        ->modalIcon('tabler-circle-dot')
                        ->icon('tabler-circle-dot')
                        ->color('warning')
                        ->authorize(fn(): bool => auth()->user()->can('update_reservation'))
                        ->form([
                            Forms\Components\Select::make('status')
                                ->options(collect(ReservationStatus::cases())->mapWithKeys(fn($status) => [
                                    $status->value => $status->label(),
                                ])->toArray())
                                ->distinct()
                                ->required()
                                ->live(),

                        ])
                        ->action(function (array $data, Collection $record): void {
                            foreach ($record as $reservation) {
                                $reservation->status = $data['status'];
                                $reservation->save();
                            }
                            Notifications\Notification::make()
                                ->title('Reservation Status Changed')
                                ->success()
                                ->color('primary')
                                ->body('The status of the reservation has been changed.')
                                ->send();
                        }),
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
                            Infolists\Components\TextEntry::make('status')
                                ->formatStateUsing(fn(string $state): string => ReservationStatus::from($state)->label())
                                ->tooltip(fn(Model $record): string => ReservationStatus::from($record->status)->description())
                                ->color(fn(string $state): string => ReservationStatus::from($state)->color())
                                ->icon(fn(string $state): string => ReservationStatus::from($state)->icon())
                                ->badge()
                                ->label(trans('frontOffice.reservation.statusLabel')),
                        ])->grow(false),
                    Infolists\Components\Section::make('Reservation Invoice')
                        ->description('This section provides comprehensive information about invoice.')
                        ->icon('tabler-file-dollar')
                        ->columns([
                            'sm' => 2,
                            'xl' => 3,
                        ])
                        ->schema([
                            Infolists\Components\TextEntry::make('total_price')
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
