<?php

use App\Http\Controllers\ReservationDailyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reservation/daily-report/{date}', ReservationDailyReportController::class)->name('reservation-daily-report');
// Route::prefix('reports')->group(function () {
//     Route::get('/summary-reservation', SummaryReservationReport::class)->name('summary-reservation-report');
// });
