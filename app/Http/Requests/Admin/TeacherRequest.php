<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class TeacherRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:255'],
            'speciality' => ['nullable', 'string', 'max:100'],
            'experience' => ['nullable', 'string', 'max:100'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:20'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'full_name.required' => 'Teacher full name is required.',
            'full_name.string' => 'Teacher name must be text.',
            'full_name.max' => 'Teacher name may not exceed 255 characters.',
            
            'speciality.string' => 'Speciality must be text.',
            'speciality.max' => 'Speciality may not exceed 100 characters.',
            
            'experience.string' => 'Experience must be text.',
            'experience.max' => 'Experience may not exceed 100 characters.',
            
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be in jpeg, png, or jpg format.',
            'profile_picture.max' => 'Profile picture may not exceed 2MB.',
            
            'phone.string' => 'Phone number must be text.',
            'phone.max' => 'Phone number may not exceed 20 characters.',
            
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'full_name' => 'Full Name',
            'speciality' => 'Speciality',
            'experience' => 'Experience',
            'profile_picture' => 'Profile Picture',
            'phone' => 'Phone Number',
            'is_active' => 'Active Status',
        ];
    }
} 