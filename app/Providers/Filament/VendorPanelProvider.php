<?php

namespace App\Providers\Filament;

use App\Http\Middleware\VerifyLicenseKey;
use App\Models\Vendor\Hotel;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Vite;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class VendorPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('manage')
            ->path('manage')
            ->viteTheme('resources/css/filament/manage/theme.css')
            // ->domain('manage.suitify.cloud')
            ->databaseNotifications()
            ->login()
            ->registration()
            ->maxContentWidth(MaxWidth::Full)
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->darkModeBrandLogo(asset('images/logo/suite_sense_logo_dark.png'))
            ->brandLogo(asset('images/logo/suite_sense_logo_white.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/logo/suite_sense_logo_icon.png'))
            ->plugins([
                \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make(),
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make(),
            ])
            ->colors([
                'primary' => [
                    '50' => '#f7f6fc',
                    '100' => '#efedfa',
                    '200' => '#e1def6',
                    '300' => '#cbc4ee',
                    '400' => '#b0a2e3',
                    '500' => '#947cd6',
                    '600' => '#7e5bc6',
                    '700' => '#714db4',
                    '800' => '#5e4097',
                    '900' => '#4e367c',
                    '950' => '#312253',

                ],
            ])
            ->colors([
                'primary' => [
                    '50' => '#f7f6fc',
                    '100' => '#efedfa',
                    '200' => '#e1def6',
                    '300' => '#cbc4ee',
                    '400' => '#b0a2e3',
                    '500' => '#947cd6',
                    '600' => '#7e5bc6',
                    '700' => '#714db4',
                    '800' => '#5e4097',
                    '900' => '#4e367c',
                    '950' => '#312253',

                ],
            ])
            ->discoverResources(in: app_path('Filament/Vendor/Resources'), for: 'App\\Filament\\Vendor\\Resources')
            ->discoverPages(in: app_path('Filament/Vendor/Pages'), for: 'App\\Filament\\Vendor\\Pages')
            ->discoverClusters(in: app_path('Filament/Vendor/Clusters'), for: 'App\\Filament\\Vendor\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Vendor/Widgets'), for: 'App\\Filament\\Vendor\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                VerifyLicenseKey::class,
            ])
            ->tenant(Hotel::class, slugAttribute: 'name', ownershipRelationship: 'latestHotel')
            ->tenantRoutePrefix('hotel');
    }
}
