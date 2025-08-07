<?php

namespace App\Filament\Resources\Certificates\Pages;

use App\Filament\Resources\Certificates\CertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCertificates extends ListRecords
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No actions - certificates are auto-generated
        ];
    }
}