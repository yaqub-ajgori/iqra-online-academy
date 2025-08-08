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

        <!-- Review Title (if exists) -->
        <h3 v-if="testimonial.title" class="mb-3 text-lg font-semibold text-gray-900">{{ testimonial.title }}</h3>

        <!-- Testimonial Content -->
        <div class="relative mb-4">
            <p class="text-lg leading-relaxed text-gray-700 italic">"{{ testimonial.comment }}"</p>
        </div>

        <!-- See More Button (for long reviews) -->
        <div v-if="testimonial.is_long" class="mb-4">
            <button
                @click="showModal = true"
                class="inline-flex items-center text-sm font-medium text-[#5f5fcd] transition-colors duration-200 hover:text-[#2d5a27]"
            >
                বিস্তারিত দেখুন
                <ChevronRightIcon class="ml-1 h-4 w-4" />
            </button>
        </div>

        <!-- Rating Stars -->
        <div class="mb-6 flex items-center space-x-1">
            <StarIcon
                v-for="star in 5"
                :key="star"
                :class="['h-5 w-5 transition-colors duration-200', star <= testimonial.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300']"
            />
            <span class="ml-2 text-sm text-gray-500">({{ testimonial.rating }}/৫)</span>
        </div>

        <!-- Student Information -->
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Enhanced Avatar -->
                <div
                    class="flex h-14 w-14 items-center justify-center rounded-full border-2 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd]/20 to-[#2d5a27]/20 shadow-sm"
                >
                    <span class="text-base font-bold text-[#5f5fcd]">
                        {{ getInitials(testimonial.name) }}
                    </span>
                </div>

                <!-- Enhanced Student Details -->
                <div>
                    <h4 class="text-base font-bold text-gray-900 transition-colors duration-200 group-hover:text-[#5f5fcd]">
                        {{ testimonial.name }}
                    </h4>
                    <p class="text-sm font-medium text-gray-600">ইকরা একাডেমি শিক্ষার্থী</p>
                    <time class="text-xs font-medium text-[#d4a574]">{{ testimonial.date }}</time>
                </div>
            </div>
        </div>

        <!-- Islamic Decorative Element -->
        <div
            class="absolute bottom-0 left-0 h-1 w-full bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"
        ></div>
    </div>

    <!-- Review Details Modal -->
    <Teleport to="body">
        <div v-show="showModal" class="bg-opacity-50 fixed inset-0 z-50 flex items-center justify-center bg-black p-4" @click="showModal = false">
            <div class="max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-2xl bg-white" @click.stop>
                <div class="p-8">
                    <!-- Modal Header -->
                    <div class="mb-6 flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900">বিস্তারিত রিভিউ</h2>
                        <button @click="showModal = false" class="text-gray-400 transition-colors duration-200 hover:text-gray-600">
                            <XIcon class="h-6 w-6" />
                        </button>
                    </div>

                    <!-- Review Details -->
                    <div class="space-y-6">
                        <!-- Student Info -->
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex h-12 w-12 items-center justify-center rounded-full border-2 border-[#d4a574] bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10"
                            >
                                <span class="text-sm font-semibold text-[#5f5fcd]">
                                    {{ getInitials(testimonial.name) }}
                                </span>
                            </div>
                            <div>
                                <h4 class="flex items-center font-semibold text-gray-900">
                                    {{ testimonial.name }}
                                    <CheckIcon v-if="testimonial.verified" class="ml-2 h-4 w-4 text-[#2d5a27]" />
                                </h4>
                                <p class="text-sm text-gray-500">{{ testimonial.date }}</p>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="flex items-center space-x-2">
                            <StarIcon
                                v-for="star in 5"
                                :key="star"
                                :class="[
                                    'h-6 w-6 transition-colors duration-200',
                                    star <= testimonial.rating ? 'fill-yellow-400 text-yellow-400' : 'text-gray-300',
                                ]"
                            />
                            <span class="ml-2 text-lg font-medium text-gray-700">({{ testimonial.rating }}/৫)</span>
                        </div>

                        <!-- Review Title -->
                        <h3 v-if="testimonial.title" class="text-xl font-semibold text-gray-900">{{ testimonial.title }}</h3>

                        <!-- Full Review Text -->
                        <div class="prose prose-lg max-w-none">
                            <p class="leading-relaxed text-gray-700">"{{ testimonial.full_review }}"</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { CheckIcon, ChevronRightIcon, QuoteIcon, StarIcon, XIcon } from 'lucide-vue-next';
import { ref } from 'vue';

interface Testimonial {
    id: number | string;
    name: string;
    rating: number;
    title?: string;
    comment: string;
    full_review: string;
    avatar?: string;
    verified: boolean;
    date: string;
    is_long: boolean;
}

interface Props {
    testimonial: Testimonial;
    variant?: 'default' | 'compact' | 'featured';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
});

const showModal = ref(false);

const getInitials = (name: string): string => {
    return name
        .split(' ')
        .map((word) => word.charAt(0))
        .join('')
        .toUpperCase()
        .slice(0, 2);
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
