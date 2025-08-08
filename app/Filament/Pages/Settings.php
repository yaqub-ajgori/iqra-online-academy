<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Filament\Actions\Action;

class Settings extends Page
{

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-cog-6-tooth';
    }
    
    public static function getNavigationLabel(): string
    {
        return 'Settings';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 99;
    }
    
    public function getTitle(): string
    {
        return 'System Settings';
    }
    
    public function getView(): string
    {
        return 'filament.pages.settings';
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('clearCache')
                ->label('Clear Application Cache')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('optimize:clear');
                        
                        Notification::make()
                            ->title('Cache Cleared Successfully')
                            ->body('All application cache has been cleared.')
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Cache Clear Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('clearConfig')
                ->label('Clear Config Cache')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('config:clear');
                        
                        Notification::make()
                            ->title('Config Cache Cleared')
                            ->body('Configuration cache has been cleared.')
                            ->success()
                            ->send();
                            
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Config Cache Clear Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('clearRoutes')
                ->label('Clear Routes Cache')
                ->color('info')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('route:clear');
                        
                        Notification::make()
                            ->title('Routes Cache Cleared')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Routes Cache Clear Failed')
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('clearViews')
                ->label('Clear Views Cache')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('view:clear');
                        
                        Notification::make()
                            ->title('Views Cache Cleared')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Views Cache Clear Failed')
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('clearLogs')
                ->label('Clear Logs')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        // Clear Laravel log files
                        $logPath = storage_path('logs');
                        $files = glob($logPath . '/laravel*.log');
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                        
                        Notification::make()
                            ->title('Logs Cleared')
                            ->body('All log files have been deleted.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Clear Logs Failed')
                            ->body('Error: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),
            Action::make('systemInfo')
                ->label('System Info')
                ->color('gray')
                ->action(function () {
                    $info = [
                        'PHP Version' => PHP_VERSION,
                        'Laravel Version' => app()->version(),
                        'Environment' => app()->environment(),
                        'Debug Mode' => config('app.debug') ? 'Enabled' : 'Disabled',
                        'Timezone' => config('app.timezone'),
                        'Database' => config('database.default'),
                    ];
                    
                    $message = collect($info)
                        ->map(fn ($value, $key) => "{$key}: {$value}")
                        ->implode("\n");
                    
                    Notification::make()
                        ->title('System Information')
                        ->body($message)
                        ->info()
                        ->duration(10000)
                        ->send();
                }),
        ];
    }
}