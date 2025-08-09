<?php

namespace App\Filament\Resources\Certificates\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Schema;

class CertificateInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('certificate_number'),
                TextEntry::make('verification_code'),
                TextEntry::make('enrollment.id')
                    ->numeric(),
                TextEntry::make('student.id')
                    ->numeric(),
                TextEntry::make('course.title')
                    ->numeric(),
                TextEntry::make('student_name'),
                TextEntry::make('course_title'),
                TextEntry::make('instructor_name'),
                TextEntry::make('final_score')
                    ->numeric(),
                TextEntry::make('total_lessons')
                    ->numeric(),
                TextEntry::make('status'),
                TextEntry::make('issued_at')
                    ->dateTime(),
                TextEntry::make('completed_at')
                    ->dateTime(),
                TextEntry::make('expires_at')
                    ->dateTime(),
                TextEntry::make('revoked_at')
                    ->dateTime(),
                TextEntry::make('revoked_by')
                    ->numeric(),
                TextEntry::make('issued_by')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
