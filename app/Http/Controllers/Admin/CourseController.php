<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): Response
    {
        $courses = Course::with(['teacher.user', 'category'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Courses/Index', [
            'courses' => $courses,
        ]);
    }
}
