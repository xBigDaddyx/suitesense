<?php

namespace App\Providers;

use Filament\Resources\Resource;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
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
        Page::$reportValidationErrorUsing = function (ValidationException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->color('danger')
                ->danger()
                ->send();
        };
        Resource::scopeToTenant(false);
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Vendor') ? true : null;
        });
    }
}
