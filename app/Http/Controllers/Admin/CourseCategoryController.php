<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseCategoryRequest;
use App\Models\CourseCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CourseCategoryController extends Controller
{
    /**
     * Display a listing of course categories.
     */
    public function index(): Response
    {
        $categories = CourseCategory::withCount(['courses'])
            ->orderBy('name')
            ->paginate(15);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new course category.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created course category.
     */
    public function store(CourseCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Generate slug
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (CourseCategory::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        CourseCategory::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Course category created successfully.');
    }

    /**
     * Show the form for editing the specified course category.
     */
    public function edit(CourseCategory $category): Response
    {
        // Load the category with course count
        $category->loadCount(['courses']);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified course category.
     */
    public function update(CourseCategoryRequest $request, CourseCategory $category): RedirectResponse
    {
        $validated = $request->validated();

        // Update slug if name changed
        if ($validated['name'] !== $category->name) {
            $slug = Str::slug($validated['name']);
            $originalSlug = $slug;
            $counter = 1;
            
            while (CourseCategory::where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
            
            $validated['slug'] = $slug;
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Course category updated successfully.');
    }

    /**
     * Remove the specified course category.
     */
    public function destroy(CourseCategory $category): RedirectResponse
    {
        // Check if category has courses
        if ($category->courses()->count() > 0) {
            return back()->withErrors([
                'delete' => 'This category has courses. Please move the courses to another category first.'
            ]);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Course category deleted successfully.');
    }

    /**
     * Toggle the active status of a category.
     */
    public function toggleStatus(CourseCategory $category): RedirectResponse
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);
        
        return back()->with('success', 'Category status updated successfully.');
    }
} 