<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CourseRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'full_description' => 'nullable|string',
            'category_id' => 'nullable|exists:course_categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'instructor_id' => 'required|exists:teachers,id',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'discount_expires_at' => 'nullable|date|after:now',
            'duration_hours' => 'nullable|integer|min:1',
            'status' => 'required|in:draft,review,published,archived',
            'is_featured' => 'sometimes|boolean',
            'is_free' => 'sometimes|boolean',
        ];
    }

    /**
     * Get the validation attributes.
     */
    public function attributes(): array
    {
        return [
            'title' => 'কোর্সের শিরোনাম',
            'full_description' => 'বিস্তারিত বিবরণ',
            'category_id' => 'ক্যাটেগরি',
            'level' => 'কোর্সের স্তর',
            'instructor_id' => 'ইন্সট্রাক্টর',
            'thumbnail_image' => 'থাম্বনেইল ছবি',

            'price' => 'মূল্য',
            'currency' => 'মুদ্রা',
            'discount_price' => 'ছাড়ের মূল্য',
            'discount_expires_at' => 'ছাড় শেষ হওয়ার তারিখ',
            'duration_hours' => 'সময়কাল (ঘন্টা)',
            'status' => 'অবস্থা',
            'is_featured' => 'ফিচার্ড',
            'is_free' => 'বিনামূল্যে',
        ];
    }

    /**
     * Get the validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'কোর্সের শিরোনাম আবশ্যক।',
            'title.string' => 'কোর্সের শিরোনাম টেক্সট হতে হবে।',
            'title.max' => 'কোর্সের শিরোনাম সর্বোচ্চ ২৫৫ অক্ষরের হতে পারে।',
            'category_id.exists' => 'নির্বাচিত ক্যাটেগরি বৈধ নয়।',
            'level.required' => 'কোর্সের স্তর নির্বাচন আবশ্যক।',
            'level.in' => 'কোর্সের স্তর বৈধ নয়।',
            'instructor_id.required' => 'ইন্সট্রাক্টর নির্বাচন আবশ্যক।',
            'instructor_id.exists' => 'নির্বাচিত ইন্সট্রাক্টর বৈধ নয়।',
            'price.required' => 'কোর্সের মূল্য আবশ্যক।',
            'price.numeric' => 'কোর্সের মূল্য সংখ্যা হতে হবে।',
            'price.min' => 'কোর্সের মূল্য ০ বা তার বেশি হতে হবে।',
            'discount_price.numeric' => 'ছাড়ের মূল্য সংখ্যা হতে হবে।',
            'discount_price.lt' => 'ছাড়ের মূল্য মূল কোর্সের মূল্যের চেয়ে কম হতে হবে।',
            'status.required' => 'কোর্সের অবস্থা নির্বাচন আবশ্যক।',
            'status.in' => 'নির্বাচিত অবস্থা বৈধ নয়।',
        ];
    }
} 