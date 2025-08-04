<?php

namespace App\Providers\Filament;

use App\Filament\Simple\Pages\Calender;
use Devonab\FilamentEasyFooter\EasyFooterPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SimplePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('simple')
            ->path('simple')
            ->spa()
             ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->login()
            
            ->discoverResources(in: app_path('Filament/Simple/Resources'), for: 'App\\Filament\\Simple\\Resources')
            ->discoverPages(in: app_path('Filament/Simple/Pages'), for: 'App\\Filament\\Simple\\Pages')
            ->pages([
                Calender::class,
                \App\Filament\Simple\Pages\UnitDietEntry::class,
                \App\Filament\Simple\Pages\PatientEntry::class,
                \App\Filament\Simple\Pages\UnitDietLogs::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Simple/Widgets'), for: 'App\\Filament\\Simple\\Widgets')
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
            ->resources([
                \App\Filament\Resources\HospitalUnitResource::class, // Link to HospitalUnitResource
                \App\Filament\Resources\PatientResource::class, // Link to PatientResource
            ])
            ->plugins([
    EasyFooterPlugin::make()
    ->withSentence(config('app.hospital_name') . " : Health Information and Management Unit")
    ->withLoadTime()->withBorder(),
])
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\CheckUserActive::class,
                \App\Http\Middleware\CheckSimplePanelAccess::class,
            ]);
    }
}
