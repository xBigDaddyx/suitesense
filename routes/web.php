<?php

use App\Http\Controllers\CreateInvoice;
use App\Http\Controllers\ReservationDailyReportController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebhookController;
use App\Livewire\Cart;
use GlennRaya\Xendivel\Xendivel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reservation/daily-report/{date}', ReservationDailyReportController::class)->name('reservation-daily-report');
// Route::prefix('reports')->group(function () {
//     Route::get('/summary-reservation', SummaryReservationReport::class)->name('summary-reservation-report');
// });


Route::post('/pay-via-ewallet', function (Request $request) {
    $response = Xendivel::payWithEwallet($request)
        ->getResponse();
    return $response;
});
Route::post('/create-invoice', [SubscriptionController::class, 'createInvoice'])->name('create-invoice');
Route::get('/cart/{plan}', Cart::class)->middleware(['auth'])->name('cart');
