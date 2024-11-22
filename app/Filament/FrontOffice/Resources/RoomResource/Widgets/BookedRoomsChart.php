<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Models\Reservation;
use App\Models\RoomType;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Filament\Forms\Components\Select;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class BookedRoomsChart extends ApexChartWidget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = [
        'md' => 6,
        'xl' => 12,
    ];
    protected function getFormSchema(): array
    {
        return [
            Select::make('period_type')
                ->options([
                    'monthly' => 'Monthly',
                    'weekly' => 'Weekly',
                    'daily' => 'Daily',
                ])
                ->default('monthly'),
            Select::make('room_type')
                ->options(RoomType::pluck('name', 'id'))
                ->default(RoomType::where('name', 'Suite')->first()->id)

        ];
    }
    protected static ?string $pollingInterval = '10s';
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'bookedRoomsChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Booked Rooms by type Chart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $periodType = $this->filterFormData['period_type'] ?? null;
        $startDate = $this->filters['startDate'] ?? null;
        $endDate = $this->filters['endDate'] ?? null;
        $selectedRoomTypes = $this->filterFormData['room_type'] ?? '';
        if ($periodType == 'monthly') {
            $data = Trend::query(
                Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('reservations.status', 'completed')
                    ->where('rooms.room_type_id', $selectedRoomTypes) // Filter berdasarkan tipe kamar yang dipilih
            )
                ->between(
                    start: Carbon::parse($startDate),
                    end: Carbon::parse($endDate),
                )
                ->dateColumn('check_in')
                ->perMonth()
                ->count();
        } else if ($periodType == 'weekly') {
            $data = Trend::query(
                Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('reservations.status', 'completed')
                    ->where('rooms.room_type_id', $selectedRoomTypes) // Filter berdasarkan tipe kamar yang dipilih
            )
                ->between(
                    start: Carbon::parse($startDate),
                    end: Carbon::parse($endDate),
                )
                ->dateColumn('check_in')
                ->perWeek()
                ->count();
        } else {
            $data = Trend::query(
                Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
                    ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
                    ->where('reservations.status', 'completed')
                    ->where('rooms.room_type_id', $selectedRoomTypes) // Filter berdasarkan tipe kamar yang dipilih
            )
                ->between(
                    start: Carbon::parse($startDate),
                    end: Carbon::parse($endDate),
                )
                ->dateColumn('check_in')
                ->perDay()
                ->count();
        }
        // // Menggunakan CarbonPeriod untuk membuat rentang tanggal
        // $period = CarbonPeriod::create($startDate, $endDate); // Membuat periode dari awal bulan sampai akhir bulan

        // // Mengambil semua pemesanan kamar berdasarkan tipe kamar yang dipilih dan status 'completed' dalam periode waktu tersebut
        // $reservations = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
        //     ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        //     ->where('reservations.status', 'completed')
        //     ->whereIn('room_types.name', $selectedRoomTypes) // Filter berdasarkan tipe kamar yang dipilih
        //     ->whereBetween('reservations.check_in', [$startDate, $endDate]) // Periode waktu
        //     ->select('room_types.name as room_type_name', 'reservations.check_in')
        //     ->get();

        // // Membuat koleksi untuk pivot data
        // $pivotData = collect();

        // // Menyusun data pivot berdasarkan tipe kamar dan tanggal
        // foreach ($reservations as $reservation) {
        //     // Format tanggal untuk legend (misalnya: '2024-11-01')
        //     $dateKey = Carbon::parse($reservation->check_in)->format('Y-m-d');

        //     // Menambahkan data ke dalam koleksi pivot
        //     $pivotData->push([
        //         'room_type_name' => $reservation->room_type_name,
        //         'date' => $dateKey,
        //     ]);
        // }

        // $bookedRoomsByType = Reservation::join('rooms', 'reservations.room_id', '=', 'rooms.id')
        //     ->join('room_types', 'rooms.room_type_id', '=', 'room_types.id')
        //     ->where('reservations.status', 'completed')
        //     ->whereBetween('check_in', [$startDate, $endDate])
        //     ->groupBy('room_types.name') // Kelompokkan berdasarkan tipe kamar
        //     ->select('room_types.name as room_type_name', DB::raw('COUNT(reservations.id) as total_booked'))
        //     ->get();
        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Booked',
                    'data' => $data->pluck('aggregate')->toArray(),
                ],
            ],
            'xaxis' => [
                'categories' => $data->pluck('date')->toArray(),
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b'],
            'stroke' => [
                'curve' => 'smooth',
            ],
        ];
    }
}
