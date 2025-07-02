<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CourseEnrollment;
use App\Models\Student;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\EnrollmentRequest;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of enrollments.
     */
    public function index(Request $request): Response
    {
        $enrollments = CourseEnrollment::with(['student.user', 'course', 'payment'])
            ->when($request->search, function ($query, $search) {
                $query->whereHas('student', function ($q) use ($search) {
                    $q->where('full_name', 'like', "%{$search}%");
                })->orWhereHas('course', function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%");
                });
            })
            ->when($request->payment_status, function ($query, $status) {
                $query->where('payment_status', $status);
            })
            ->when($request->enrollment_type, function ($query, $type) {
                $query->where('enrollment_type', $type);
            })
            ->latest('enrolled_at')
            ->paginate(15)
            ->appends($request->query());

        return Inertia::render('Admin/Enrollments/Index', [
            'enrollments' => $enrollments,
            'filters' => $request->only(['search', 'payment_status', 'enrollment_type'])
        ]);
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create(): Response
    {
        $students = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->user->email ?? null,
            ];
        });

        $courses = Course::where('status', 'published')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'price' => $course->price,
                'is_free' => $course->is_free,
            ];
        });

        $payments = Payment::where('status', 'completed')->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'student_name' => $payment->student->full_name ?? null,
                'course_title' => $payment->course->title ?? null,
            ];
        });

        return Inertia::render('Admin/Enrollments/Create', [
            'students' => $students,
            'courses' => $courses,
            'payments' => $payments,
        ]);
    }

    /**
     * Store a newly created enrollment.
     */
    public function store(EnrollmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Check for unique student-course combination
        $existingEnrollment = CourseEnrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->first();

        if ($existingEnrollment) {
            return back()->withErrors(['course_id' => 'Student is already enrolled in this course.']);
        }

        CourseEnrollment::create($validated);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment created successfully.');
    }

    /**
     * Display the specified enrollment.
     */
    public function show(CourseEnrollment $enrollment): Response
    {
        $enrollment->load(['student.user', 'course', 'payment', 'lessonProgress']);

        return Inertia::render('Admin/Enrollments/Show', [
            'enrollment' => $enrollment,
        ]);
    }

    /**
     * Show the form for editing the specified enrollment.
     */
    public function edit(CourseEnrollment $enrollment): Response
    {
        $enrollment->load(['student.user', 'course', 'payment']);

        $students = Student::with('user')->get()->map(function ($student) {
            return [
                'id' => $student->id,
                'name' => $student->full_name,
                'email' => $student->user->email ?? null,
            ];
        });

        $courses = Course::where('status', 'published')->get()->map(function ($course) {
            return [
                'id' => $course->id,
                'title' => $course->title,
                'price' => $course->price,
                'is_free' => $course->is_free,
            ];
        });

        $payments = Payment::where('status', 'completed')->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'transaction_id' => $payment->transaction_id,
                'amount' => $payment->amount,
                'student_name' => $payment->student->full_name ?? null,
                'course_title' => $payment->course->title ?? null,
            ];
        });

        return Inertia::render('Admin/Enrollments/Edit', [
            'enrollment' => $enrollment,
            'students' => $students,
            'courses' => $courses,
            'payments' => $payments,
        ]);
    }

    /**
     * Update the specified enrollment.
     */
    public function update(EnrollmentRequest $request, CourseEnrollment $enrollment): RedirectResponse
    {
        $validated = $request->validated();

        // Check for unique student-course combination (excluding current enrollment)
        $existingEnrollment = CourseEnrollment::where('student_id', $validated['student_id'])
            ->where('course_id', $validated['course_id'])
            ->where('id', '!=', $enrollment->id)
            ->first();

        if ($existingEnrollment) {
            return back()->withErrors(['course_id' => 'Student is already enrolled in this course.']);
        }

        $enrollment->update($validated);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment updated successfully.');
    }

    /**
     * Remove the specified enrollment.
     */
    public function destroy(CourseEnrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully.');
    }

    /**
     * Toggle enrollment active status.
     */
    public function toggleStatus(CourseEnrollment $enrollment): RedirectResponse
    {
        $enrollment->update([
            'is_active' => !$enrollment->is_active
        ]);

        $status = $enrollment->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Enrollment {$status} successfully.");
    }

    /**
     * Update enrollment progress.
     */
    public function updateProgress(CourseEnrollment $enrollment): RedirectResponse
    {
        $enrollment->updateProgress();

        return back()->with('success', 'Enrollment progress updated successfully.');
    }
}
