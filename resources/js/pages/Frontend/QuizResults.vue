<template>
    <FrontendLayout :title="`${quiz.title} - ফলাফল`">
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="mx-auto max-w-2xl px-4">
                <!-- Results Card -->
                <div class="rounded-lg border bg-white p-8 text-center shadow-sm">
                    <!-- Icon -->
                    <div
                        class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full"
                        :class="attempt.is_passed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
                    >
                        <svg v-if="attempt.is_passed" class="h-10 w-10" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        <svg v-else class="h-10 w-10" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </div>

                    <!-- Status -->
                    <h1 class="mb-2 text-3xl font-bold" :class="attempt.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ attempt.is_passed ? 'উত্তীর্ণ!' : 'অনুত্তীর্ণ' }}
                    </h1>

                    <!-- Score -->
                    <div class="mb-4 text-5xl font-bold" :class="attempt.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ Math.round(attempt.score) }}%
                    </div>

                    <!-- Details -->
                    <div class="mb-8 rounded-2xl bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 p-6">
                        <div class="grid grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="mb-1 text-2xl font-bold text-[#5f5fcd]">{{ attempt.correct_answers }}</div>
                                <div class="text-sm font-medium text-[#6c757d]">সঠিক উত্তর</div>
                            </div>
                            <div class="border-x border-gray-200 text-center">
                                <div class="mb-1 text-2xl font-bold text-[#6c757d]">{{ attempt.total_questions }}</div>
                                <div class="text-sm font-medium text-[#6c757d]">মোট প্রশ্ন</div>
                            </div>
                            <div class="text-center">
                                <div class="mb-1 text-2xl font-bold text-[#2d5a27]">{{ quiz.passing_score }}%</div>
                                <div class="text-sm font-medium text-[#6c757d]">পাসিং স্কোর</div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Message -->
                    <div v-if="attempt.is_passed" class="mb-8 rounded-xl bg-gradient-to-r from-[#2d5a27]/10 to-[#198754]/10 p-4">
                        <p class="font-semibold text-[#2d5a27]">🎉 অভিনন্দন! আপনি সফলভাবে কুইজটি সম্পন্ন করেছেন।</p>
                    </div>
                    <div v-else class="to-red-25 mb-8 rounded-xl bg-gradient-to-r from-red-50 p-4">
                        <p class="font-semibold text-red-600">😔 দুঃখিত! আরেকবার চেষ্টা করুন।</p>
                    </div>

                    <!-- Action Button -->
                    <button
                        @click="goBack"
                        class="hover:shadow-islamic-lg group inline-flex transform items-center rounded-xl bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-10 py-4 text-lg font-semibold text-white transition-all duration-300 hover:scale-105"
                    >
                        <svg class="mr-3 h-5 w-5 transition-transform group-hover:-translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H17a1 1 0 110 2H6.414l2.293 2.293a1 1 0 010 1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        কোর্সে ফিরুন
                    </button>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Props
const page = usePage();
const quiz = computed(() => page.props.quiz);
const attempt = computed(() => page.props.attempt);

// Methods
const goBack = () => {
    router.visit(`/learning/${quiz.value.course.slug}`);
};
</script>
