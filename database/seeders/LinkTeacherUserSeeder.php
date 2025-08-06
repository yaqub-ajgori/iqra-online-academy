<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Database\Seeder;

class LinkTeacherUserSeeder extends Seeder
{
    public function run(): void
    {
        $teacherUser = User::where('email', 'teacher@iqra.academy')->first();
        $teacher = Teacher::first(); // উস্তাদ আবু বকর সিদ্দিক
        
        if ($teacherUser && $teacher && !$teacher->user_id) {
            $teacher->user_id = $teacherUser->id;
            $teacher->save();
            echo "Teacher linked to user successfully!\n";
        }
    }
}