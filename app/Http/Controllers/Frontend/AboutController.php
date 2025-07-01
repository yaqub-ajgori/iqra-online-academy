<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AboutController extends Controller
{
    /**
     * Show the about page
     */
    public function index()
    {
        // Get statistics
        $stats = [
            [
                'id' => 1,
                'label' => 'মোট শিক্ষার্থী',
                'value' => Student::count() . '+'
            ],
            [
                'id' => 2,
                'label' => 'মোট কোর্স',
                'value' => Course::where('status', 'published')->count() . '+'
            ],
            [
                'id' => 3,
                'label' => 'অভিজ্ঞ শিক্ষক',
                'value' => Teacher::count() . '+'
            ],
            [
                'id' => 4,
                'label' => 'সন্তুষ্ট শিক্ষার্থী',
                'value' => '৯৮%'
            ]
        ];

        // About page data
        $aboutData = [
            'tagline' => 'ইসলামিক শিক্ষায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে উঠেছে ইকরা অনলাইন একাডেমি। আমরা বিশ্বাস করি জ্ঞানার্জনই প্রকৃত ইবাদত।',
            'mission_title' => 'ইসলামিক জ্ঞানে আলোকিত হোক প্রতিটি মুসলমান',
            'mission_description' => 'আমাদের মূল লক্ষ্য হলো সহজ ও আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা প্রদান করা। কুরআন-হাদিসের আলোকে একটি আদর্শ ইসলামিক সমাজ গঠনে অবদান রাখা।',
            'mission_image' => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=600&h=400&fit=crop&crop=center',
            'mission_image_alt' => 'ইসলামিক শিক্ষা',
            'values_title' => 'যে নীতিমালার উপর ভিত্তি করে আমরা কাজ করি',
            'values_description' => 'ইসলামিক মূল্যবোধ ও আধুনিক শিক্ষা পদ্ধতির সমন্বয়ে আমরা গড়ে তুলেছি একটি অনন্য শিক্ষা প্রতিষ্ঠান।',
            'team_title' => 'অভিজ্ঞ শিক্ষকমণ্ডলী ও বিশেষজ্ঞগণ',
            'team_description' => 'আমাদের টিমে রয়েছেন দেশের সেরা আলেম, ইসলামিক স্কলার এবং প্রযুক্তিবিদরা।',
            'cta_title' => 'আমাদের সাথে যুক্ত হন',
            'cta_description' => 'ইসলামিক শিক্ষার এই মহান যাত্রায় আমাদের সাথে যুক্ত হন। আপনার জ্ঞানের পিপাসা মিটান আমাদের সাথে।',
            'features' => [
                [
                    'id' => 1,
                    'title' => 'মানসম্মত শিক্ষা',
                    'description' => 'অভিজ্ঞ আলেম ও স্কলারদের তত্ত্বাবধানে প্রস্তুত কোর্স'
                ],
                [
                    'id' => 2,
                    'title' => 'সহজ পদ্ধতি',
                    'description' => 'আধুনিক প্রযুক্তি ও ইন্টারঅ্যাক্টিভ মিডিয়ার ব্যবহার'
                ],
                [
                    'id' => 3,
                    'title' => 'সার্টিফিকেশন',
                    'description' => 'আন্তর্জাতিক মানের সার্টিফিকেট প্রদান'
                ]
            ],
            'values' => [
                [
                    'id' => 1,
                    'title' => 'নির্ভুল জ্ঞান',
                    'description' => 'কুরআন ও সুন্নাহর আলোকে সঠিক ইসলামিক জ্ঞান প্রদান করা আমাদের প্রথম অগ্রাধিকার।',
                    'icon' => 'book-open'
                ],
                [
                    'id' => 2,
                    'title' => 'সহযোগিতা',
                    'description' => 'শিক্ষার্থীদের সাথে ব্যক্তিগত যোগাযোগ রেখে তাদের শিক্ষার যাত্রায় সহযোগিতা করা।',
                    'icon' => 'users'
                ],
                [
                    'id' => 3,
                    'title' => 'উৎকর্ষতা',
                    'description' => 'প্রতিটি কোর্স ও সেবায় সর্বোচ্চ মান বজায় রাখা এবং ক্রমাগত উন্নতির চেষ্টা।',
                    'icon' => 'award'
                ],
                [
                    'id' => 4,
                    'title' => 'ভালোবাসা',
                    'description' => 'ইসলামিক শিক্ষার প্রতি ভালোবাসা ও আন্তরিকতার সাথে জ্ঞান বিতরণ।',
                    'icon' => 'heart'
                ],
                [
                    'id' => 5,
                    'title' => 'সর্বজনীনতা',
                    'description' => 'বিশ্বের যেকোনো প্রান্ত থেকে সবার জন্য ইসলামিক শিক্ষার সুযোগ সৃষ্টি।',
                    'icon' => 'globe'
                ],
                [
                    'id' => 6,
                    'title' => 'বিশ্বস্ততা',
                    'description' => 'শিক্ষার্থীদের সাথে স্বচ্ছতা ও বিশ্বস্ততার সাথে সকল কার্যক্রম পরিচালনা।',
                    'icon' => 'shield'
                ]
            ],
            'team_members' => Teacher::take(6)
                ->get()
                ->map(function ($teacher, $index) {
                    return [
                        'id' => $teacher->id,
                        'name' => $teacher->full_name,
                        'position' => $teacher->speciality ?? 'ইসলামিক শিক্ষক',
                        'experience' => $teacher->experience ?? '৫+ বছরের অভিজ্ঞতা',
                        'avatar' => $teacher->profile_picture ?? null
                    ];
                })
                ->toArray()
        ];

        return Inertia::render('Frontend/AboutPage', [
            'aboutData' => $aboutData,
            'stats' => $stats
        ]);
    }
} 