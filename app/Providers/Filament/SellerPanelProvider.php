<?php

namespace App\Providers\Filament;

use App\Http\Middleware\IsSellerMiddelware;
use App\Models\User;
use DutchCodingCompany\FilamentSocialite\FilamentSocialitePlugin;
use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use DutchCodingCompany\FilamentSocialite\Provider;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Illuminate\Contracts\Auth\Authenticatable;

class SellerPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('seller')
            ->path('seller')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->plugin(
                FilamentSocialitePlugin::make()
                    // (required) Add providers corresponding with providers in `config/services.php`.
                    ->providers([
                        // Create a provider 'gitlab' corresponding to the Socialite driver with the same name.
                        Provider::make('google')
                            ->label('google')
                            ->icon('fab-google')
                            ->color(Color::hex('#2f2a6b'))
                            ->outlined(false)
                            ->stateless(false)
                           /* ->scopes(['openid','email','userinfo'])*/
                            /*->with(['...'])*/,
                    ])
                   /* ->createUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                       $user =User::create([
                           'name'=>$oauthUser->getName(),
                           'email'=>$oauthUser->getEmail(),
                           'password'=>bcrypt('fpEV.JY.R2zw7Uv'),

                       ]);
                        auth()->login($user);
                    })*/
                    ->resolveUserUsing(function (string $provider, SocialiteUserContract $oauthUser, FilamentSocialitePlugin $plugin) {
                        // Logic to retrieve an existing user.
                    })
                    /*->domainAllowList(['pazarpasha.com'])*/
                    // (optional) Override the panel slug to be used in the oauth routes. Defaults to the panel ID.
                    ->slug('seller')
                    // (optional) Enable/disable registration of new (socialite-) users.
                    ->registration(true)
                    // (optional) Enable/disable registration of new (socialite-) users using a callback.
                    // In this example, a login flow can only continue if there exists a user (Authenticatable) already.
                    ->registration(fn (string $provider, SocialiteUserContract $oauthUser, ?Authenticatable $user) => (bool) $user)
                    // (optional) Change the associated model class.
                    ->userModelClass(\App\Models\User::class)
                    // (optional) Change the associated socialite class (see below).
                  /*  ->socialiteUserModelClass(\App\Models\User::class)*/
            )

            ->login()
            ->maxContentWidth('full')
            ->discoverResources(in: app_path('Filament/Seller/Resources'), for: 'App\\Filament\\Seller\\Resources')
            ->discoverPages(in: app_path('Filament/Seller/Pages'), for: 'App\\Filament\\Seller\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Seller/Widgets'), for: 'App\\Filament\\Seller\\Widgets')
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
                IsSellerMiddelware::class
            ]);
    }
}
