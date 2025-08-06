<?php

namespace App\Filament\Resources\CourseEnrollments;

use App\Filament\Resources\CourseEnrollments\Pages\CreateCourseEnrollment;
use App\Filament\Resources\CourseEnrollments\Pages\EditCourseEnrollment;
use App\Filament\Resources\CourseEnrollments\Pages\ListCourseEnrollments;
use App\Filament\Resources\CourseEnrollments\Schemas\CourseEnrollmentForm;
use App\Filament\Resources\CourseEnrollments\Tables\CourseEnrollmentsTable;
use App\Models\CourseEnrollment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class CourseEnrollmentResource extends Resource
{
    protected static ?string $model = CourseEnrollment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static UnitEnum|string|null $navigationGroup = 'Academy';
    protected static ?string $navigationLabel = 'Course Enrollments';
    protected static ?string $modelLabel = 'Course Enrollment';
    protected static ?string $pluralModelLabel = 'Course Enrollments';
    protected static ?int $navigationSort = 3;

    /**
     * Scope enrollments for teachers to only show those for their courses
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Teachers can only see enrollments for their courses
        if ($user && $user->isTeacher()) {
            $teacher = $user->teacher;
            if ($teacher) {
                $query->whereHas('course', function ($q) use ($teacher) {
                    $q->where('instructor_id', $teacher->id)
                      ->orWhereHas('instructors', function ($instQuery) use ($teacher) {
                          $instQuery->where('teacher_id', $teacher->id);
                      });
                });
            }
        }
        
        return $query;
    }
    
    /**
     * Teachers can view enrollments for their courses
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
     * Teachers can edit enrollments for their courses
     */
    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Only admins can delete enrollments
     */
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->isAdmin();
    }
    
    /**
     * Only admins can create enrollments directly
     */
    public static function canCreate(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return CourseEnrollmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseEnrollmentsTable::configure($table);
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
            'index' => ListCourseEnrollments::route('/'),
            'create' => CreateCourseEnrollment::route('/create'),
            'edit' => EditCourseEnrollment::route('/{record}/edit'),
        ];
    }
}
