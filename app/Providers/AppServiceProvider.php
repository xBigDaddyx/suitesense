<?php

namespace App\Providers;

use App\Models\Payment;
use App\Models\Reservation;
use App\Observers\PaymentObserver;
use App\Observers\ReservationObserver;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Validation\ValidationException;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Payment::observe(PaymentObserver::class);
        Reservation::observe(ReservationObserver::class);
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->color('danger')
                ->danger()
                ->send();
        };
        Resource::scopeToTenant(false);
        Gate::after(function ($user, $ability) {
            return $user->hasRole('Vendor') ? true : null;
        });
    }
}
