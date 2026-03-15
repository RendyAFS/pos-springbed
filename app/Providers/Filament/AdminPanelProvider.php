<?php

namespace App\Providers\Filament;

use Andreia\FilamentUiSwitcher\FilamentUiSwitcherPlugin;
use Awcodes\LightSwitch\Enums\Alignment;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Hydrat\TableLayoutToggle\TableLayoutTogglePlugin;
use Hydrat\TableLayoutToggle\Persisters\LocalStoragePersister;
use Filament\Enums\ThemeMode;
use Filament\Navigation\NavigationGroup;
use Filament\Support\Enums\Platform;
use Filament\Support\Icons\Heroicon;
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
            ->sidebarCollapsibleOnDesktop()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
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
                // TableLayoutTogglePlugin::make()
                //     ->setDefaultLayout('list') // default layout for user seeing the table for the first time
                //     ->persistLayoutUsing(
                //         persister: LocalStoragePersister::class, // chose a persister to save the layout preference of the user
                //         cacheStore: 'redis', // optional, change the cache store for the Cache persister
                //         cacheTtl: 60 * 24, // optional, change the cache time for the Cache persister
                //     )
                //     ->shareLayoutBetweenPages(false) // allow all tables to share the layout option for this user
                //     ->displayToggleAction() // used to display the toggle action button automatically
                //     ->toggleActionHook('tables::toolbar.search.after') // chose the Filament view hook to render the button on
                //     ->listLayoutButtonIcon('heroicon-o-list-bullet')
                //     ->gridLayoutButtonIcon('heroicon-o-squares-2x2'),
            ]);
    }
}
