<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Widgets;

use App\Models\Reservation;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AdrChart extends ApexChartWidget
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
    protected static ?string $chartId = 'adrChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Monthly Average Room Rate (ADR)';
    protected function getViewData(): array
    {
        $startDate = $this->filters['startDate'];
        $endDate = $this->filters['endDate'];
        // Mengambil data pendapatan kamar dan jumlah kamar yang dipesan berdasarkan rentang tanggal yang dipilih
        $revenues = DB::table('reservations')
            ->selectRaw('
                EXTRACT(MONTH FROM check_in) AS month,
                EXTRACT(YEAR FROM check_in) AS year,
                SUM(price) AS total_revenue,
                COUNT(*) AS total_bookings
            ')
            ->whereBetween('check_in', [$startDate, $endDate])
            ->groupBy(DB::raw('EXTRACT(YEAR FROM check_in), EXTRACT(MONTH FROM check_in)'))
            ->orderBy(DB::raw('EXTRACT(YEAR FROM check_in)'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM check_in)'))
            ->get();

        $months = [];
        $adrData = [];

        foreach ($revenues as $revenue) {
            $months[] = Carbon::createFromFormat('m', $revenue->month)->format('F');
            // Menghitung ADR untuk setiap bulan
            $adrData[] = $revenue->total_revenue / $revenue->total_bookings;
        }

        return [
            'months' => $months,  // Nama bulan
            'adr' => $adrData,  // Data ADR per bulan
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
                    'name' => 'Average',
                    'data' => $this->getViewData()['adr'],
                ],
            ],
            'plotOptions' => [
                'bar' => [
                    'horizontal' => 'true',
                ],
            ],
            'xaxis' => [
                'categories' => $this->getViewData()['months'],
            ],
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
                colors: ['#A855F7'], // Warna untuk batang
    }
    JS);
    }
}
