<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Payment;
use App\Models\CourseEnrollment;
use App\Models\Student;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display the checkout page for a course
     */
    public function checkout($course): Response
    {
        $courseModel = Course::where('slug', $course)->where('status', 'published')->first();
        if (!$courseModel) {
            abort(404, 'Course not found');
        }

        return Inertia::render('Frontend/PaymentPage', [
            'title' => 'পেমেন্ট - ' . $courseModel->title,
            'course' => $courseModel,
            'user' => Auth::user(),
            'meta' => [
                'description' => 'Complete your enrollment for ' . $courseModel->title,
                'keywords' => 'payment, enrollment, ইসলামিক কোর্স, অনলাইন পেমেন্ট'
            ]
        ])->encryptHistory();
    }

    /**
     * Process course enrollment payment
     */
    public function processPayment(Request $request, $course)
    {
        // Validate the request
        $validated = $request->validate([
            'transactionId' => 'required|string|max:255',
            'paymentMethod' => 'required|string|in:bkash,nagad,rocket',
            'agreeTerms' => 'required|accepted',
            'agreeRefund' => 'required|accepted',
            'agreeNewsletter' => 'boolean',
            'course_id' => 'required|exists:courses,id',
            'amount' => 'required|numeric|min:0'
        ]);

        // Get the course
        $courseModel = Course::findOrFail($validated['course_id']);
        
        \Log::info('Payment Debug', [
            'course_id' => $courseModel->id,
            'course_title' => $courseModel->title,
            'effective_price' => $courseModel->effective_price,
            'original_price' => $courseModel->price,
            'discount_price' => $courseModel->discount_price,
            'discount_expires_at' => $courseModel->discount_expires_at,
        ]);
        
        // Get the authenticated user and their student record
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        
        if (!$student) {
            return back()->withErrors(['error' => 'Student profile not found.']);
        }

        // Check if student is already enrolled
        $existingEnrollment = CourseEnrollment::where('student_id', $student->id)
            ->where('course_id', $courseModel->id)
            ->first();
            
        if ($existingEnrollment) {
            return back()->withErrors(['error' => 'You are already enrolled in this course.']);
        }

        try {
            DB::beginTransaction();

            // Create payment record
            $payment = Payment::create([
                'student_id' => $student->id,
                'course_id' => $courseModel->id,
                'amount' => $courseModel->effective_price,
                'currency' => 'BDT',
                'payment_method' => $validated['paymentMethod'],
                'transaction_id' => $validated['transactionId'],
                'status' => 'pending', // Admin will verify and update to 'completed'
                'sender_number' => null, // Can be added later if needed
                'receiver_number' => $this->getReceiverNumber($validated['paymentMethod']),
            ]);
            
            // \Log::info('Payment Created', [
            //     'payment_id' => $payment->id,
            //     'amount_saved' => $payment->amount,
            // ]);

            // Create enrollment record
            $enrollment = CourseEnrollment::create([
                'student_id' => $student->id,
                'course_id' => $courseModel->id,
                'enrolled_at' => now(),
                'enrollment_type' => $courseModel->price > 0 ? 'paid' : 'free',
                'payment_id' => $payment->id,
                'amount_paid' => $payment->amount,
                'currency' => 'BDT',
                'payment_status' => 'pending',
                'progress_percentage' => 0,
                'lessons_completed' => 0,
                'is_active' => false, // Will be activated after payment verification
            ]);

            DB::commit();

            return redirect()->route('frontend.student.dashboard')
                ->with('success', 'Payment submitted successfully! Your enrollment will be activated after payment verification.');

        } catch (\Exception $e) {
            DB::rollback();
            
            return back()->withErrors(['error' => 'Payment processing failed. Please try again.']);
        }
    }

    /**
     * Get receiver number based on payment method
     */
    private function getReceiverNumber($paymentMethod)
    {
        $numbers = [
            'bkash' => '01915878662',
            'nagad' => '01750-469027',
            'rocket' => '019158786625'
        ];

        return $numbers[$paymentMethod] ?? null;
    }

    /**
     * Get course data by slug
     * In a real app, this would fetch from database
     */
    private function getCourseBySlug(string $slug): ?array
    {
        $courses = [
            'quran-tilawat-tajwid' => [
                'id' => 1,
                'title' => 'কুরআন তিলাওয়াত ও তাজবীদ',
                'slug' => 'quran-tilawat-tajwid',
                'description' => 'সহীহ উচ্চারণে কুরআন তিলাওয়াত শিখুন। তাজবীদের নিয়মকানুন সহ বিস্তারিত আলোচনা।',
                'image' => 'https://images.unsplash.com/photo-1544113503-7ad5ac882d5d?w=400&h=250&fit=crop',
                'category' => 'কুরআন',
                'level' => 'beginner',
                'price' => 1500, // Changed from 0 to show payment flow
                'duration' => '২ মাস',
                'students_count' => 1250,
                'rating' => 4.9,
                'instructor' => [
                    'name' => 'উস্তাদ মোহাম্মদ রহমান',
                    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'
                ]
            ],
            'hadith-sunnah-jibon' => [
                'id' => 2,
                'title' => 'হাদিস ও সুন্নাহর আলোকে জীবনযাত্রা',
                'slug' => 'hadith-sunnah-jibon',
                'description' => 'রাসূল (সা.) এর সুন্নাহ অনুসরণ করে আদর্শ জীবনযাত্রার নির্দেশনা।',
                'image' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=400&h=250&fit=crop',
                'category' => 'হাদিস',
                'level' => 'intermediate',
                'price' => 1500,
                'duration' => '৩ মাস',
                'students_count' => 890,
                'rating' => 4.8,
                'instructor' => [
                    'name' => 'উস্তাদ আবদুল কারিম',
                    'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face'
                ]
            ],
            'islamic-fiqh-masael' => [
                'id' => 3,
                'title' => 'ইসলামিক ফিকহ - দৈনন্দিন মাসায়েল',
                'slug' => 'islamic-fiqh-masael',
                'description' => 'দৈনন্দিন জীবনের ইসলামিক সমাধান। ইবাদত, মুআমালাত এবং সামাজিক বিষয়াবলী।',
                'image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=250&fit=crop',
                'category' => 'ফিকহ',
                'level' => 'advanced',
                'price' => 2500,
                'duration' => '৪ মাস',
                'students_count' => 650,
                'rating' => 4.7,
                'instructor' => [
                    'name' => 'উস্তাদ মোহাম্মদ হাসান',
                    'avatar' => 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=40&h=40&fit=crop&crop=face'
                ]
            ]
        ];

        return $courses[$slug] ?? null;
    }
} 