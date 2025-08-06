<?php

namespace App\Filament\Resources\Students;

use App\Filament\Resources\Students\Pages\CreateStudent;
use App\Filament\Resources\Students\Pages\EditStudent;
use App\Filament\Resources\Students\Pages\ListStudents;
use App\Filament\Resources\Students\Schemas\StudentForm;
use App\Filament\Resources\Students\Tables\StudentsTable;
use App\Models\Student;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;
    protected static UnitEnum|string|null $navigationGroup = 'Academy';
    
    /**
     * Teachers can view students, but only those enrolled in their courses
     */
    public static function canViewAny(): bool
    {
        return auth()->user()->isAdmin() || auth()->user()->isTeacher();
    }
    
    /**
     * Scope students for teachers to only show those enrolled in their courses
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Teachers can only see students enrolled in their courses
        if ($user && $user->isTeacher()) {
            $teacher = $user->teacher;
            if ($teacher) {
                $query->whereHas('enrollments', function ($q) use ($teacher) {
                    $q->whereHas('course', function ($courseQuery) use ($teacher) {
                        $courseQuery->where('instructor_id', $teacher->id)
                                   ->orWhereHas('instructors', function ($instQuery) use ($teacher) {
                                       $instQuery->where('teacher_id', $teacher->id);
                                   });
                    });
                });
            }
        }
        
        return $query;
    }
    
    /**
     * Teachers can view students enrolled in their courses
     */
    public static function canView(Model $record): bool
    {
        $user = auth()->user();
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($user->isTeacher() && $user->teacher) {
            $teacherId = $user->teacher->id;
            return $record->enrollments()->whereHas('course', function ($query) use ($teacherId) {
                $query->where('instructor_id', $teacherId)
                      ->orWhereHas('instructors', function ($instQuery) use ($teacherId) {
                          $instQuery->where('teacher_id', $teacherId);
                      });
            })->exists();
        }
        
        return false;
    }
    
    /**
     * Teachers can edit students in their courses (limited editing)
     */
    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Only admins can delete students
     */
    public static function canDelete(Model $record): bool
    {
        return auth()->user()->isAdmin();
    }
    
    /**
     * Only admins can create students directly
     */
    public static function canCreate(): bool
    {
        return auth()->user()->isAdmin();
    }

    public static function form(Schema $schema): Schema
    {
        return StudentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StudentsTable::configure($table);
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
            'index' => ListStudents::route('/'),
            'create' => CreateStudent::route('/create'),
            'edit' => EditStudent::route('/{record}/edit'),
        ];
    }
}
