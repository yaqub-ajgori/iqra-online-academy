<template>
    <div
        class="group shadow-islamic hover:shadow-islamic-lg relative overflow-hidden rounded-2xl border border-gray-100 bg-white p-8 transition-all duration-300"
    >
        <!-- Quote Icon -->
        <div class="relative mb-6">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10">
                <QuoteIcon class="h-6 w-6 text-[#5f5fcd]" />
            </div>
        </div>

        <!-- Testimonial Content -->
        <div class="relative mb-6">
            <p class="text-lg leading-relaxed text-gray-700 italic">"{{ testimonial.content }}"</p>
        </div>

        <!-- Rating Stars -->
        <div class="mb-6 flex items-center space-x-1">
            <StarIcon
                v-for="star in 5"
                :key="star"
                :class="['h-5 w-5 transition-colors duration-200', star <= testimonial.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300']"
            />
            <span class="ml-2 text-sm text-gray-500">({{ testimonial.rating_bn || testimonial.rating }}/৫)</span>
        </div>

        <!-- Student Information -->
        <div class="flex items-center space-x-4">
            <!-- Avatar -->
            <div class="relative">
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full border-3 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                >
                    <component v-if="testimonial.student.avatar" :is="testimonial.student.avatar" class="h-8 w-8 text-[#5f5fcd]" />
                    <span v-else class="text-lg font-semibold text-white">
                        {{ getInitials(testimonial.student.name) }}
                    </span>
                </div>

                <!-- Verified Badge -->
                <div v-if="testimonial.verified" class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full bg-[#2d5a27]">
                    <CheckIcon class="h-3 w-3 text-white" />
                </div>
            </div>

            <!-- Student Details -->
            <div class="flex-1">
                <h4 class="font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                    {{ testimonial.student.name }}
                </h4>
                <p class="text-sm text-gray-500">
                    {{ testimonial.student.title || 'শিক্ষার্থী' }}
                </p>
                <div v-if="testimonial.student.location" class="mt-1 flex items-center text-xs text-gray-400">
                    <MapPinIcon class="mr-1 h-3 w-3" />
                    {{ testimonial.student.location }}
                </div>
            </div>

            <!-- Date -->
            <div class="text-right">
                <time class="text-xs text-gray-400" :datetime="testimonial.created_at">
                    {{ formatDate(testimonial.created_at) }}
                </time>
            </div>
        </div>

        <!-- Islamic Decorative Element -->
        <div
            class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
        ></div>
    </div>
</template>

<script setup lang="ts">
import { CheckIcon, MapPinIcon, QuoteIcon, StarIcon } from 'lucide-vue-next';
import type { Component } from 'vue';

interface Student {
    name: string;
    avatar?: Component;
    title?: string;
    location?: string;
}

interface Testimonial {
    id: number;
    content: string;
    rating: number;
    rating_bn?: string;
    student: Student;
    verified: boolean;
    created_at: string;
}

interface Props {
    testimonial: Testimonial;
    variant?: 'default' | 'compact' | 'featured';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
});

const getInitials = (name: string): string => {
    return name
        .split(' ')
        .map((word) => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    const months = ['জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন', 'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'];

    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    return `${day} ${month}, ${year}`;
};
</script>

<style scoped>
/* Islamic-inspired hover effects */
@keyframes testimonialFloat {
    0%,
    100% {
        transform: translateY(0px);
    }
    50% {
        transform: translateY(-5px);
    }
}

.group:hover {
    animation: testimonialFloat 3s ease-in-out infinite;
}

/* Quote styling enhancement */
.group:hover .quote-decoration {
    opacity: 1;
    transform: scale(1.1);
}

/* Gradient text effect for featured testimonials */
.text-gradient-testimonial {
    background: linear-gradient(135deg, #5f5fcd 0%, #2d5a27 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Islamic decorative border animation */
@keyframes borderGlow {
    0%,
    100% {
        box-shadow: 0 0 5px rgba(95, 95, 205, 0.1);
    }
    50% {
        box-shadow: 0 0 20px rgba(95, 95, 205, 0.2);
    }
}

.group:hover {
    animation: borderGlow 2s ease-in-out infinite;
}
</style>
