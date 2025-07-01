<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentRequest;
use App\Models\Student;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(): Response
    {
        $students = Student::with(['user', 'enrollments.course'])
            ->withCount(['enrollments', 'payments'])
            ->latest()
            ->get();

        // Calculate statistics
        $stats = [
            'total_students' => $students->count(),
            'active_students' => $students->where('is_active', true)->count(),
            'verified_students' => $students->where('is_verified', true)->count(),
            'enrolled_students' => $students->where('enrollments_count', '>', 0)->count(),
        ];

        return Inertia::render('Admin/Students/Index', [
            'students' => $students,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): Response
    {
        // Get users that don't have student or admin roles
        $availableUsers = User::whereDoesntHave('roles', function ($query) {
            $query->whereIn('role_type', ['admin', 'student']);
        })->get(['id', 'name', 'email']);

        return Inertia::render('Admin/Students/Create', [
            'availableUsers' => $availableUsers,
        ]);
    }

    /**
     * Store a newly created student.
     */
    public function store(StudentRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('students/profiles', 'public');
        }

        // Create student
        $student = Student::create($validated);

        // Assign student role
        UserRole::create([
            'user_id' => $validated['user_id'],
            'role_type' => 'student',
        ]);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student created successfully!');
    }

    /**
     * Display the specified student.
     */
    public function show(Student $student): Response
    {
        $student->load([
            'user',
            'enrollments.course.category',
            'payments' => function ($query) {
                $query->latest()->take(5);
            }
        ]);

        // Calculate statistics
        $stats = [
            'total_enrollments' => $student->enrollments->count(),
            'completed_courses' => $student->enrollments->where('is_completed', true)->count(),
            'total_payments' => $student->payments->sum('amount'),
            'average_progress' => $student->enrollments->avg('progress_percentage') ?? 0,
        ];

        return Inertia::render('Admin/Students/Show', [
            'student' => $student,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student): Response
    {
        $student->load(['user']);

        return Inertia::render('Admin/Students/Edit', [
            'student' => $student,
        ]);
    }

    /**
     * Update the specified student.
     */
    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $validated = $request->validated();

        // Remove user_id from validation data (cannot be updated)
        unset($validated['user_id']);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($student->profile_picture && Storage::disk('public')->exists($student->profile_picture)) {
                Storage::disk('public')->delete($student->profile_picture);
            }
            // Store the new one
            $validated['profile_picture'] = $request->file('profile_picture')->store('students/profiles', 'public');
        } else {
            // If no new file, remove from validated data to avoid overwriting with null
            unset($validated['profile_picture']);
        }

        // Update student
        $student->update($validated);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully!');
    }

    /**
     * Remove the specified student.
     */
    public function destroy(Student $student): RedirectResponse
    {
        // Check if student has active enrollments
        if ($student->enrollments()->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete student with active enrollments. Please remove enrollments first.']);
        }

        // Delete profile picture
        if ($student->profile_picture && Storage::disk('public')->exists($student->profile_picture)) {
            Storage::disk('public')->delete($student->profile_picture);
        }

        // Delete student role
        UserRole::where('user_id', $student->user_id)
                ->where('role_type', 'student')
                ->delete();

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully!');
    }


}
