<?php

namespace App\Http\Controllers;

use App\Enums\ReservationStatus;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Enums\Format;

use function Spatie\LaravelPdf\Support\pdf;

class ReservationDailyReportController extends Controller
{
    public function __invoke($date)
    {
        $datas = Reservation::where('check_in', '>=', $date)->where('check_in', '<=', $date)->with('room.roomType', 'room', 'guest')->get();
        $totalReservations = Reservation::where('check_in', $date)->with('room.roomType', 'room', 'guest')
            ->count();
        $completedReservations = Reservation::where('check_in', $date)->where('status', ReservationStatus::COMPLETED->value)->with('room.roomType', 'room', 'guest')
            ->count();
        $cancelledReservations = Reservation::where('check_in', $date)->where('status', ReservationStatus::CANCELLED->value)->with('room.roomType', 'room', 'guest')
            ->count();
        $totalRevenue = Reservation::where('check_in', $date)->where('status', ReservationStatus::COMPLETED->value)->with('room.roomType', 'room', 'guest')
            ->sum('total_price');
        return pdf()
            ->landscape()
            ->format(Format::A4)
            ->margins(1, 1, 1, 1.25)
            ->view('reports.reservation.daily-report', ['date' => $date, 'datas' => $datas, 'totalReservations' => $totalReservations, 'completedReservations' => $completedReservations, 'cancelledReservations' => $cancelledReservations, 'totalRevenue' => $totalRevenue])
            ->name('summary-reservation-' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
