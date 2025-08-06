<?php

namespace App\Providers\Filament;

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

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            // ->brandName('Iqra Online Academy')
            // ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2rem')
            ->favicon(asset('images/favicon.ico'))
            ->maxContentWidth('full')
            ->sidebarWidth('15rem')
            ->colors([
                'primary' => [
                    50 => '#f0f4ff',
                    100 => '#e9ecff',
                    200 => '#d4d9ff',
                    300 => '#b0bcff',
                    400 => '#8892ff',
                    500 => '#5f5fcd', // Islamic purple
                    600 => '#4f4fa5',
                    700 => '#40407d',
                    800 => '#333355',
                    900 => '#26262d',
                    950 => '#1a1a22',
                ],
                'success' => [
                    50 => '#f0f7f4',
                    100 => '#dcefe4',
                    200 => '#badecc',
                    300 => '#8cc8ab',
                    400 => '#5fb876',
                    500 => '#2d5a27', // Islamic green
                    600 => '#1f4a1c',
                    700 => '#1a3d18',
                    800 => '#153213',
                    900 => '#0f250d',
                    950 => '#081a06',
                ],
                'warning' => [
                    50 => '#fef7ed',
                    100 => '#fdedd5',
                    200 => '#fbd6aa',
                    300 => '#f8b974',
                    400 => '#f59e42',
                    500 => '#d4a574', // Islamic gold
                    600 => '#c4955f',
                    700 => '#a37a47',
                    800 => '#7d5e37',
                    900 => '#5c442a',
                    950 => '#3d2c1c',
                ],
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                \App\Filament\Widgets\AcademyStatsWidget::class,
            ])
            ->navigationGroups([
                'Academy',
                'Users',
                'Financial',
                'Blog',
                'System',
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
            ])->sidebarCollapsibleOnDesktop();
    }
}