<?php

namespace App\Filament\Resources\Courses\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->columns(4)
            ->components([
                Select::make('module_id')
                    ->label('Module')
                    ->relationship('module', 'title', function ($query) {
                        try {
                            $course = $this->getOwnerRecord();
                            if ($course) {
                                return $query->where('course_id', $course->id);
                            }
                            return $query->whereRaw('1 = 0'); // Return no results if no course
                        } catch (\Exception $e) {
                            return $query->whereRaw('1 = 0');
                        }
                    })
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->default(null),
                    
                TextInput::make('title')
                    ->label('Lesson Title')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->default(''),
                    
                Select::make('lesson_type')
                    ->label('Lesson Type')
                    ->options([
                        'video' => 'Video',
                        'text' => 'Text/Reading',
                    ])
                    ->default('video')
                    ->required()
                    ->columnSpanFull(),
                    
                TextInput::make('video_url')
                    ->label('Video URL')
                    ->url()
                    ->maxLength(500)
                    ->columnSpanFull()
                    ->default(''),
                    
                Textarea::make('content')
                    ->label('Lesson Content')
                    ->columnSpanFull()
                    ->rows(8)
                    ->placeholder('Enter lesson content here...')
                    ->required(false)
                    ->default('')
                    ->dehydrateStateUsing(fn ($state) => $state ?? ''),
                    
                TextInput::make('sort_order')
                    ->label('Sort Order')
                    ->numeric()
                    ->default(function () {
                        try {
                            $course = $this->getOwnerRecord();
                            if (!$course) return 1;
                            $count = $course->lessons()->count();
                            return max(1, ($count ?: 0) + 1);
                        } catch (\Exception $e) {
                            return 1;
                        }
                    })
                    ->minValue(1)
                    ->required()
                    ->columnSpan(1),
                    
                TextInput::make('video_duration')
                    ->label('Duration (seconds)')
                    ->numeric()
                    ->minValue(0)
                    ->suffix('sec')
                    ->columnSpan(1)
                    ->default(0),
                    
                Toggle::make('is_preview')
                    ->label('Free Preview')
                    ->default(false)
                    ->columnSpan(1),
                    
                Toggle::make('is_mandatory')
                    ->label('Mandatory Lesson')
                    ->default(true)
                    ->columnSpan(1),
                    
                Toggle::make('is_active')
                    ->label('Active Lesson')
                    ->default(true)
                    ->columnSpan(1),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                TextColumn::make('module.title')
                    ->label('Module')
                    ->sortable()
                    ->searchable(),
                    
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable()
                    ->width('80px'),
                    
                TextColumn::make('title')
                    ->label('Lesson Title')
                    ->searchable()
                    ->sortable(),
                    
                BadgeColumn::make('lesson_type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'video',
                        'success' => 'text',
                    ]),
                    
                TextColumn::make('video_duration')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state ? gmdate('H:i:s', $state) : '-')
                    ->sortable(),
                    
                IconColumn::make('is_preview')
                    ->label('Preview')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye')
                    ->falseIcon('heroicon-o-eye-slash')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Lesson')
                    ->icon('heroicon-o-plus')
                    ->mutateFormDataUsing(function (array $data): array {
                        try {
                            // Automatically set the course_id from the parent course
                            $course = $this->getOwnerRecord();
                            if ($course) {
                                $data['course_id'] = $course->id;
                            }
                            
                            // Ensure required fields have safe defaults
                            $data['title'] = trim($data['title'] ?? '');
                            $data['lesson_type'] = $data['lesson_type'] ?? 'video';
                            $data['content'] = $data['content'] ?? '';
                            $data['video_url'] = trim($data['video_url'] ?? '');
                            $data['video_duration'] = (int) ($data['video_duration'] ?? 0);
                            $data['sort_order'] = (int) ($data['sort_order'] ?? 1);
                            $data['is_preview'] = (bool) ($data['is_preview'] ?? false);
                            $data['is_mandatory'] = (bool) ($data['is_mandatory'] ?? true);
                            $data['is_active'] = (bool) ($data['is_active'] ?? true);
                            
                            // Validate required fields
                            if (empty($data['title'])) {
                                throw new \Exception('Lesson title is required');
                            }
                            
                            return $data;
                        } catch (\Exception $e) {
                            throw new \Exception('Error creating lesson: ' . $e->getMessage());
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        try {
                            // Ensure course_id is maintained on edit
                            $course = $this->getOwnerRecord();
                            if ($course) {
                                $data['course_id'] = $course->id;
                            }
                            
                            // Ensure required fields have safe defaults
                            $data['title'] = trim($data['title'] ?? '');
                            $data['lesson_type'] = $data['lesson_type'] ?? 'video';
                            $data['content'] = $data['content'] ?? '';
                            $data['video_url'] = trim($data['video_url'] ?? '');
                            $data['video_duration'] = (int) ($data['video_duration'] ?? 0);
                            $data['sort_order'] = (int) ($data['sort_order'] ?? 1);
                            $data['is_preview'] = (bool) ($data['is_preview'] ?? false);
                            $data['is_mandatory'] = (bool) ($data['is_mandatory'] ?? true);
                            $data['is_active'] = (bool) ($data['is_active'] ?? true);
                            
                            // Validate required fields
                            if (empty($data['title'])) {
                                throw new \Exception('Lesson title is required');
                            }
                            
                            return $data;
                        } catch (\Exception $e) {
                            throw new \Exception('Error updating lesson: ' . $e->getMessage());
                        }
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}
