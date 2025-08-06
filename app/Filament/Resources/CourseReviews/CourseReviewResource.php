<?php

namespace App\Filament\Resources\CourseReviews;

use App\Filament\Resources\CourseReviews\Pages;
use App\Filament\Resources\CourseReviews\Schemas\CourseReviewForm;
use App\Filament\Resources\CourseReviews\Tables\CourseReviewsTable;
use App\Models\CourseReview;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use BackedEnum;
use UnitEnum;

class CourseReviewResource extends Resource
{
    protected static ?string $model = CourseReview::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?string $navigationLabel = 'Course Reviews';

    protected static ?string $modelLabel = 'Course Review';

    protected static ?string $pluralModelLabel = 'Course Reviews';

    protected static ?int $navigationSort = 4;

    protected static UnitEnum|string|null $navigationGroup = 'Academy';

    public static function form(Schema $schema): Schema
    {
        return CourseReviewForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseReviewsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCourseReviews::route('/'),
            'create' => Pages\CreateCourseReview::route('/create'),
            'view' => Pages\ViewCourseReview::route('/{record}'),
            'edit' => Pages\EditCourseReview::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery()
            ->with(['course', 'student', 'user', 'approver']);
        
        $user = auth()->user();
        
        // Teachers can only see reviews for courses they teach
        if ($user && $user->isTeacher()) {
            $teacher = $user->teacher;
            if ($teacher) {
                $query->whereHas('course', function ($q) use ($teacher) {
                    $q->where('instructor_id', $teacher->id)
                      ->orWhereHas('instructors', function ($q) use ($teacher) {
                          $q->where('teacher_id', $teacher->id);
                      });
                });
            }
        }
        
        return $query;
    }

    /**
     * Teachers can view reviews for courses they teach
     */
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isTeacher() && $user->teacher) {
            $teacherId = $user->teacher->id;
            $course = $record->course;
            return $course->instructor_id === $teacherId 
                || $course->instructors()->where('teacher_id', $teacherId)->exists();
        }
        
        return false;
    }
    
    /**
     * Teachers can edit reviews for courses they teach
     */
    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Only admins can delete reviews (teachers should moderate, not delete)
     */
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', CourseReview::STATUS_PENDING)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getNavigationBadge();
        
        if ($pendingCount > 10) {
            return 'danger';
        }
        
        if ($pendingCount > 5) {
            return 'warning';
        }
        
        return 'primary';
    }
}