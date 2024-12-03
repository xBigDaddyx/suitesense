<?php

namespace App\Filament\FrontOffice\Resources\ReservationResource\Widgets;

use App\States\Reservation\Confirmed;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Illuminate\Support\Facades\DB;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ReservationCancellationRateChart extends ApexChartWidget
{
    use InteractsWithPageFilters;
    protected int | string | array $columnSpan = [
        'md' => 6,
        'xl' => 12,
    ];
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'reservationCancellationRateChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = '';
    public function getViewData(): array
    {
        // Ambil tanggal mulai dan akhir dari form
        $startDate = $this->filters['startDate'];
        $endDate = $this->filters['endDate'];

        // Query untuk mendapatkan jumlah pemesanan dan pembatalan per bulan
        $reservationsPerMonth = DB::table('reservations')
            ->select(
                DB::raw('DATE_TRUNC(\'month\', check_in) as month'),
                DB::raw('count(*) as total_reservations'),
                DB::raw('sum(CASE WHEN state = \'cancelled\' THEN 1 ELSE 0 END) as cancelled_reservations')
            )
            ->whereState('state', '!=', Confirmed::class)
            ->whereBetween('check_in', [$startDate, $endDate])

            ->groupBy(DB::raw('DATE_TRUNC(\'month\', check_in)'))
            ->orderBy('month')
            ->get();

        // Menghitung cancellation rate per bulan
        $months = [];
        $cancellationRates = [];
        foreach ($reservationsPerMonth as $data) {
            $month = Carbon::parse($data->month)->format('F Y');  // Format bulan: Januari 2024
            $totalReservations = $data->total_reservations;
            $cancelledReservations = $data->cancelled_reservations;

            $cancellationRate = $totalReservations > 0 ? ($cancelledReservations / $totalReservations) * 100 : 0;

            $months[] = $month;
            $cancellationRates[] = round($cancellationRate);
        }

        return [
            'months' => $months,
            'cancellationRates' => $cancellationRates,
            'start_date' => $startDate,
            'end_date' => $endDate,
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
                    'name' => 'Cancelation Rate (%)',
                    'data' => $this->getViewData()['cancellationRates'],
                    'type' => 'column',
                ],

            ],
            'xaxis' => [
                'categories' => $this->getViewData()['months'],
                'axisBorder' => [
                    'show' => 'false'
                ],
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
                        'colors' => '#7E5BC6',
                        'fontWeight' => 600,
                    ],
                ],
            ],

            'colors' => ['#7E5BC6'],
        ];
    }

    protected function extraJsOptions(): ?RawJs
    {
        return RawJs::make(<<<'JS'
    {
        plotOptions: {
          bar: {
            borderRadius: 10,
            dataLabels: {
              position: 'top', // top, center, bottom
            },
          }
        },
        title: {
                    text: 'Reservation Cancellation Rate',
                    align: 'center',
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return ` ${val} %`;
                    },
                    offsetY: -20,
          style: {
            fontSize: '12px',
            colors: ["#304758"]
          }
                },
    }
    JS);
    }
}
