<?php

namespace App\Http\Requests\Admin;

use App\Models\CourseCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CourseCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()?->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'sometimes|boolean',
        ];
    }

    /**
     * Get the validation attributes.
     */
    public function attributes(): array
    {
        return [
            'name' => 'Category Name',
            'description' => 'Description',
            'is_active' => 'Status',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Category name is required.',
            'name.string' => 'Category name must be text.',
            'name.max' => 'Category name may not be greater than 255 characters.',
            'description.string' => 'Description must be text.',
            'description.max' => 'Description may not be greater than 1000 characters.',
            'is_active.boolean' => 'Status must be true or false.',
        ];
    }
} 