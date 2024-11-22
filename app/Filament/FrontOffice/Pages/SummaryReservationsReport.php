<?php

namespace App\Filament\FrontOffice\Pages;

use App\Models\Reservation;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Summarizers\Count;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\Summarizers\Sum;
use Illuminate\Http\Client\Response;
use Spatie\Browsershot\Browsershot;
use stdClass;

class SummaryReservationsReport extends Page implements HasTable, HasForms
{

    use InteractsWithTable;
    use InteractsWithForms;

    public string $endDate;
    public string $startDate;
    public $datas;

    protected static ?string $navigationIcon = 'tabler-file-invoice';
    protected static ?string $navigationLabel = 'Summary Reservations Report';
    protected static ?string $navigationGroup = 'Reports';


    public function getTableRecordKey(Model $record): string
    {
        return $record->room_type_id;
    }
    public function generatePdf()
    {
        $file = Browsershot::url(route('filament.frontOffice.pages.summary-reservations-report', ['tenant' => Filament::getTenant()]))->save(storage_path('/app/public/reports/example.pdf'));

        // return new Response($file, 200, array(
        //     'Content-Type' => 'application/pdf',
        //     'Content-Disposition' =>  'attachment; filename="ticket.pdf"'
        // ));
    }
    public function mount()
    {
        $this->getDatas('2024-01-01', '2024-12-31');
    }
    public function getDatas($startDate, $endDate)
    {
        return $this->datas =  Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'room_types.id as room_type_id', // Unique ID untuk tiap tipe kamar
                'room_types.name as room_type',
                'rooms.price as room_price',
                DB::raw('COUNT(reservations.id) as total_reservations'),
                DB::raw("SUM(DATE_PART('day', reservations.check_out - reservations.check_in)) as nights"),
                DB::raw('SUM(reservations.total_price) as total_revenue')
            )
            ->whereBetween('reservations.check_in', [$startDate, $endDate]) // Filter tanggal check-in
            ->groupBy('room_types.id', 'room_types.name', 'rooms.price')
            ->orderByDesc('total_revenue');
    }
    public function table(Table $table): Table
    {

        $this->startDate = '2024-01-01'; // Tanggal mulai
        $this->endDate = '2024-12-31';   // Tanggal akhir
        $query = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
            ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
            ->select(
                'room_types.id as room_type_id', // Unique ID untuk tiap tipe kamar
                'room_types.name as room_type',
                'rooms.price as room_price',
                DB::raw('COUNT(reservations.id) as total_reservations'),
                DB::raw("SUM(DATE_PART('day', reservations.check_out - reservations.check_in)) as nights"),
                DB::raw('SUM(reservations.total_price) as total_revenue')
            )
            ->whereBetween('reservations.check_in', [$this->startDate, $this->endDate]) // Filter tanggal check-in
            ->groupBy('room_types.id', 'room_types.name', 'rooms.price')
            ->orderByDesc('total_revenue');
        return $table
            ->query($query)
            ->paginated(false)
            ->columns([
                TextColumn::make('index')
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
                TextColumn::make('room_type'),
                TextColumn::make('room_price')
                    ->money(trans('frontOffice.reservation.priceCurrency')),
                TextColumn::make('total_reservations')
                    ->numeric(decimalPlaces: 2)
                    ->label('Reservations')
                    ->summarize(
                        Sum::make('total_reservations')
                            ->numeric(decimalPlaces: 2)
                            ->label('Total Reservations')
                    ),
                TextColumn::make('nights')
                    ->numeric(decimalPlaces: 2)
                    ->summarize(
                        Sum::make('nights')
                            ->numeric(decimalPlaces: 2)
                            ->label('Total Nights')
                    ),
                TextColumn::make('total_revenue')
                    ->weight(FontWeight::Bold)
                    ->color('primary')
                    ->money(trans('frontOffice.reservation.priceCurrency'))
                    ->alignEnd()
                    ->label('Revenue')
                    ->summarize(
                        Sum::make('total_revenue')
                            ->numeric(decimalPlaces: 2)
                            ->money(trans('frontOffice.reservation.priceCurrency'))
                            ->label('Total Revenue')
                    ),
                // TextColumn::make('number')

                //     ->label('#'),
                // TextColumn::make('check_in')
                //     ->date(),
                // TextColumn::make('check_out')
                //     ->date(),
                // TextColumn::make('room.name')
                //     ->label('Room'),
                // TextColumn::make('room.roomType.name')
                //     ->label('Room Type'),
                // TextColumn::make('guest.name')
                //     ->label('Guest'),
                // TextColumn::make('status')
                //     ->alignCenter()
                //     ->label('Reservation Status'),
                // TextColumn::make('is_completed_payment')
                //     ->label('Payment Completed')
                //     ->alignCenter()
                //     ->formatStateUsing(function (string $state) {

                //         return $state === '1' ? 'Yes' : 'No';
                //     }),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }
    protected static string $view = 'filament.front-office.pages.summary-reservations-report';
}
