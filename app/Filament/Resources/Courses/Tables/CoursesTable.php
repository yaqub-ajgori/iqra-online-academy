<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use App\Services\CertificateService;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail_image')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->circular()
                    ->size(50),
                    
                TextColumn::make('title')
                    ->label('Course Title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record) => $record->category->name ?? ''),
                    
                TextColumn::make('instructor.full_name')
                    ->label('Instructor')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('price')
                    ->label('Price')
                    ->sortable()
                    ->formatStateUsing(function ($record) {
                        if ($record->is_free) {
                            return 'Free';
                        }
                        if ($record->discount_price && $record->discount_expires_at && $record->discount_expires_at->isFuture()) {
                            return '৳' . number_format($record->discount_price);
                        }
                        return '৳' . number_format($record->price);
                    }),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'review', 
                        'success' => 'published',
                        'danger' => 'archived',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'review' => 'Under Review', 
                        'published' => 'Published',
                        'archived' => 'Archived'
                    ]),
                    
                SelectFilter::make('category_id')
                    ->label('Category')
                    ->relationship('category', 'name')
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-o-pencil'),
                    
                Action::make('generate_certificates')
                    ->label('Generate Certificates')
                    ->icon('heroicon-o-academic-cap')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Certificates')
                    ->modalDescription('This will generate certificates for all eligible students who have completed this course.')
                    ->modalSubmitActionLabel('Generate')
                    ->action(function ($record) {
                        $certificateService = app(CertificateService::class);
                        $generatedCount = $certificateService->autoGenerateCertificates($record);
                        
                        if ($generatedCount > 0) {
                            Notification::make()
                                ->title('Certificates Generated')
                                ->body("Successfully generated {$generatedCount} certificate(s) for completed students.")
                                ->success()
                                ->send();
                        } else {
                            Notification::make()
                                ->title('No Certificates Generated')
                                ->body('No eligible students found. Students must complete the course to receive certificates.')
                                ->warning()
                                ->send();
                        }
                    })
                    ->visible(fn ($record) => auth()->user()->isAdmin() || auth()->user()->isTeacher()),
                    
                DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->before(function (DeleteAction $action, $record) {
                        // Check if course has enrollments, modules, lessons, or reviews
                        $enrollmentsCount = $record->enrollments()->count();
                        $modulesCount = $record->modules()->count();
                        $reviewsCount = $record->reviews()->count();
                        
                        $constraints = [];
                        if ($enrollmentsCount > 0) $constraints[] = "{$enrollmentsCount} enrollment(s)";
                        if ($modulesCount > 0) $constraints[] = "{$modulesCount} module(s)";
                        if ($reviewsCount > 0) $constraints[] = "{$reviewsCount} review(s)";
                        
                        if (!empty($constraints)) {
                            Notification::make()
                                ->title('Cannot Delete Course')
                                ->body("This course has " . implode(', ', $constraints) . ". Please remove these first.")
                                ->danger()
                                ->persistent()
                                ->send();
                            
                            $action->cancel();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (DeleteBulkAction $action, $records) {
                            $blockedCourses = [];
                            
                            foreach ($records as $record) {
                                $enrollmentsCount = $record->enrollments()->count();
                                $modulesCount = $record->modules()->count();
                                $reviewsCount = $record->reviews()->count();
                                
                                if ($enrollmentsCount > 0 || $modulesCount > 0 || $reviewsCount > 0) {
                                    $constraints = [];
                                    if ($enrollmentsCount > 0) $constraints[] = "{$enrollmentsCount} enrollments";
                                    if ($modulesCount > 0) $constraints[] = "{$modulesCount} modules";
                                    if ($reviewsCount > 0) $constraints[] = "{$reviewsCount} reviews";
                                    
                                    $blockedCourses[] = "{$record->title} (" . implode(', ', $constraints) . ")";
                                }
                            }
                            
                            if (!empty($blockedCourses)) {
                                Notification::make()
                                    ->title('Cannot Delete Courses')
                                    ->body('The following courses have related data: ' . implode('; ', $blockedCourses) . '. Please clean up the related data first.')
                                    ->danger()
                                    ->persistent()
                                    ->send();
                                
                                $action->cancel();
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->emptyStateHeading('No courses yet')
            ->emptyStateDescription('Create your first course to get started.')
            ->emptyStateIcon('heroicon-o-academic-cap');
    }
}
