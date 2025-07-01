<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
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
        return [
            'student_id' => ['required', 'exists:students,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'max:3'],
            'payment_method' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'bank_transfer', 'card'])],
            'transaction_id' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['pending', 'processing', 'completed', 'failed', 'cancelled', 'refunded'])],
            'sender_number' => ['nullable', 'string', 'max:20'],
            'receiver_number' => ['nullable', 'string', 'max:20'],
            'bank_name' => ['nullable', 'string'],
            'account_number' => ['nullable', 'string'],
            'branch_name' => ['nullable', 'string'],
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
            'amount.required' => 'Payment amount is required.',
            'amount.numeric' => 'Payment amount must be a number.',
            'amount.min' => 'Payment amount cannot be negative.',
            'currency.required' => 'Currency is required.',
            'currency.max' => 'Currency code cannot exceed 3 characters.',
            'payment_method.required' => 'Payment method is required.',
            'payment_method.in' => 'Invalid payment method selected.',
            'transaction_id.string' => 'Transaction ID must be text.',
            'status.required' => 'Payment status is required.',
            'status.in' => 'Invalid payment status selected.',
            'sender_number.string' => 'Sender number must be text.',
            'sender_number.max' => 'Sender number cannot exceed 20 characters.',
            'receiver_number.string' => 'Receiver number must be text.',
            'receiver_number.max' => 'Receiver number cannot exceed 20 characters.',
            'bank_name.string' => 'Bank name must be text.',
            'account_number.string' => 'Account number must be text.',
            'branch_name.string' => 'Branch name must be text.',
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
            'amount' => 'payment amount',
            'currency' => 'currency',
            'payment_method' => 'payment method',
            'transaction_id' => 'transaction ID',
            'status' => 'payment status',
            'sender_number' => 'sender number',
            'receiver_number' => 'receiver number',
            'bank_name' => 'bank name',
            'account_number' => 'account number',
            'branch_name' => 'branch name',
        ];
    }
} 