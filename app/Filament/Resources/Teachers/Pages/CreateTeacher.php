<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\User;
use App\Models\UserRole;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;
    
    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $userId = null;
            
            // Check if linking to existing user
            if (!empty($data['user_id'])) {
                $userId = $data['user_id'];
            } else {
                // Create new user account
                $user = User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['temp_password']),
                    'email_verified_at' => now(), // Auto-verify teacher accounts
                ]);
                
                // Assign teacher role
                UserRole::create([
                    'user_id' => $user->id,
                    'role_type' => 'teacher',
                ]);
                
                $userId = $user->id;
            }
            
            // Remove user creation fields from teacher data
            $teacherData = collect($data)->except(['name', 'email', 'temp_password'])->toArray();
            $teacherData['user_id'] = $userId;
            
            // Create teacher record
            return static::getModel()::create($teacherData);
        });
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Teacher account created successfully! Login credentials have been set up.';
    }
}
