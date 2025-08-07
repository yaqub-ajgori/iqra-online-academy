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
                                <!-- Reviews Header -->
                                <div v-if="course.reviews && course.reviews.length > 0" class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">শিক্ষার্থীদের রিভিউ</h3>
                                    <div class="flex items-center space-x-4 text-sm text-gray-600">
                                        <div class="flex items-center space-x-1">
                                            <StarIcon class="h-4 w-4 fill-yellow-400 text-yellow-400" />
                                            <span class="font-medium">{{ course.rating }}/৫</span>
                                        </div>
                                        <span>{{ course.reviews_count }} টি রিভিউ</span>
                                    </div>
                                </div>

                                <!-- Reviews List -->
                                <div v-for="review in course.reviews" :key="review.id" class="border-b border-gray-200 pb-6">
                                    <div class="flex items-start space-x-4">
                                        <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10">
                                            <span class="text-sm font-semibold text-[#5f5fcd]">
                                                {{ getInitials(review.student.name) }}
                                            </span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-3">
                                                    <h5 class="font-semibold text-gray-900">{{ review.student.name }}</h5>
                                                    <CheckIcon v-if="review.is_verified_purchase" class="h-4 w-4 text-[#2d5a27]" title="যাচাইকৃত ক্রয়" />
                                                </div>
                                                <span class="text-sm text-gray-500">{{ review.date }}</span>
                                            </div>
                                            
                                            <!-- Rating -->
                                            <div class="mb-3 flex items-center space-x-1">
                                                <StarIcon
                                                    v-for="star in 5"
                                                    :key="star"
                                                    :class="[
                                                        'h-4 w-4',
                                                        star <= review.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300',
                                                    ]"
                                                />
                                            </div>

                                            <!-- Review Title -->
                                            <h4 v-if="review.title" class="font-medium text-gray-900 mb-2">{{ review.title }}</h4>

                                            <!-- Review Content -->
                                            <p class="text-gray-700 mb-3">{{ review.comment }}</p>

                                            <!-- See More Button -->
                                            <div v-if="review.is_long" class="mb-3">
                                                <button
                                                    @click="showReviewModal(review)"
                                                    class="inline-flex items-center text-sm text-[#5f5fcd] hover:text-[#2d5a27] transition-colors duration-200 font-medium"
                                                >
                                                    বিস্তারিত দেখুন
                                                    <ChevronDownIcon class="ml-1 h-4 w-4" />
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <!-- No reviews message -->
                                <div v-if="!course.reviews || course.reviews.length === 0" class="py-12 text-center">
                                    <StarIcon class="mx-auto mb-4 h-12 w-12 text-gray-400" />
                                    <h3 class="mb-2 text-lg font-medium text-gray-900">এখনো কোনো রিভিউ নেই</h3>
                                    <p class="text-gray-500">এই কোর্সের জন্য প্রথম রিভিউটি লিখুন।</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Card -->
                    <div class="lg:col-span-1">
                        <div class="sticky top-8">
                            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-2xl">
                                <!-- Course Image -->
                                <div v-if="course.image" class="relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                    <img
                                        :src="course.image"
                                        :alt="course.title"
                                        class="h-64 w-full object-cover object-center"
                                        loading="lazy"
                                    />
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
                                </div>
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

    <!-- Review Details Modal -->
    <Teleport to="body">
        <div
            v-show="showModalState"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50"
            @click="closeReviewModal"
        >
            <div
                class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto"
                @click.stop
            >
                <div class="p-8" v-if="selectedReview">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">বিস্তারিত রিভিউ</h2>
                        <button
                            @click="closeReviewModal"
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                        >
                            <XIcon class="h-6 w-6" />
                        </button>
                    </div>

                    <!-- Review Details -->
                    <div class="space-y-6">
                        <!-- Student Info -->
                        <div class="flex items-center space-x-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10">
                                <span class="text-sm font-semibold text-[#5f5fcd]">
                                    {{ getInitials(selectedReview.student.name) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 flex items-center">
                                    {{ selectedReview.student.name }}
                                    <CheckIcon v-if="selectedReview.is_verified_purchase" class="ml-2 h-4 w-4 text-[#2d5a27]" />
                                </h4>
                                <p class="text-sm text-gray-500">{{ selectedReview.date }}</p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center space-x-2">
                            <StarIcon
                                v-for="star in 5"
                                :key="star"
                                :class="['h-6 w-6 transition-colors duration-200', star <= selectedReview.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300']"
                            />
                            <span class="ml-2 text-lg font-medium text-gray-700">({{ selectedReview.rating }}/৫)</span>
                        </div>

                        <!-- Review Title -->
                        <h3 v-if="selectedReview.title" class="text-xl font-semibold text-gray-900">{{ selectedReview.title }}</h3>

                        <!-- Full Review Text -->
                        <div class="prose prose-lg max-w-none">
                            <p class="text-gray-700 leading-relaxed">"{{ selectedReview.full_review }}"</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { BookOpenIcon, CheckIcon, ChevronDownIcon, ClockIcon, ImageIcon, StarIcon, UsersIcon, XIcon } from 'lucide-vue-next';
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
            avatar?: string;
        };
        rating: number;
        title?: string;
        date: string;
        comment: string;
        full_review: string;
        is_long: boolean;
        is_verified_purchase: boolean;
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

// Modal state for review details
const selectedReview = ref(null);
const showModalState = ref(false);

// Reviews functionality - now uses course.reviews from props
const showReviewModal = (review) => {
    selectedReview.value = review;
    showModalState.value = true;
};

const closeReviewModal = () => {
    showModalState.value = false;
    selectedReview.value = null;
};

// Keep compatibility with existing computed properties
const reviews = computed(() => props.course.reviews || []);

const reviewRating = computed(() => {
    if (!reviews.value || reviews.value.length === 0) return '0.0';
    const total = reviews.value.reduce((acc, review) => acc + (review.rating || 0), 0);
    return (total / reviews.value.length).toFixed(1);
});

const reviewCount = computed(() => (reviews.value ? reviews.value.length : 0));

// Helper function for getting initials from name
const getInitials = (name: string): string => {
    return name.split(' ').map(word => word.charAt(0)).join('').toUpperCase().substring(0, 2);
};

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
