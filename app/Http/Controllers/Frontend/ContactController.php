<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Show the contact page
     */
    public function index()
    {
        // Contact page data
        $contactData = [
            'title' => 'আমাদের সাথে যোগাযোগ করুন',
            'subtitle' => 'আমরা সবসময় আপনাদের প্রশ্ন ও পরামর্শের জন্য প্রস্তুত। যেকোনো সহায়তার জন্য আমাদের সাথে যোগাযোগ করতে দ্বিধা করবেন না।',
            'form_title' => 'আমাদের বার্তা পাঠান',
            'quick_contact_title' => 'দ্রুত যোগাযোগ',
            'support_title' => 'সহায়তার মাধ্যম',
            'faq_title' => 'প্রায়শই জিজ্ঞাসিত প্রশ্ন',
            'faq_subtitle' => 'আপনার প্রশ্নের উত্তর এখানে পেতে পারেন',
            'subjects' => [
                ['value' => 'course_inquiry', 'label' => 'কোর্স সম্পর্কে জানতে চাই'],
                ['value' => 'technical_support', 'label' => 'প্রযুক্তিগত সহায়তা'],
                ['value' => 'payment_issue', 'label' => 'পেমেন্ট সমস্যা'],
                ['value' => 'course_content', 'label' => 'কোর্স কনটেন্ট সম্পর্কে'],
                ['value' => 'certificate', 'label' => 'সার্টিফিকেট সম্পর্কে'],
                ['value' => 'feedback', 'label' => 'মতামত ও পরামর্শ'],
                ['value' => 'other', 'label' => 'অন্যান্য']
            ],
            'contact_info' => [
                [
                    'type' => 'phone',
                    'label' => 'ফোন',
                    'value' => '+৮৮০ ১২৩৪ ৫৬৭৮৯০'
                ],
                [
                    'type' => 'email',
                    'label' => 'ইমেইল',
                    'value' => 'info@iqra-academy.com'
                ],
                [
                    'type' => 'address',
                    'label' => 'ঠিকানা',
                    'value' => '১২৩৪ ইসলামিক সেন্টার<br/>ঢাকা, বাংলাদেশ'
                ],
                [
                    'type' => 'clock',
                    'label' => 'অফিস সময়',
                    'value' => 'রবি-বৃহস্পতি: ৯:০০ - ১৮:০০<br/>শুক্রবার: ১৪:০০ - ১৮:০০'
                ]
            ],
            'support_options' => [
                [
                    'type' => 'chat',
                    'label' => 'লাইভ চ্যাট',
                    'status' => 'অনলাইন'
                ],
                [
                    'type' => 'phone',
                    'label' => 'ফোন সাপোর্ট',
                    'status' => '২৪/৭'
                ],
                [
                    'type' => 'help',
                    'label' => 'FAQ'
                ]
            ]
        ];

        // Static FAQ list
        $contactData['faqs'] = [
            [
                'id' => 1,
                'question' => 'কিভাবে কোর্সে এনরোল করবো?',
                'answer' => 'আপনি আমাদের ওয়েবসাইটে লগইন করে পছন্দের কোর্সে এনরোল করতে পারবেন।'
            ],
            [
                'id' => 2,
                'question' => 'পেমেন্ট পদ্ধতি কী কী?',
                'answer' => 'বিকাশ, নগদ, ব্যাংক ট্রান্সফারসহ বিভিন্ন পেমেন্ট অপশন রয়েছে।'
            ],
            [
                'id' => 3,
                'question' => 'কোর্স কনটেন্ট কতদিন অ্যাক্সেস করতে পারবো?',
                'answer' => 'একবার এনরোল করলে আপনি লাইফটাইম অ্যাক্সেস পাবেন।'
            ]
        ];
        return Inertia::render('Frontend/ContactPage', [
            'contactData' => $contactData
        ]);
    }

    /**
     * Submit contact form
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000'
        ], [
            'name.required' => 'নাম প্রয়োজন',
            'email.required' => 'ইমেইল প্রয়োজন',
            'email.email' => 'সঠিক ইমেইল ঠিকানা দিন',
            'subject.required' => 'বিষয় নির্বাচন করুন',
            'message.required' => 'বার্তা প্রয়োজন',
            'message.min' => 'বার্তা কমপক্ষে ১০ অক্ষরের হতে হবে',
            'message.max' => 'বার্তা সর্বোচ্চ ২০০০ অক্ষরের হতে পারে'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            ContactMessage::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);

            return back()->with('success', 'আপনার বার্তা সফলভাবে পাঠানো হয়েছে। আমরা শীঘ্রই আপনার সাথে যোগাযোগ করবো।');
        } catch (\Exception $e) {
            return back()->with('error', 'দুঃখিত, একটি সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।')->withInput();
        }
    }
} 