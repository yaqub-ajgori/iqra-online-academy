<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnrollmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $enrollmentId = $this->route('enrollment')?->id;

        return [
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_at' => ['required', 'date'],
            'enrollment_type' => ['required', Rule::in(['paid', 'free', 'scholarship', 'trial'])],
            'payment_id' => ['nullable', 'exists:payments,id'],
            'amount_paid' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_status' => ['required', Rule::in(['pending', 'completed', 'failed', 'refunded'])],
            'progress_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'lessons_completed' => ['nullable', 'integer', 'min:0'],
            'is_completed' => ['boolean'],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'Student is required.',
            'student_id.exists' => 'Selected student does not exist.',
            'course_id.required' => 'Course is required.',
            'course_id.exists' => 'Selected course does not exist.',
            'enrolled_at.required' => 'Enrollment date is required.',
            'enrolled_at.date' => 'Enrollment date must be a valid date.',
            'enrollment_type.required' => 'Enrollment type is required.',
            'enrollment_type.in' => 'Invalid enrollment type selected.',
            'payment_id.exists' => 'Selected payment does not exist.',
            'amount_paid.required' => 'Amount paid is required.',
            'amount_paid.numeric' => 'Amount paid must be a number.',
            'amount_paid.min' => 'Amount paid cannot be negative.',
            'currency.required' => 'Currency is required.',
            'currency.max' => 'Currency code cannot exceed 3 characters.',
            'payment_status.required' => 'Payment status is required.',
            'payment_status.in' => 'Invalid payment status selected.',
            'progress_percentage.numeric' => 'Progress percentage must be a number.',
            'progress_percentage.min' => 'Progress percentage cannot be negative.',
            'progress_percentage.max' => 'Progress percentage cannot exceed 100.',
            'lessons_completed.integer' => 'Lessons completed must be a whole number.',
            'lessons_completed.min' => 'Lessons completed cannot be negative.',
            'is_completed.boolean' => 'Completion status must be true or false.',
            'is_active.boolean' => 'Active status must be true or false.',
        ];
    }

    /**
     * Get custom attribute names.
     */
    public function attributes(): array
    {
        return [
            'student_id' => 'student',
            'course_id' => 'course',
            'enrolled_at' => 'enrollment date',
            'enrollment_type' => 'enrollment type',
            'payment_id' => 'payment',
            'amount_paid' => 'amount paid',
            'currency' => 'currency',
            'payment_status' => 'payment status',
            'progress_percentage' => 'progress percentage',
            'lessons_completed' => 'lessons completed',
            'is_completed' => 'completion status',
            'is_active' => 'active status',
        ];
    }
} 