<template>
    <FrontendLayout title="কোর্সের বিস্তারিত - ইকরা অনলাইন একাডেমি">
        <Head :title="course.title" />

        <!-- Course Hero Section -->
        <section class="relative bg-gradient-to-br from-[#5f5fcd]/10 via-white to-[#2d5a27]/5 py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">
                    <!-- Course Content -->
                    <div class="lg:col-span-2">
                        <!-- Course Header -->
                        <div class="mb-8">
                            <h1 class="mb-4 text-3xl leading-tight font-bold text-gray-900 lg:text-4xl">
                                {{ course.title }}
                            </h1>

                            <p class="mb-6 text-lg leading-relaxed text-gray-600">
                                {{ course.description }}
                            </p>

                            <!-- Course Stats -->
                            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center space-x-2">
                                    <UsersIcon class="h-5 w-5 text-[#5f5fcd]" />
                                    <span>{{ course.students_count }}+ শিক্ষার্থী</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <ClockIcon class="h-5 w-5 text-[#2d5a27]" />
                                    <span>{{ course.duration }}</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="flex space-x-1 text-[#d4a574]">
                                        <StarIcon class="h-4 w-4 fill-current" />
                                        <StarIcon class="h-4 w-4 fill-current" />
                                        <StarIcon class="h-4 w-4 fill-current" />
                                        <StarIcon class="h-4 w-4 fill-current" />
                                        <StarIcon class="h-4 w-4 fill-current" />
                                    </div>
                                    <span>{{ reviewRating }} ({{ reviewCount }} রিভিউ)</span>
                                </div>
                            </div>
                        </div>

                        <!-- Course Content Tabs -->
                        <div class="mb-12">
                            <div class="mb-8 border-b border-gray-200">
                                <nav class="flex space-x-8">
                                    <button
                                        v-for="tab in tabs"
                                        :key="tab.id"
                                        @click="activeTab = tab.id"
                                        :class="[
                                            'border-b-2 px-1 py-3 text-sm font-medium transition-colors duration-200',
                                            activeTab === tab.id
                                                ? 'border-[#5f5fcd] text-[#5f5fcd]'
                                                : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700',
                                        ]"
                                    >
                                        {{ tab.name }}
                                    </button>
                                </nav>
                            </div>

                            <!-- Tab Content -->
                            <div v-if="activeTab === 'overview'" class="prose prose-slate max-w-none">
                                <h3>কোর্স সম্পর্কে</h3>
                                <div v-html="sanitizeHtml(course.full_description)"></div>

                                <!-- Course content will be displayed through modules and lessons -->
                            </div>

                            <div v-if="activeTab === 'curriculum'" class="space-y-4">
                                <!-- Show message if no modules -->
                                <div v-if="!course.modules || course.modules.length === 0" class="py-8 text-center text-gray-500">
                                    <p>এই কোর্সের জন্য এখনো কোনো কারিকুলাম যোগ করা হয়নি।</p>
                                </div>

                                <!-- Show modules if available -->
                                <div
                                    v-for="module in course.modules"
                                    :key="module.id"
                                    :class="['curriculum-module', expandedModules.includes(module.id) && 'expanded']"
                                >
                                    <div class="curriculum-header" @click="toggleModule(module.id)">
                                        <div>
                                            <span>{{ module.title }}</span>
                                            <span v-if="module.lessons_count" class="ml-2 text-sm text-gray-500">
                                                ({{ module.lessons_count }} টি পাঠ)
                                            </span>
                                        </div>
                                        <ChevronDownIcon :class="['curriculum-chevron', expandedModules.includes(module.id) && 'expanded']" />
                                    </div>
                                    <transition name="fade-slide">
                                        <div v-show="expandedModules.includes(module.id)" class="curriculum-lessons">
                                            <!-- Show message if module has no lessons -->
                                            <div v-if="!module.lessons || module.lessons.length === 0" class="py-4 text-center text-gray-500">
                                                <p>এই মডিউলে এখনো কোনো পাঠ যোগ করা হয়নি।</p>
                                            </div>

                                            <!-- Show lessons if available -->
                                            <ul v-else>
                                                <li v-for="lesson in module.lessons" :key="lesson.id" class="curriculum-lesson">
                                                    <span class="curriculum-dot"></span>
                                                    <div>
                                                        <span>{{ lesson.title }}</span>
                                                        <span v-if="lesson.duration" class="ml-2 text-xs text-gray-400">
                                                            {{ lesson.duration }}
                                                        </span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </transition>
                                </div>
                            </div>

                            <div v-if="activeTab === 'instructor'" class="rounded-2xl bg-gray-50 p-8">
                                <div class="flex items-start space-x-6">
                                    <div>
                                        <img
                                            v-if="course.instructor.avatar"
                                            :src="course.instructor.avatar"
                                            :alt="course.instructor.name"
                                            class="h-24 w-24 rounded-full border-4 border-white object-cover shadow-lg"
                                        />
                                        <div
                                            v-else
                                            class="flex h-24 w-24 items-center justify-center rounded-full border-4 border-white bg-[#5f5fcd]/10 shadow-lg"
                                        >
                                            <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <circle cx="28" cy="28" r="28" fill="#5f5fcd" />
                                                <path
                                                    d="M28 29c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7 3.582 7 8 7zm0 2c-5.33 0-16 2.686-16 8v3a1 1 0 001 1h30a1 1 0 001-1v-3c0-5.314-10.67-8-16-8z"
                                                    fill="#fff"
                                                />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="mb-1 text-2xl font-bold text-gray-900">{{ course.instructor.name }}</h3>
                                        <p v-if="course.instructor.bio" class="mb-2 text-lg text-[#5f5fcd]">{{ course.instructor.bio }}</p>
                                        <p v-if="course.instructor.experience" class="mb-2 text-gray-700">
                                            অভিজ্ঞতা: {{ course.instructor.experience }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div v-if="activeTab === 'reviews'" class="space-y-6">
                                <div v-for="review in reviews" :key="review.id" class="border-b border-gray-200 pb-6">
                                    <div class="flex items-start space-x-4">
                                        <div>
                                            <div
                                                class="flex h-12 w-12 items-center justify-center rounded-full border-4 border-white bg-[#5f5fcd]/10 shadow-lg"
                                            >
                                                <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="28" cy="28" r="28" fill="#5f5fcd" />
                                                    <path
                                                        d="M28 29c4.418 0 8-3.134 8-7s-3.582-7-8-7-8 3.134-8 7 3.582 7 8 7zm0 2c-5.33 0-16 2.686-16 8v3a1 1 0 001 1h30a1 1 0 001-1v-3c0-5.314-10.67-8-16-8z"
                                                        fill="#fff"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="mb-2 flex items-center justify-between">
                                                <h4 class="font-semibold text-gray-900">{{ review.student.name }}</h4>
                                                <div class="flex items-center space-x-1">
                                                    <div class="flex space-x-1 text-[#d4a574]">
                                                        <StarIcon v-for="i in review.rating" :key="i" class="h-4 w-4 fill-current" />
                                                    </div>
                                                    <span class="ml-2 text-sm text-gray-500">{{ review.date }}</span>
                                                </div>
                                            </div>
                                            <p class="leading-relaxed text-gray-600">{{ review.comment }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Card -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8">
                            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                                <!-- Price Section -->
                                <div class="border-b border-gray-200 p-8 text-center">
                                    <template v-if="course.is_free">
                                        <div class="mb-2 text-3xl font-bold text-[#2d5a27]">ফ্রি</div>
                                    </template>
                                    <template v-else-if="showDiscount">
                                        <div class="mb-2 text-3xl font-bold text-[#e2136e]">
                                            ৳{{ formatPrice(course.discount_price as number) }}
                                            <span class="ml-2 text-lg text-gray-400 line-through">৳{{ formatPrice(course.price as number) }}</span>
                                        </div>
                                        <div
                                            v-if="discountExpiresIn"
                                            class="mb-2 inline-block rounded bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800"
                                        >
                                            {{ discountExpiresIn }}
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div class="mb-2 text-3xl font-bold text-gray-900">৳{{ formatPrice(course.price as number) }}</div>
                                    </template>
                                    <p class="text-sm text-gray-600">লাইফটাইম অ্যাক্সেস</p>
                                </div>

                                <!-- Enrollment Actions -->
                                <div class="space-y-4 p-8">
                                    <PrimaryButton
                                        v-if="!isEnrolled"
                                        @click="handleEnroll"
                                        size="lg"
                                        variant="primary"
                                        :icon="BookOpenIcon"
                                        :loading="enrollLoading"
                                        :disabled="enrollLoading"
                                        class="w-full justify-center"
                                    >
                                        {{ enrollLoading ? 'প্রক্রিয়াধীন...' : course.price === 0 ? 'ফ্রি এনরোল করুন' : 'এখনই কিনুন' }}
                                    </PrimaryButton>

                                    <PrimaryButton
                                        v-else
                                        disabled
                                        size="lg"
                                        variant="accent"
                                        :icon="ImageIcon"
                                        class="w-full cursor-not-allowed justify-center opacity-60"
                                    >
                                        আপনি ইতিমধ্যে এনরোল করেছেন
                                    </PrimaryButton>
                                </div>

                                <!-- Course Features -->
                                <div class="border-t border-gray-100 bg-gray-50 p-8">
                                    <h4 class="mb-4 font-semibold text-gray-900">এই কোর্সে যা পাবেন:</h4>
                                    <ul class="space-y-3 text-sm text-gray-600">
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>{{ course.total_lessons || 0 }} টি লেসন</span>
                                        </li>
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>{{ course.duration }} কন্টেন্ট</span>
                                        </li>
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>ডাউনলোডযোগ্য রিসোর্স</span>
                                        </li>
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>সার্টিফিকেট অফ কমপ্লিশন</span>
                                        </li>
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>লাইফটাইম অ্যাক্সেস</span>
                                        </li>
                                        <li class="flex items-center space-x-3">
                                            <CheckIcon class="h-4 w-4 text-[#2d5a27]" />
                                            <span>কমিউনিটি সাপোর্ট</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Related Courses -->
        <!-- <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="সম্পর্কিত কোর্সসমূহ"
          subtitle="আরও জানুন ইসলামিক শিক্ষা নিয়ে"
          description="এই কোর্সের সাথে সম্পর্কিত আরও কিছু কোর্স যা আপনার জ্ঞান বৃদ্ধিতে সহায়ক হবে।"
          variant="default"
          size="md"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <CourseCard 
            v-for="relatedCourse in relatedCourses" 
            :key="relatedCourse.id"
            :course="relatedCourse"
            @enroll="handleCourseEnroll"
            @favorite="handleCourseFavorite"
          />
        </div>
      </div>
    </section> -->
    </FrontendLayout>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { BookOpenIcon, CheckIcon, ChevronDownIcon, ClockIcon, ImageIcon, StarIcon, UsersIcon } from 'lucide-vue-next';
import { computed, onMounted, ref } from 'vue';
// @ts-ignore
import dayjs from 'dayjs';

// Type definitions
interface Course {
    id: number;
    slug: string;
    title: string;
    description: string;
    full_description?: string;
    image?: string;
    price: number;
    duration: string;
    students_count: number;
    rating: number;
    reviews_count?: number;
    total_lessons?: number;
    learning_outcomes?: string[];
    requirements?: string[];
    instructor: {
        name: string;
        title?: string;
        avatar?: string;
        bio?: string;
        courses_count?: number;
        students_count?: number;
        rating?: number;
        experience?: string;
        phone?: string;
    };
    modules?: Array<{
        id: number;
        title: string;
        lessons_count: number;
        duration: string;
        lessons: Array<{
            id: number;
            title: string;
            duration: string;
        }>;
    }>;
    reviews?: Array<{
        id: number;
        student: {
            name: string;
            avatar: string;
        };
        rating: number;
        date: string;
        comment: string;
    }>;
}

const props = defineProps({
    course: {
        type: Object,
        required: true,
    },
    isAuthenticated: {
        type: Boolean,
        default: false,
    },
    isEnrolled: {
        type: Boolean,
        default: false,
    },
});

const activeTab = ref('overview');
const expandedModules = ref<number[]>([]);
const enrollLoading = ref(false);

const tabs = [
    { id: 'overview', name: 'কোর্স সম্পর্কে' },
    { id: 'curriculum', name: 'কারিকুলাম' },
    { id: 'instructor', name: 'শিক্ষক' },
    { id: 'reviews', name: 'রিভিউ' },
];

const toggleModule = (moduleId: number) => {
    // If the clicked module is already open, close it by clearing the array.
    if (expandedModules.value.includes(moduleId)) {
        expandedModules.value = [];
    } else {
        // Otherwise, set the clicked module as the only open one.
        expandedModules.value = [moduleId];
    }
};

onMounted(() => {
    if (props.course.modules && props.course.modules.length > 0) {
        expandedModules.value.push(props.course.modules[0].id);
    }
});

// Dummy data for reviews (replace with actual data)
const reviews = ref([
    {
        id: 1,
        student: {
            name: 'মাহমুদুল হাসান',
            avatar: 'https://randomuser.me/api/portraits/men/32.jpg',
        },
        rating: 5,
        date: '১ দিন আগে',
        comment: 'এই কোর্সটি অসাধারণ! শিক্ষক খুবই সহানুভূতিশীল এবং বিষয়বস্তু সহজবোধ্য।',
    },
    {
        id: 2,
        student: {
            name: 'সাবরিনা আক্তার',
            avatar: 'https://randomuser.me/api/portraits/women/44.jpg',
        },
        rating: 5,
        date: '২ দিন আগে',
        comment: 'আমি অনেক কিছু শিখেছি। ভিডিও গুলো খুবই পরিষ্কার এবং গঠনমূলক।',
    },
    {
        id: 3,
        student: {
            name: 'রাশেদুল ইসলাম',
            avatar: 'https://randomuser.me/api/portraits/men/45.jpg',
        },
        rating: 5,
        date: '৩ দিন আগে',
        comment: 'কোর্সের কনটেন্ট আপডেটেড এবং প্রাসঙ্গিক। ধন্যবাদ ইকরা একাডেমি!',
    },
    {
        id: 4,
        student: {
            name: 'নাজমুল হোসেন',
            avatar: 'https://randomuser.me/api/portraits/men/46.jpg',
        },
        rating: 5,
        date: '৪ দিন আগে',
        comment: 'শেখার জন্য খুবই ভালো একটি প্ল্যাটফর্ম। সকলের জন্য সুপারিশ করছি।',
    },
    {
        id: 5,
        student: {
            name: 'তানজিলা রহমান',
            avatar: 'https://randomuser.me/api/portraits/women/47.jpg',
        },
        rating: 5,
        date: '৫ দিন আগে',
        comment: 'ইনস্ট্রাক্টর খুবই দক্ষ এবং সহানুভূতিশীল। কোর্সটি উপভোগ করেছি।',
    },
    {
        id: 6,
        student: {
            name: 'মো. সাইফুল ইসলাম',
            avatar: 'https://randomuser.me/api/portraits/men/48.jpg',
        },
        rating: 5,
        date: '৬ দিন আগে',
        comment: 'কোর্সের প্রতিটি মডিউল খুব সুন্দরভাবে সাজানো। শেখার জন্য আদর্শ।',
    },
    {
        id: 7,
        student: {
            name: 'শারমিন আক্তার',
            avatar: 'https://randomuser.me/api/portraits/women/49.jpg',
        },
        rating: 5,
        date: '১ সপ্তাহ আগে',
        comment: 'প্র্যাকটিক্যাল উদাহরণগুলো খুবই সহায়ক ছিল। ধন্যবাদ।',
    },
    {
        id: 8,
        student: {
            name: 'জুবায়ের আহমেদ',
            avatar: 'https://randomuser.me/api/portraits/men/50.jpg',
        },
        rating: 5,
        date: '১ সপ্তাহ আগে',
        comment: 'কোর্সটি আমার ক্যারিয়ারে অনেক সাহায্য করেছে। highly recommended!',
    },
    {
        id: 9,
        student: {
            name: 'রিমা খাতুন',
            avatar: 'https://randomuser.me/api/portraits/women/51.jpg',
        },
        rating: 5,
        date: '১ সপ্তাহ আগে',
        comment: 'কোর্সের কনটেন্ট খুবই মানসম্মত এবং সহজবোধ্য।',
    },
    {
        id: 10,
        student: {
            name: 'মো. আব্দুল্লাহ',
            avatar: 'https://randomuser.me/api/portraits/men/52.jpg',
        },
        rating: 5,
        date: '২ সপ্তাহ আগে',
        comment: 'ইন্টারেক্টিভ কুইজ ও অ্যাসাইনমেন্টগুলো খুবই উপকারী ছিল।',
    },
    {
        id: 11,
        student: {
            name: 'সাদিয়া ইসলাম',
            avatar: 'https://randomuser.me/api/portraits/women/53.jpg',
        },
        rating: 5,
        date: '২ সপ্তাহ আগে',
        comment: 'আমি কোর্সটি সম্পূর্ণ করেছি এবং অনেক আত্মবিশ্বাস পেয়েছি।',
    },
    {
        id: 12,
        student: {
            name: 'আরিফুল ইসলাম',
            avatar: 'https://randomuser.me/api/portraits/men/54.jpg',
        },
        rating: 5,
        date: '৩ সপ্তাহ আগে',
        comment: 'তাজবীদের নিয়মগুলো এত সুন্দরভাবে বুঝিয়েছেন যে মনে রাখা খুবই সহজ হয়েছে। ধন্যবাদ উস্তাদকে।',
    },
]);

const reviewRating = computed(() => {
    if (!reviews.value || reviews.value.length === 0) return '0.0';
    const total = reviews.value.reduce((acc, review) => acc + (review.rating || 0), 0);
    return (total / reviews.value.length).toFixed(1);
});

const reviewCount = computed(() => (reviews.value ? reviews.value.length : 0));

const showDiscount = computed(() => {
    return props.course.discount_price && props.course.discount_expires_at && dayjs(props.course.discount_expires_at).isAfter(dayjs());
});

const discountExpiresIn = computed(() => {
    if (!props.course.discount_expires_at) return '';
    const expires = dayjs(props.course.discount_expires_at);
    const now = dayjs();
    if (expires.isBefore(now)) return '';
    const days = expires.diff(now, 'day');
    if (days > 0) return `ছাড় চলবে আর ${days} দিন`;
    const hours = expires.diff(now, 'hour');
    if (hours > 0) return `ছাড় চলবে আজ পর্যন্ত`;
    return '';
});

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('bn-BD').format(price);
};

const sanitizeHtml = (html: string): string => {
    if (!html) return '';
    // Create a new DOMParser to safely parse HTML without executing scripts
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    return doc.body.innerHTML || '';
};

const handleEnroll = () => {
    enrollLoading.value = true;
    const intendedUrl = route('frontend.payment.checkout', { course: props.course.slug });

    try {
        if (!props.isAuthenticated) {
            // Store the intended URL in the session before redirecting to login
            sessionStorage.setItem('intended_url', intendedUrl);
            router.visit(route('login'));
        } else {
            // If authenticated, proceed directly to payment
            router.visit(intendedUrl);
        }
    } catch (error) {
        console.error('Error during enrollment:', error);
        enrollLoading.value = false;
    }
};
</script>

<style scoped>
.prose :where(p):not(:where([class~='not-prose'] *)) {
    margin-top: 0;
    margin-bottom: 1.25rem;
}

.curriculum-module {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 2px 8px 0 rgba(95, 95, 205, 0.06);
    margin-bottom: 1.5rem;
    background: #fff;
    transition: box-shadow 0.3s;
}
.curriculum-module.expanded {
    box-shadow: 0 4px 16px 0 rgba(95, 95, 205, 0.12);
    background: linear-gradient(90deg, #f5f7fa 0%, #e9ecff 100%);
}
.curriculum-header {
    padding: 1.25rem 2rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    font-size: 1.15rem;
    font-weight: 600;
    color: #23235b;
    transition:
        background 0.2s,
        color 0.2s;
}
.curriculum-header:hover {
    background: #f0f4ff;
    color: #5f5fcd;
}
.curriculum-chevron {
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}
.curriculum-chevron.expanded {
    transform: rotate(180deg) scale(1.2);
}
.curriculum-lessons {
    padding: 1.25rem 2.5rem 1.5rem 2.5rem;
    background: #f8fafc;
    animation: fadeInUp 0.4s;
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.curriculum-lesson {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 1.05rem;
    color: #444;
    margin-bottom: 0.5rem;
    transition: color 0.2s;
}
.curriculum-lesson:hover {
    color: #5f5fcd;
}
.curriculum-dot {
    width: 0.5rem;
    height: 0.5rem;
    border-radius: 50%;
    background: linear-gradient(135deg, #5f5fcd 60%, #2d5a27 100%);
    box-shadow: 0 0 6px #5f5fcd44;
    animation: pulse 1.2s infinite alternate;
}
@keyframes pulse {
    to {
        box-shadow: 0 0 12px #5f5fcd88;
    }
}

.fade-slide-enter-active,
.fade-slide-leave-active {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.fade-slide-enter-from,
.fade-slide-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}
.fade-slide-enter-to,
.fade-slide-leave-from {
    opacity: 1;
    transform: translateY(0);
}
</style>
