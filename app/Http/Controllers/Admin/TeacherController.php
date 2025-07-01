<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TeacherRequest;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(): Response
    {
        $teachers = Teacher::latest()->get();

        // Get statistics
        $stats = [
            'total_teachers' => Teacher::count(),
            'active_teachers' => Teacher::where('is_active', true)->count(),
            'inactive_teachers' => Teacher::where('is_active', false)->count(),
        ];

        return Inertia::render('Admin/Teachers/Index', [
            'teachers' => $teachers,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for creating a new teacher.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Teachers/Create');
    }

    /**
     * Store a newly created teacher in storage.
     */
    public function store(TeacherRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Set default for boolean field if not provided
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = $request->file('profile_picture')->store('teachers/profiles', 'public');
        }

        Teacher::create($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher): Response
    {
        // Get teacher statistics
        $stats = [
            'total_courses' => 0, // Will be updated when course relationship is established
            'active_courses' => 0,
            'total_students' => 0,
            'average_rating' => 0,
        ];

        return Inertia::render('Admin/Teachers/Show', [
            'teacher' => $teacher,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified teacher.
     */
    public function edit(Teacher $teacher): Response
    {
        return Inertia::render('Admin/Teachers/Edit', [
            'teacher' => $teacher,
        ]);
    }

    /**
     * Update the specified teacher in storage.
     */
    public function update(TeacherRequest $request, Teacher $teacher): RedirectResponse
    {
        $validated = $request->validated();

        // Set default for boolean field if not provided
        $validated['is_active'] = $request->boolean('is_active', true);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($teacher->profile_picture) {
                Storage::disk('public')->delete($teacher->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('teachers/profiles', 'public');
        }

        $teacher->update($validated);

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified teacher from storage.
     */
    public function destroy(Teacher $teacher): RedirectResponse
    {
        // Delete profile picture if exists
        if ($teacher->profile_picture) {
            Storage::disk('public')->delete($teacher->profile_picture);
        }

        $teacher->delete();

        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }

    /**
     * Toggle teacher active status.
     */
    public function toggleStatus(Teacher $teacher): RedirectResponse
    {
        $teacher->update([
            'is_active' => !$teacher->is_active,
        ]);

        $status = $teacher->is_active ? 'activated' : 'deactivated';
        
        return redirect()->back()
            ->with('success', "Teacher {$status} successfully.");
    }
}
