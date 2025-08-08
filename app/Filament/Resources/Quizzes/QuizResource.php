<?php

namespace App\Filament\Resources\Quizzes;

use App\Filament\Resources\Quizzes\Pages\CreateQuiz;
use App\Filament\Resources\Quizzes\Pages\EditQuiz;
use App\Filament\Resources\Quizzes\Pages\ListQuizzes;
use App\Filament\Resources\Quizzes\RelationManagers\AttemptsRelationManager;
use App\Filament\Resources\Quizzes\RelationManagers\QuestionsRelationManager;
use App\Filament\Resources\Quizzes\Schemas\QuizForm;
use App\Filament\Resources\Quizzes\Tables\QuizzesTable;
use App\Models\Quiz;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class QuizResource extends Resource
{
    protected static ?string $model = Quiz::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;
    protected static UnitEnum|string|null $navigationGroup = 'Learning';
    protected static ?int $navigationSort = 3;

    /**
     * Scope quizzes for teachers to only show those for their courses
     */
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        $user = auth()->user();
        
        // Teachers can only see quizzes for their courses
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
     * Teachers can view quizzes for their courses
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
     * Teachers can edit quizzes for their courses
     */
    public static function canEdit(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Teachers can delete quizzes for their courses
     */
    public static function canDelete(Model $record): bool
    {
        return static::canView($record);
    }
    
    /**
     * Teachers can create quizzes for their courses
     */
    public static function canCreate(): bool
    {
        return true; // Both admin and teachers can create quizzes
    }

    public static function form(Schema $schema): Schema
    {
        return QuizForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizzesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
            AttemptsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQuizzes::route('/'),
            'create' => CreateQuiz::route('/create'),
            'edit' => EditQuiz::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Quizzes & Exams';
    }

    public static function getPluralLabel(): string
    {
        return 'Quizzes & Exams';
    }
}