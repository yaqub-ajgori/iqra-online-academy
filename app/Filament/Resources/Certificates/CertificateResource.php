<?php

namespace App\Filament\Resources\Certificates;

use App\Filament\Resources\Certificates\Pages\ListCertificates;
use App\Filament\Resources\Certificates\Tables\CertificatesTable;
use App\Filament\Traits\AdminOnlyResource;
use App\Models\Certificate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CertificateResource extends Resource
{
    use AdminOnlyResource;
    
    protected static ?string $model = Certificate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    protected static UnitEnum|string|null $navigationGroup = 'Learning';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Certificate Monitoring';

    public static function form(Schema $schema): Schema
    {
        // Read-only resource - no form needed
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return CertificatesTable::configure($table);
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
            'index' => ListCertificates::route('/'),
        ];
    }

}