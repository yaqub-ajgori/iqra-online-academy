<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     */
    public function index(): Response
    {
        $enrollments = CourseEnrollment::with(['student.user', 'course'])
            ->latest()
            ->paginate(15);

        return Inertia::render('Admin/Enrollments/Index', [
            'enrollments' => $enrollments,
        ]);
    }
}
