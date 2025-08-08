<?php

namespace App\Filament\Resources\Courses;

use App\Filament\Resources\Courses\Pages\CreateCourse;
use App\Filament\Resources\Courses\Pages\EditCourse;
use App\Filament\Resources\Courses\Pages\ListCourses;
use App\Filament\Resources\Courses\RelationManagers\ModulesRelationManager;
use App\Filament\Resources\Courses\RelationManagers\LessonsRelationManager;
use App\Filament\Resources\Courses\Schemas\CourseForm;
use App\Filament\Resources\Courses\Tables\CoursesTable;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;
    protected static UnitEnum|string|null $navigationGroup = 'Learning';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Courses';

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ModulesRelationManager::class,
            LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }
    
    /**
     * Modify query for teachers to only see their own courses
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Teachers can only see courses where they are the primary instructor
        // or listed as an additional instructor
        if ($user && $user->isTeacher()) {
            $teacher = $user->teacher;
            if ($teacher) {
                $query->where(function ($q) use ($teacher) {
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
     * Teachers can view courses they teach
     */
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isTeacher() && $user->teacher) {
            $teacherId = $user->teacher->id;
            return $record->instructor_id === $teacherId 
                || $record->instructors()->where('teacher_id', $teacherId)->exists();
        }
        
        return false;
    }
    
    /**
     * Teachers can edit courses they teach
     */
    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Teachers cannot delete courses
     */
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->isAdmin();
    }
    
    /**
     * Teachers can create courses
     */
    public static function canCreate(): bool
    {
        return true; // Both admin and teachers can create courses
    }
}
