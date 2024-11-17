<?php

namespace App\Providers\Filament;

use App\Filament\FrontOffice\Pages\AvailableRoomDashboard;
use App\Models\Hotel;
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
            ->viteTheme('resources/css/filament/frontOffice/theme.css')
            ->login()
            ->maxContentWidth(MaxWidth::Full)
            ->font('Poppins')
            ->sidebarCollapsibleOnDesktop()
            ->darkModeBrandLogo('/storage/images/logo/suite_sense_logo_dark.png')
            ->brandLogo(asset('/storage/images/logo/suite_sense_logo_white.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('/storage/images/logo/suite_sense_logo_icon.png'))
            ->plugins([
                \Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin::make(),
                \Saade\FilamentFullCalendar\FilamentFullCalendarPlugin::make(),
            ])
            ->colors([
                'primary' => '#FBAC08',
            ])
            ->discoverResources(in: app_path('Filament/FrontOffice/Resources'), for: 'App\\Filament\\FrontOffice\\Resources')
            ->discoverPages(in: app_path('Filament/FrontOffice/Pages'), for: 'App\\Filament\\FrontOffice\\Pages')
            ->pages([
                AvailableRoomDashboard::class,
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
            ->tenant(Hotel::class, slugAttribute: 'name', ownershipRelationship: 'hotel')
            ->tenantRoutePrefix('hotel');
    }
}
