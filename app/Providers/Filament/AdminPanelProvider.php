<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use App\Filament\Pages\SelectStore;
use App\Http\Middleware\EnsureStoreSelected;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Enums\Platform;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Jacobtims\FilamentLogger\FilamentLoggerPlugin;
use Jeffgreco13\FilamentBreezy\BreezyCore;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->defaultThemeMode(ThemeMode::System)
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->favicon(asset('assets/images/favicon.png'))
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([])
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce('750ms')
            ->globalSearchFieldSuffix(fn(): ?string => match (Platform::detect()) {
                Platform::Windows, Platform::Linux => 'ctrl + k',
                Platform::Mac => '⌘ k',
                default => null,
            })
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
                EnsureStoreSelected::class,
            ])
            ->userMenuItems([
                Action::make('select_store')
                    ->label('Change Store')
                    ->icon('heroicon-o-building-storefront')
                    ->url(fn(): string => SelectStore::getUrl())
                    ->visible(function () {
                        /** @var User|null $user */
                        $user = Auth::user();
                        return !$user?->hasRole('Super Admin');
                    }),
            ])
            ->navigationGroups([
                NavigationGroup::make('Master Data')
                    ->label('Master Data')
                    ->icon(Heroicon::OutlinedServerStack)
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Access Control')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->collapsed(),
            ])
            ->plugins([
                GlobalSearchModalPlugin::make(),
                FilamentLoggerPlugin::make(),
                FilamentShieldPlugin::make()
                    ->navigationLabel('Role')
                    ->pluralModelLabel('Roles')
                    ->navigationIcon(false)
                    ->navigationGroup('Access Control'),
                FilamentUiSwitcherPlugin::make()
                    ->withModeSwitcher(),
                LightSwitchPlugin::make()
                    ->position(Alignment::TopLeft),
                BreezyCore::make()
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: false, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile' // Sets the slug for the profile page (default = 'my-profile')
                    ),
            ]);
    }
}
