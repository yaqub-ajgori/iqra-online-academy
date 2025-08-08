<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\UserRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $roleTypes = $data['role_types'] ?? ['student'];
        unset($data['role_types']);

        $record = static::getModel()::create($data);

        // Create roles for the user
        foreach ($roleTypes as $roleType) {
            UserRole::create([
                'user_id' => $record->id,
                'role_type' => $roleType,
            ]);
        }

        return $record;
    }
}
