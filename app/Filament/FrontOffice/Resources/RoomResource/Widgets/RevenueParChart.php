<?php

namespace App\Filament\FrontOffice\Resources\RoomResource\Widgets;

use App\Models\Reservation;
use App\Models\Room;
use App\States\Reservation\CheckedIn;
use App\States\Reservation\Confirmed;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class RevenueParChart extends ApexChartWidget
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
    protected static ?string $chartId = 'revenueParChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Monthly RevPAR';
    protected function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? null;

        $year = Carbon::parse($startDate)->year; // Tahun saat ini
        $totalRooms = Room::count(); // Total kamar dalam properti

        $monthlyData = collect(range(1, 12))->map(function ($month) use ($year, $totalRooms) {
            $startOfMonth = Carbon::create($year, $month, 1)->startOfDay();
            $endOfMonth = Carbon::create($year, $month)->endOfMonth();

            // Total pendapatan kamar dalam bulan ini
            $totalRevenue = Reservation::whereState('state', Confirmed::class)->orWhereState('state', CheckedIn::class)
                ->whereBetween('check_in', [$startOfMonth, $endOfMonth])
                ->sum('price'); // Asumsi kolom `total_price` menyimpan pendapatan per reservasi

            // Total kamar tersedia selama bulan (jumlah kamar x jumlah hari dalam bulan)
            $daysInMonth = $startOfMonth->daysInMonth;
            $totalAvailableRooms = $totalRooms * $daysInMonth;

            // Hitung RevPAR
            $revPAR = $totalAvailableRooms > 0 ? $totalRevenue / $totalAvailableRooms : 0;

            return [
                'month' => $startOfMonth->format('F'), // Nama bulan
                'revpar' => round($revPAR, 2), // RevPAR bulanan
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
                'type' => 'bar',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'RevPAR',
                    'data' => $this->getViewData()['monthlyData']->pluck('revpar')->toArray(),
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


        ];
    }
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
    {
        yaxis: {
                    title: {
                        text: "Revenue per Room (Rp)",
                    },
                    labels: {
                        formatter: function (val) {
                            return "$ " + val.toLocaleString();
                        },
                    },
                },
                colors: ['#60E8B7'], // Warna utama grafik
    }
    JS);
    }
}
