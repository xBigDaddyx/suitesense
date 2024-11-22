<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Widgets;

use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TotalRevenueChart extends ApexChartWidget
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
    protected static ?string $chartId = 'totalRevenueChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Total Room Revenues';
    public function getViewData(): array
    {
        // Ambil rentang tanggal yang dipilih
        $startDate = $this->filters['startDate'];
        $endDate = $this->filters['endDate'];

        // Mengambil data total pendapatan kamar berdasarkan rentang tanggal yang dipilih
        $revenues = DB::table('reservations')
            ->selectRaw('
                EXTRACT(MONTH FROM check_in) AS month,
                EXTRACT(YEAR FROM check_in) AS year,
                SUM(total_price) AS total_revenue
            ')
            ->whereBetween('check_in', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM check_in), EXTRACT(MONTH FROM check_in)'))
            ->orderBy(DB::raw('EXTRACT(YEAR FROM check_in)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM check_in)'))
            ->get();

        $months = [];
        $totalRevenueData = [];

        foreach ($revenues as $revenue) {
            $months[] = Carbon::createFromFormat('m', $revenue->month)->format('F');
            $totalRevenueData[] = $revenue->total_revenue;
        }

        return [
            'months' => $months,  // Nama bulan
            'total_revenue' => $totalRevenueData,  // Data Total Pendapatan Kamar per bulan
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
                    'name' => 'Total Revenues',
                    'data' => $this->getViewData()['total_revenue'],
                ],
            ],
            'xaxis' => [
                'categories' => $this->getViewData()['months'],
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
        ];
    }
    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
    {
        dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return `$ ${val.toLocaleString()}`;
                    },
                },
        yaxis: {
                    labels: {
                        formatter: function (val) {
                            return "$ " + val.toLocaleString();
                        },
                    },
                },
    }
    JS);
    }
}
