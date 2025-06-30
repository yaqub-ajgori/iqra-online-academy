<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index(): Response
    {
        return Inertia::render('Frontend/HomePage', [
            'title' => 'ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় আধুনিক প্রযুক্তি',
            'meta' => [
                'description' => 'কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।',
                'keywords' => 'ইসলামিক শিক্ষা, অনলাইন কোর্স, কুরআন, হাদিস, ফিকহ, ইকরা একাডেমি'
            ],
            'featured_courses' => $this->getFeaturedCourses(),
            'stats' => $this->getStats(),
        ]);
    }

    /**
     * Display the about page
     */
    public function about(): Response
    {
        return Inertia::render('Frontend/AboutPage', [
            'title' => 'আমাদের সম্পর্কে - ইকরা অনলাইন একাডেমি',
            'mission' => 'ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির মাধ্যমে সকলের কাছে পৌঁছে দেওয়া',
            'vision' => 'একটি আলোকিত ইসলামিক সমাজ গড়ে তোলা যেখানে সবাই সঠিক ইসলামিক জ্ঞানে সমৃদ্ধ'
        ]);
    }

    /**
     * Display the contact page
     */
    public function contact(): Response
    {
        return Inertia::render('Frontend/ContactPage', [
            'title' => 'যোগাযোগ - ইকরা অনলাইন একাডেমি',
            'contact_info' => [
                'phone' => '+৮৮০ ১২৩৪ ৫৬৭৮৯০',
                'email' => 'info@iqra-academy.com',
                'address' => 'ঢাকা, বাংলাদেশ'
            ]
        ]);
    }

    /**
     * Get featured courses for homepage
     */
    private function getFeaturedCourses(): array
    {
        // This would typically fetch from database
        // For now, returning mock data that matches the frontend
        return [
            [
                'id' => 1,
                'title' => 'কুরআন তিলাওয়াত ও তাজবীদ',
                'description' => 'সহীহ উচ্চারণে কুরআন তিলাওয়াত শিখুন। তাজবীদের নিয়মকানুন সহ বিস্তারিত আলোচনা।',
                'image' => 'https://images.unsplash.com/photo-1544113503-7ad5ac882d5d?w=400&h=250&fit=crop',
                'category' => 'কুরআন',
                'level' => 'beginner',
                'price' => 0,
                'duration' => '২ মাস',
                'students_count' => 1250,
                'rating' => 4.9,
                'instructor' => [
                    'name' => 'উস্তাদ মোহাম্মদ রহমান',
                    'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'
                ],
                'has_preview' => true,
                'enrolled' => false
            ],
            [
                'id' => 2,
                'title' => 'হাদিস ও সুন্নাহর আলোকে জীবনযাত্রা',
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
                ],
                'has_preview' => true,
                'enrolled' => false
            ],
            [
                'id' => 3,
                'title' => 'ইসলামিক ফিকহ - দৈনন্দিন মাসায়েল',
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
                ],
                'has_preview' => true,
                'enrolled' => false
            ]
        ];
    }

    /**
     * Get stats for homepage
     */
    private function getStats(): array
    {
        return [
            'total_courses' => 100,
            'total_students' => 5000,
            'satisfaction_rate' => 95,
            'total_instructors' => 25
        ];
    }
} 