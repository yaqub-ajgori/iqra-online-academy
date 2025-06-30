<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * Display the checkout page for a course
     */
    public function checkout(string $course): Response
    {
        // In a real application, you would fetch the course from database
        // For now, using mock data based on the course slug
        $courseData = $this->getCourseBySlug($course);
        
        if (!$courseData) {
            abort(404, 'Course not found');
        }

        return Inertia::render('Frontend/PaymentPage', [
            'title' => 'পেমেন্ট - ' . $courseData['title'],
            'course' => $courseData,
            'meta' => [
                'description' => 'Complete your enrollment for ' . $courseData['title'],
                'keywords' => 'payment, enrollment, ইসলামিক কোর্স, অনলাইন পেমেন্ট'
            ]
        ]);
    }

    /**
     * Process course enrollment payment
     */
    public function processPayment(string $course)
    {
        // This would handle the payment processing logic
        // For now, return a success response
        return response()->json([
            'success' => true,
            'message' => 'Payment processed successfully',
            'redirect' => route('frontend.learn.course', ['course' => $course])
        ]);
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