<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Enums\RoomStatus;
use App\Models\Reservation;
use App\Models\Room;
use App\States\Guest\CheckedIn;
use App\States\Reservation\Confirmed;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class OccupancyRateChart extends ApexChartWidget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = [
        'md' => 3,
        'xl' => 6,
    ];
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'occupancyRateChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Monthly Occupancy Rate';
    protected function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;
        $year = Carbon::parse($startDate)->year; // Tahun saat ini
        $totalRooms = Room::count(); // Total kamar

        $monthlyData = collect(range(1, 12))->map(function ($month) use ($year, $totalRooms) {
            $daysInMonth = Carbon::create($year, $month)->daysInMonth;

            // Total room-days dalam bulan ini
            $totalRoomDays = $totalRooms * $daysInMonth;

            // Hitung jumlah hari kamar terisi selama bulan ini
            $occupiedRoomDays = Reservation::whereState('state', Confirmed::class)->orWhereState('state', CheckedIn::class)
                ->where(function ($query) use ($year, $month) {
                    $query->whereBetween('check_in', [
                        Carbon::create($year, $month, 1)->startOfDay(),
                        Carbon::create($year, $month)->endOfMonth(),
                    ])->orWhereBetween('check_out', [
                        Carbon::create($year, $month, 1)->startOfDay(),
                        Carbon::create($year, $month)->endOfMonth(),
                    ]);
                })
                ->get()
                ->reduce(function ($carry, $reservation) use ($year, $month) {
                    $checkIn = Carbon::parse($reservation->check_in);
                    $checkOut = Carbon::parse($reservation->check_out);
                    $monthStart = Carbon::create($year, $month, 1)->startOfDay();
                    $monthEnd = Carbon::create($year, $month)->endOfMonth();

                    $start = $checkIn->greaterThan($monthStart) ? $checkIn : $monthStart;
                    $end = $checkOut->lessThan($monthEnd) ? $checkOut : $monthEnd;

                    return $carry + $start->diffInDays($end) + 1;
                }, 0);

            // Hitung tingkat okupansi
            $occupancyRate = $totalRoomDays > 0 ? ($occupiedRoomDays / $totalRoomDays) * 100 : 0;

            // Hitung total reservasi
            $totalReservations = Reservation::whereState('state', Confirmed::class)->orWhereState('state', CheckedIn::class)
                ->whereBetween('check_in', [
                    Carbon::create($year, $month, 1)->startOfDay(),
                    Carbon::create($year, $month)->endOfMonth(),
                ])->count();

            return [
                'month' => Carbon::create($year, $month)->format('F'), // Nama bulan
                'rate' => round($occupancyRate, 2), // Tingkat okupansi dalam %
                'total_reservations' => $totalReservations, // Total reservasi
            ];
        });

        return [
            'monthlyData' => $monthlyData,
        ];
    }

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Occupancy Rate (%)',
                    'data' => $this->getViewData()['monthlyData']->pluck('rate')->toArray(),
                    'type' => 'column',
                ],
                [
                    'name' => 'Reservations',
                    'data' => $this->getViewData()['monthlyData']->pluck('total_reservations')->toArray(),
                    'type' => 'line',
                ],
            ],
            'xaxis' => [
                'categories' => $this->getViewData()['monthlyData']->pluck('month')->toArray(),
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
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'stroke' => [
                'width' => [0, 4],
            ],
        ];
    }
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
    {
        yaxis: [
                    {
                        title: {
                            text: "Occupancy Rate (%)",
                        },
                        labels: {
                            formatter: function (val) {
                                return val + "%";
                            },
                        },
                    },
                    {
                        opposite: true,
                        title: {
                            text: "Total Reservations",
                        },
                    },
                ],
                colors: ['#ff7e04','#FF382D'], // Warna untuk setiap series
    }
    JS);
    }
}
