<?php

namespace App\Providers\Filament;

use App\Enums\LevelUserEnum;
use App\Http\Middleware\IsAdminMiddelware;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Assets\Css;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontFamily;
use Filament\Support\Facades\FilamentAsset;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Assets\Js;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make()
            ])->navigationItems([
                NavigationItem::make('عودة للموقع')
                    ->url('/')
                    ->icon('heroicon-o-presentation-chart-line')

                    ->sort(1),

            ])
            ->login()
            ->colors([
                'primary' => Color::Red,
                'secondary' => Color::Orange,
                'success' => Color::Green,
                'danger' => Color::Red,
                'warning' => Color::Orange,
                'gold'=>Color::hex('#FFFF00'),

            ])
            ->maxContentWidth('full')
            ->font('Alexandria', 'https://fonts.googleapis.com/css2?family=Alexandria:wght@100..900&display=swap')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
//                Widgets\FilamentInfoWidget::class,
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
                IsAdminMiddelware::class
            ])->spa()
            ->assets([
                Js::make('img-editor', resource_path('js/img-editor.js')),
                Css::make('style-panel',asset('css/assets/style-panel.css'))
            ]);
    }

}
