<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StudentRequest extends FormRequest
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
        $rules = [
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()->subYears(5)->toDateString()],
            'gender' => ['nullable', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'bio' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'education_level' => ['nullable', 'string', 'max:255'],
            'is_active' => ['sometimes', 'boolean'],
            'is_verified' => ['sometimes', 'boolean'],
        ];

        if (request()->isMethod('post')) {
            $rules['user_id'] = ['required', 'exists:users,id', 'unique:students,user_id'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User account selection is required.',
            'user_id.exists' => 'Selected user account does not exist.',
            
            'full_name.required' => 'Full name is required.',
            'full_name.string' => 'Full name must be text.',
            'full_name.max' => 'Full name cannot exceed 255 characters.',
            
            'phone.string' => 'Phone number must be text.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            
            'date_of_birth.date' => 'Please provide a valid birth date.',
            'date_of_birth.before' => 'Student must be at least 5 years old.',
            
            'gender.in' => 'Gender must be male, female, or other.',
            
            'address.string' => 'Address must be text.',
            
            'city.string' => 'City must be text.',
            'city.max' => 'City cannot exceed 100 characters.',
            
            'district.string' => 'District must be text.',
            'district.max' => 'District cannot exceed 100 characters.',
            
            'country.string' => 'Country must be text.',
            'country.max' => 'Country cannot exceed 100 characters.',
            
            'postal_code.string' => 'Postal code must be text.',
            'postal_code.max' => 'Postal code cannot exceed 20 characters.',
            
            'profile_picture.image' => 'Profile picture must be an image.',
            'profile_picture.mimes' => 'Profile picture must be in jpeg, png, or jpg format.',
            'profile_picture.max' => 'Profile picture cannot exceed 2MB.',
            
            'bio.string' => 'Biography must be text.',
            
            'occupation.string' => 'Occupation must be text.',
            'occupation.max' => 'Occupation cannot exceed 255 characters.',
            
            'education_level.string' => 'Education level must be text.',
            'education_level.max' => 'Education level cannot exceed 255 characters.',
            
            'is_active.boolean' => 'Active status must be true or false.',
            'is_verified.boolean' => 'Verification status must be true or false.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'User Account',
            'full_name' => 'Full Name',
            'phone' => 'Phone Number',
            'date_of_birth' => 'Date of Birth',
            'gender' => 'Gender',
            'address' => 'Address',
            'city' => 'City',
            'district' => 'District',
            'country' => 'Country',
            'postal_code' => 'Postal Code',
            'profile_picture' => 'Profile Picture',
            'bio' => 'Biography',
            'occupation' => 'Occupation',
            'education_level' => 'Education Level',
            'is_active' => 'Active Status',
            'is_verified' => 'Verification Status',
        ];
    }
} 