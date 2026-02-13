<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureAdminUser;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Facades\FilamentColor;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Facades\Filament;

class AdminPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();

        Filament::serving(function () {
            Filament::registerRenderHook(
                'global-search.input',
                fn () => view('filament::components.global-search-input', [
                    'placeholder' => 'Search a name',
                ])
            );
        });
    }
    public function panel(Panel $panel): Panel
    {
        FilamentColor::register([
            'Day' => '#ffef08',
            'Night' => '#8f68e3',
        ]);

        return $panel

            // for docs
            ->navigationItems([
                NavigationItem::make('User Guide')
                    ->url('/docs', shouldOpenInNewTab: true)
                    ->icon('heroicon-o-book-open')
                    ->group('External'),
            ])

            ->default()
            ->id('admin')
            ->path('/admin')
            ->login()
            ->spa()
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\Filament\Admin\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                EnsureAdminUser::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s');
    }
}
