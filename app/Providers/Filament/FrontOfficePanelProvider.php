<?php

namespace App\Providers\Filament;

use App\Filament\FrontOffice\Pages\AvailableRoomDashboard;
use App\Filament\FrontOffice\Pages\ProfilePage;
use App\Models\Vendor\Hotel;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Illuminate\View\Middleware\ShareErrorsFromSession;

class FrontOfficePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('frontOffice')
            ->path('/office/front')
            ->databaseNotifications()
            ->viteTheme('resources/css/filament/frontOffice/theme.css')
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->profile(ProfilePage::class, isSimple: false)
            ->maxContentWidth(MaxWidth::Full)
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->darkModeBrandLogo(asset('images/logo/suitify_logo_dark.svg'))
            ->brandLogo(asset('images/logo/suitify_logo_white.svg'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/logo/suitify_logo_icon.svg'))
            ->plugins([
                FilamentShieldPlugin::make(),
                \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make(),
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make(),
            ])
            ->colors([
                'primary' => [
                    '50' => '#FFFFFF',
                    '100' => '#F8F5FE',
                    '200' => '#DFD0FB',
                    '300' => '#C7AAF7',
                    '400' => '#AE85F4',
                    '500' => '#955FF0',
                    '600' => '#7C3AED',
                    '700' => '#5D14DB',
                    '800' => '#470FA7',
                    '900' => '#320B74',
                    '950' => '#27085A'
                ],

            ])
            ->discoverResources(in: app_path('Filament/FrontOffice/Resources'), for: 'App\\Filament\\FrontOffice\\Resources')
            ->discoverPages(in: app_path('Filament/FrontOffice/Pages'), for: 'App\\Filament\\FrontOffice\\Pages')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->pages([
                // AvailableRoomDashboard::class,
                // Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/FrontOffice/Widgets'), for: 'App\\Filament\\FrontOffice\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
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
            ])
            ->tenantMiddleware([
                \BezhanSalleh\FilamentShield\Middleware\SyncShieldTenant::class,
            ], isPersistent: true)
            ->tenant(Hotel::class, slugAttribute: 'name');
    }
}
