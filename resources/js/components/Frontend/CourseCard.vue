<template>
    <div
        class="group shadow-islamic hover:shadow-islamic-lg relative transform overflow-hidden rounded-2xl border border-gray-100 bg-white transition-all duration-500 hover:-translate-y-2 hover:border-[#5f5fcd]/20"
    >
        <!-- Course Image with Enhanced Placeholder -->
        <div class="relative overflow-hidden">
            <img
                v-if="isValidImage(course.image)"
                :src="course.image"
                :alt="course.title"
                class="h-48 w-full object-cover transition-transform duration-700 group-hover:scale-110"
            />
            <CoursePlaceholder v-else class="h-48 w-full" :text="course.category" />

            <!-- Badges Container -->
            <div class="absolute top-3 left-3 flex flex-col space-y-2">
                <!-- Featured Badge -->
                <span
                    v-if="course.is_featured"
                    class="rounded-full border border-yellow-300 bg-gradient-to-r from-yellow-400 to-amber-500 px-3 py-1 text-xs font-semibold text-white shadow-lg"
                >
                    ফিচার্ড
                </span>
                <!-- Free Badge -->
                <span
                    v-if="course.is_free"
                    class="rounded-full border border-green-400 bg-gradient-to-r from-green-500 to-emerald-600 px-3 py-1 text-xs font-semibold text-white shadow-lg"
                >
                    ফ্রি
                </span>
            </div>

            <!-- Enhanced Image Overlay -->
            <div
                class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            ></div>

            <!-- Course Progress Overlay (if enrolled) -->
            <div
                v-if="course.enrolled && course.progress !== undefined"
                class="absolute right-0 bottom-0 left-0 bg-gradient-to-t from-black/80 to-transparent p-3"
            >
                <div class="h-1 overflow-hidden rounded-full bg-white">
                    <div class="h-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]" :style="{ width: course.progress + '%' }"></div>
                </div>
                <p class="mt-1 text-xs text-white">{{ course.progress }}% পূর্ণ হয়েছে</p>
            </div>
        </div>

        <!-- Enhanced Course Content -->
        <div class="p-6">
            <!-- Category & Duration with Enhanced Design -->
            <div class="mb-4 flex items-center justify-between">
                <span
                    class="inline-flex items-center rounded-full border border-[#5f5fcd]/25 bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 px-3 py-1 text-xs font-semibold text-[#5f5fcd] transition-all duration-200 hover:from-[#5f5fcd]/20 hover:to-[#2d5a27]/20"
                >
                    {{ course.category }}
                </span>
                <div class="flex items-center rounded-full bg-gray-50 px-2 py-1 text-xs text-gray-500">
                    <ClockIcon class="mr-1 h-3 w-3" />
                    {{ course.duration || 'স্ব-নির্ধারিত' }}
                </div>
            </div>

            <!-- Enhanced Course Title -->
            <Link :href="courseUrl" prefetch="hover" class="block focus:outline-none">
                <h3 class="mb-3 line-clamp-2 text-lg leading-tight font-bold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                    {{ course.title }}
                </h3>
            </Link>

            <!-- Price Section (add after course title/description, before action buttons) -->
            <div class="mb-4">
                <template v-if="course.is_free">
                    <span class="text-lg font-bold text-[#2d5a27]">ফ্রি</span>
                </template>
                <template v-else-if="showDiscount">
                    <span class="mr-2 text-lg font-bold text-[#e2136e]">৳{{ formatPrice(course.discount_price as number) }}</span>
                    <span class="text-sm text-gray-400 line-through">৳{{ formatPrice(course.price as number) }}</span>
                    <span v-if="discountExpiresIn" class="ml-2 rounded bg-yellow-100 px-2 py-0.5 text-xs font-semibold text-yellow-800">{{
                        discountExpiresIn
                    }}</span>
                </template>
                <template v-else>
                    <span class="text-lg font-bold text-gray-900">৳{{ formatPrice(course.price as number) }}</span>
                </template>
            </div>

            <!-- Enhanced Course Description -->
            <p class="mb-4 line-clamp-2 text-sm leading-relaxed text-gray-600" v-html="course.description"></p>

            <!-- Enhanced Course Meta -->
            <div class="mb-4 flex items-center justify-between text-sm">
                <!-- Student Count with Enhanced Design -->
                <div class="flex items-center rounded-full bg-gray-50 px-3 py-1">
                    <UsersIcon class="mr-1 h-4 w-4 text-[#5f5fcd]" />
                    <span class="font-medium text-gray-700">{{ formatNumber(course.students_count || 0) }} শিক্ষার্থী</span>
                </div>

                <!-- Enhanced Rating -->
                <div v-if="course.rating" class="flex items-center rounded-full bg-yellow-50 px-3 py-1">
                    <StarIcon class="mr-1 h-4 w-4 fill-yellow-500 text-yellow-500" />
                    <span class="font-medium text-gray-700">{{ Number(course.rating || 0).toFixed(1) }}</span>
                </div>
            </div>

            <!-- Enhanced Instructor Section -->
            <div
                v-if="course.instructor"
                class="mb-5 flex items-center rounded-xl border border-[#5f5fcd]/10 bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5 p-3"
            >
                <div class="relative">
                    <img
                        v-if="isValidImage(course.instructor.avatar)"
                        :src="course.instructor.avatar"
                        :alt="course.instructor.name"
                        class="h-10 w-10 rounded-full border-2 border-[#d4a574] shadow-sm"
                    />
                    <div
                        v-else
                        class="flex h-10 w-10 items-center justify-center rounded-full border-2 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] shadow-sm"
                    >
                        <span class="text-sm font-semibold text-white">
                            {{ getInitials(course.instructor.name) }}
                        </span>
                    </div>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-semibold text-gray-800">{{ course.instructor.name }}</p>
                    <p class="text-xs text-gray-500">ইন্সট্রাক্টর</p>
                </div>
            </div>

            <!-- Enhanced Action Buttons -->
            <div class="space-y-3">
                <Link :href="courseUrl" prefetch="hover" class="block">
                    <PrimaryButton
                        variant="primary"
                        size="sm"
                        full-width
                        :icon="BookOpenIcon"
                        class="transition-transform duration-200 hover:scale-105"
                    >
                        {{ course.price === 0 ? 'ফ্রি এনরোল' : 'এনরোল করুন' }}
                    </PrimaryButton>
                </Link>

                <Link v-if="course.enrolled" :href="continueUrl" prefetch="hover" class="block">
                    <PrimaryButton
                        variant="secondary"
                        size="sm"
                        full-width
                        :icon="PlayIcon"
                        class="transition-transform duration-200 hover:scale-105"
                    >
                        শেখা চালিয়ে যান
                    </PrimaryButton>
                </Link>
            </div>
        </div>

        <!-- Enhanced Islamic Decorative Corner -->
        <div class="absolute top-0 right-0 h-20 w-20 overflow-hidden">
            <div
                class="absolute top-3 right-3 h-10 w-10 rotate-45 transform rounded-lg bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/20 transition-transform duration-300 group-hover:scale-110"
            ></div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpenIcon, ClockIcon, PlayIcon, StarIcon, UsersIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import CoursePlaceholder from './CoursePlaceholder.vue';
import PrimaryButton from './PrimaryButton.vue';
// @ts-ignore
import dayjs from 'dayjs';

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
    enrolled?: boolean;
    progress?: number;
    isNew?: boolean;
    is_featured?: boolean;
    is_free?: boolean;
    instructor?: {
        name: string;
        avatar?: string;
    };
    discount_price?: number;
    discount_expires_at?: string;
}

interface Props {
    course: Course;
}

const props = defineProps<Props>();

const courseUrl = computed(() => `/courses/${props.course.slug}`);
const continueUrl = computed(() => `/courses/${props.course.slug}/learn`);

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('bn-BD').format(price);
};

const formatNumber = (num: number) => {
    if (num >= 1000000) {
        return (num / 1000000).toFixed(1) + 'M';
    }
    if (num >= 1000) {
        return (num / 1000).toFixed(1) + 'k';
    }
    return num.toString();
};

const getInitials = (name: string): string => {
    return name
        .split(' ')
        .map((word) => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

// Utility function to check for a valid image
const isValidImage = (src: string | undefined | null): boolean => {
    return !!src && typeof src === 'string' && src.trim() !== '' && !src.includes('undefined');
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
</script>

<style scoped>
/* Enhanced line clamp utilities */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Enhanced Islamic-inspired hover effects */
.group:hover .islamic-pattern {
    opacity: 1;
    transform: scale(1.05);
}

/* Enhanced card float animation */
@keyframes cardFloat {
    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-6px) rotate(0.5deg);
    }
}

.group:hover {
    animation: cardFloat 3s ease-in-out infinite;
}

/* Enhanced shadow animation */
@keyframes shadowPulse {
    0%,
    100% {
        box-shadow:
            0 4px 12px rgba(95, 95, 205, 0.1),
            0 2px 4px rgba(45, 90, 39, 0.05);
    }
    50% {
        box-shadow:
            0 8px 25px rgba(95, 95, 205, 0.2),
            0 4px 8px rgba(45, 90, 39, 0.1);
    }
}

.group:hover {
    animation: shadowPulse 2s ease-in-out infinite;
}

/* Enhanced focus states for accessibility */
.group:focus-within {
    outline: 2px solid #5f5fcd;
    outline-offset: 2px;
    border-radius: 16px;
}

/* Custom scrollbar for long content */
::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #5f5fcd;
    border-radius: 2px;
}

/* Enhanced button hover effects */
.group:hover button {
    transform: scale(1.05);
}

/* Enhanced image hover effects */
.group:hover img {
    filter: brightness(1.1) contrast(1.1);
}

/* Enhanced placeholder animation */
.group:hover .course-placeholder {
    transform: scale(1.02);
    transition: transform 0.3s ease;
}
</style>
