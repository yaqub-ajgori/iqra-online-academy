<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\UserRole;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $roleTypes = $data['role_types'] ?? [];
        unset($data['role_types']);

        $record->update($data);

        // Sync roles - delete existing and create new ones
        $record->roles()->delete();
        
        foreach ($roleTypes as $roleType) {
            UserRole::create([
                'user_id' => $record->id,
                'role_type' => $roleType,
            ]);
        }

        return $record;
    }
}
