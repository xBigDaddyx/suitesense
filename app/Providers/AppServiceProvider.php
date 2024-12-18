<?php

namespace App\Providers;

use App\Events\CancelReservationEvent;
use App\Models\Permission;
use App\Models\Role;
use App\Listeners\CancelReservationListener;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Vendor\Subscription;
use App\Observers\PaymentObserver;
use App\Observers\ReservationObserver;
use App\Observers\SubscriptionObserver;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Facades\FilamentColor;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;
use Xendit\Configuration;

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
        app(\Spatie\Permission\PermissionRegistrar::class)
            ->setPermissionClass(Permission::class)
            ->setRoleClass(Role::class);


        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));
        if (env('APP_ENV') == 'production') {
            URL::forceScheme('https');
        }

        Event::listen(CancelReservationEvent::class, CancelReservationListener::class);

        //Observer

        Payment::observe(PaymentObserver::class);
        Reservation::observe(ReservationObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        //end observer

        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->icon('tabler-alert-circle')
                ->title('Form Validation Error')
                ->body($exception->getMessage())
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
