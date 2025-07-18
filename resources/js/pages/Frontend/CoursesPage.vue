<template>
    <FrontendLayout title="কোর্সসমূহ - ইকরা অনলাইন একাডেমি">
        <Head title="কোর্সসমূহ - ইকরা অনলাইন একাডেমি">
            <meta
                name="description"
                content="ইকরা অনলাইন একাডেমির কোর্সসমূহ - কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিস্তৃত কোর্স সংগ্রহ। অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা গ্রহণ করুন।"
            />
            <meta name="keywords" content="ইসলামিক কোর্স, কুরআন কোর্স, হাদিস কোর্স, ফিকহ কোর্স, অনলাইন শিক্ষা, ইকরা কোর্স" />
        </Head>
        <!-- Page Header -->
        <section class="relative overflow-hidden bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20">
            <!-- Background Pattern -->
            <div class="pattern-dots absolute inset-0 opacity-10"></div>

            <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <div class="mb-6 inline-flex items-center rounded-full border border-white/20 bg-white/10 px-4 py-2 backdrop-blur-sm">
                        <BookOpenIcon class="mr-2 h-4 w-4 text-white" />
                        <span class="text-sm font-medium text-white">আমাদের কোর্সসমূহ</span>
                    </div>

                    <h1 class="mb-6 text-4xl font-bold text-white lg:text-6xl">
                        ইসলামিক
                        <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">শিক্ষার</span>
                        জগত
                    </h1>

                    <p class="mx-auto max-w-3xl text-xl leading-relaxed text-gray-300">
                        কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিস্তৃত কোর্স সংগ্রহ। অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে আধুনিক পদ্ধতিতে ইসলামিক
                        শিক্ষা গ্রহণ করুন।
                    </p>
                </div>
            </div>
        </section>

        <!-- Course Grid -->
        <section class="py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Results -->
                <div>
                    <!-- Results Summary -->
                    <div class="mb-8">
                        <p class="text-gray-600">
                            <span class="font-semibold text-gray-900">{{ totalCourses }}</span> টি কোর্স পাওয়া গেছে
                        </p>
                    </div>

                    <!-- Course Grid -->
                    <div v-if="allCourses.length > 0" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        <CourseCard v-for="course in allCourses" :key="course.id" :course="course" @enroll="handleCourseEnroll" />
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="allCourses.length === 0" class="py-16 text-center">
                        <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-gray-100">
                            <BookOpenIcon class="h-10 w-10 text-gray-400" />
                        </div>
                        <h3 class="mb-4 text-xl font-semibold text-gray-900">কোনো কোর্স পাওয়া যায়নি</h3>
                        <p class="mb-6 text-gray-600">দুঃখিত, বর্তমানে কোনো কোর্স উপলব্ধ নেই</p>
                    </div>

                    <!-- Infinite Scroll Trigger -->
                    <div v-if="hasMoreCourses && allCourses.length > 0" class="mt-12">
                        <!-- Loading indicator -->
                        <div v-if="isLoading" class="py-8 text-center">
                            <div class="inline-flex items-center space-x-2 text-gray-600">
                                <div class="h-6 w-6 animate-spin rounded-full border-b-2 border-[#5f5fcd]"></div>
                                <span>আরো কোর্স লোড হচ্ছে...</span>
                            </div>
                        </div>
                        <!-- Invisible trigger element -->
                        <div ref="loadMoreTrigger" class="h-20 w-full"></div>
                    </div>

                    <!-- Manual load more button (fallback) -->
                    <div v-else-if="hasMoreCourses && allCourses.length > 0" class="mt-12 text-center">
                        <PrimaryButton @click="loadMoreCourses" :loading="isLoading" size="lg" variant="outline"> আরো কোর্স দেখুন </PrimaryButton>
                    </div>
                </div>
            </div>
        </section>
    </FrontendLayout>
</template>

<script setup lang="ts">
import CourseCard from '@/components/Frontend/CourseCard.vue';
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { useIntersectionObserver } from '@vueuse/core';
import { BookOpenIcon } from 'lucide-vue-next';
import { computed, onUnmounted, ref } from 'vue';

// Props
interface Props {
    courses: {
        data: Course[];
        links: any[];
        next_page_url: string | null;
        prev_page_url: string | null;
        current_page: number;
        last_page: number;
        total: number;
    };
    search?: string;
    stats?: {
        total_courses: number;
        total_categories: number;
    };
}

const props = defineProps<Props>();

// Type definitions
interface Course {
    id: number;
    slug: string;
    title: string;
    description: string;
    image?: string;
    category: string;
    level: string;
    price: number;
    duration?: string;
    students_count?: number;
    rating?: number;
    instructor?: {
        name: string;
        avatar?: string;
    };
    isNew?: boolean;
    enrolled?: boolean;
    progress?: number;
    created_at?: string;
}

// State for infinite scroll
const allCourses = ref<Course[]>([]);
const isLoading = ref(false);
const loadMoreTrigger = ref<HTMLElement>();

// Computed properties
const hasMoreCourses = computed(() => props.courses?.next_page_url !== null);
const totalCourses = computed(() => props.courses?.total || 0);

// Initialize courses from props
if (props.courses?.data) {
    allCourses.value = [...props.courses.data];
}

// Setup intersection observer for infinite scroll
const { stop } = useIntersectionObserver(
    loadMoreTrigger,
    ([{ isIntersecting }]) => {
        if (isIntersecting && hasMoreCourses.value && !isLoading.value) {
            loadMoreCourses();
        }
    },
    {
        threshold: 0.1,
        rootMargin: '100px',
    },
);

// Methods
const loadMoreCourses = () => {
    if (!props.courses?.next_page_url || isLoading.value) return;

    isLoading.value = true;

    router.get(
        props.courses.next_page_url,
        { merge: true },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                // Append new courses to existing list
                const newCourses = page.props.courses?.data || [];
                allCourses.value = [...allCourses.value, ...newCourses];
            },
            onFinish: () => {
                isLoading.value = false;
            },
        },
    );
};

const handleCourseEnroll = (course: Course) => {
    router.visit(route('frontend.payment.checkout', { course: course.slug }));
};

// Cleanup intersection observer on unmount
onUnmounted(() => {
    stop();
});
</script>

<style scoped>
/* Custom styles for the courses page */
.text-gradient-islamic {
    background-size: 200% 200%;
    animation: gradientShift 4s ease-in-out infinite;
}

@keyframes gradientShift {
    0%,
    100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}
</style>
