<template>
    <FrontendLayout :title="quiz.title">
        <div class="min-h-screen bg-gradient-to-br from-[#fdfcff] to-[#fafbf9] py-8">
            <div class="mx-auto max-w-2xl px-4">
                <!-- Quiz Header -->
                <div class="shadow-islamic mb-6 rounded-2xl border border-gray-100 bg-white p-8">
                    <div class="mb-6 text-center">
                        <div
                            class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]"
                        >
                            <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h1 class="text-gradient-islamic mb-3 text-3xl font-bold">{{ quiz.title }}</h1>
                        <p v-if="quiz.description" class="mb-4 leading-relaxed text-[#6c757d]">{{ quiz.description }}</p>
                        <div
                            class="inline-flex items-center rounded-full border border-[#5f5fcd]/25 bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 px-4 py-2"
                        >
                            <svg class="mr-2 h-4 w-4 text-[#5f5fcd]" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            <span class="text-sm font-semibold text-[#5f5fcd]">মোট প্রশ্ন: {{ quiz.questions.length }} টি</span>
                        </div>
                    </div>
                </div>

                <!-- Question Card -->
                <div v-if="currentQuestion" class="shadow-islamic mb-6 rounded-2xl border border-gray-100 bg-white p-8">
                    <!-- Progress -->
                    <div class="mb-6">
                        <div class="mb-3 flex items-center justify-between">
                            <span
                                class="inline-flex items-center rounded-full bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 px-3 py-1 text-sm font-semibold text-[#5f5fcd]"
                            >
                                প্রশ্ন {{ currentQuestionIndex + 1 }} / {{ quiz.questions.length }}
                            </span>
                            <span class="text-sm font-medium text-[#6c757d]">
                                {{ Math.round(((currentQuestionIndex + 1) / quiz.questions.length) * 100) }}% সম্পন্ন
                            </span>
                        </div>
                        <div class="h-3 w-full rounded-full bg-gradient-to-r from-gray-100 to-gray-200 shadow-inner">
                            <div
                                class="h-3 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] shadow-sm transition-all duration-500"
                                :style="{ width: ((currentQuestionIndex + 1) / quiz.questions.length) * 100 + '%' }"
                            ></div>
                        </div>
                    </div>

                    <!-- Question -->
                    <div class="mb-8">
                        <h2 class="mb-2 text-xl leading-relaxed font-bold text-[#212529]">{{ currentQuestion.question }}</h2>
                        <div class="h-1 w-20 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]"></div>
                    </div>

                    <!-- Multiple Choice -->
                    <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-4">
                        <label
                            v-for="(option, index) in currentQuestion.options"
                            :key="index"
                            class="hover:shadow-islamic group flex cursor-pointer items-start rounded-xl border-2 p-4 transition-all duration-300"
                            :class="
                                answers[currentQuestion.id] == index
                                    ? 'shadow-islamic border-[#5f5fcd] bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5'
                                    : 'border-gray-200 hover:border-[#5f5fcd]/50 hover:bg-gray-50'
                            "
                        >
                            <input
                                type="radio"
                                :value="index"
                                v-model="answers[currentQuestion.id]"
                                class="mt-1 mr-4 text-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]"
                            />
                            <span class="font-medium text-[#212529] transition-colors group-hover:text-[#5f5fcd]">{{ option }}</span>
                        </label>
                    </div>

                    <!-- True/False -->
                    <div v-else-if="currentQuestion.type === 'true_false'" class="space-y-4">
                        <label
                            class="hover:shadow-islamic group flex cursor-pointer items-center rounded-xl border-2 p-4 transition-all duration-300"
                            :class="
                                answers[currentQuestion.id] === 'সত্য'
                                    ? 'shadow-islamic border-[#2d5a27] bg-gradient-to-r from-[#2d5a27]/10 to-[#2d5a27]/5'
                                    : 'border-gray-200 hover:border-[#2d5a27]/50 hover:bg-gray-50'
                            "
                        >
                            <input
                                type="radio"
                                value="সত্য"
                                v-model="answers[currentQuestion.id]"
                                class="mr-4 text-[#2d5a27] focus:ring-2 focus:ring-[#2d5a27]"
                            />
                            <div class="flex items-center">
                                <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-[#2d5a27]/20">
                                    <svg class="h-4 w-4 text-[#2d5a27]" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <span class="font-medium text-[#212529] transition-colors group-hover:text-[#2d5a27]">সত্য</span>
                            </div>
                        </label>
                        <label
                            class="hover:shadow-islamic group flex cursor-pointer items-center rounded-xl border-2 p-4 transition-all duration-300"
                            :class="
                                answers[currentQuestion.id] === 'মিথ্যা'
                                    ? 'to-red-25 shadow-islamic border-red-500 bg-gradient-to-r from-red-50'
                                    : 'border-gray-200 hover:border-red-400 hover:bg-gray-50'
                            "
                        >
                            <input
                                type="radio"
                                value="মিথ্যা"
                                v-model="answers[currentQuestion.id]"
                                class="mr-4 text-red-500 focus:ring-2 focus:ring-red-500"
                            />
                            <div class="flex items-center">
                                <div class="mr-3 flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
                                    <svg class="h-4 w-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <span class="font-medium text-[#212529] transition-colors group-hover:text-red-500">মিথ্যা</span>
                            </div>
                        </label>
                    </div>

                    <!-- Short Answer -->
                    <div v-else-if="currentQuestion.type === 'short_answer'">
                        <div class="relative">
                            <input
                                v-model="answers[currentQuestion.id]"
                                type="text"
                                class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 p-4 transition-all duration-300 focus:border-[#5f5fcd] focus:bg-white focus:ring-2 focus:ring-[#5f5fcd]/20 focus:outline-none"
                                placeholder="আপনার উত্তর লিখুন..."
                            />
                            <div class="absolute top-0 left-4 -mt-2 bg-white px-2 text-sm font-medium text-[#6c757d]">উত্তর</div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="shadow-islamic rounded-2xl border border-gray-100 bg-white p-8">
                    <div class="flex items-center justify-between">
                        <button
                            v-if="currentQuestionIndex > 0"
                            @click="previousQuestion"
                            class="group inline-flex items-center rounded-xl border-2 border-gray-200 px-6 py-3 font-semibold text-[#6c757d] transition-all duration-300 hover:border-[#5f5fcd]/50 hover:bg-gray-50 hover:text-[#5f5fcd]"
                        >
                            <svg class="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            পূর্ববর্তী
                        </button>
                        <div v-else></div>

                        <button
                            v-if="currentQuestionIndex < quiz.questions.length - 1"
                            @click="nextQuestion"
                            class="hover:shadow-islamic-lg group inline-flex transform items-center rounded-xl bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-8 py-3 font-semibold text-white transition-all duration-300 hover:scale-105"
                        >
                            পরবর্তী
                            <svg class="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <button
                            v-else
                            @click="submitQuiz"
                            :disabled="!canSubmit"
                            class="hover:shadow-islamic-lg group inline-flex transform items-center rounded-xl bg-gradient-to-r from-[#2d5a27] to-[#198754] px-8 py-3 font-semibold text-white transition-all duration-300 hover:scale-105 disabled:transform-none disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <svg class="mr-2 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                            জমা দিন
                        </button>
                    </div>
                    <div v-if="!canSubmit && currentQuestionIndex === quiz.questions.length - 1" class="mt-4 text-center">
                        <p class="text-sm text-[#6c757d]">সব প্রশ্নের উত্তর দিন জমা দিতে</p>
                    </div>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import FrontendLayout from '@/layouts/FrontendLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

// Props
const page = usePage();
const quiz = computed(() => page.props.quiz);

// State
const currentQuestionIndex = ref(0);
const answers = ref({});

// Computed
const currentQuestion = computed(() => {
    return quiz.value.questions[currentQuestionIndex.value];
});

const canSubmit = computed(() => {
    // Check if all questions are answered
    return quiz.value.questions.every((question) => {
        const answer = answers.value[question.id];
        return answer !== undefined && answer !== null && answer !== '';
    });
});

// Methods
const nextQuestion = () => {
    if (currentQuestionIndex.value < quiz.value.questions.length - 1) {
        currentQuestionIndex.value++;
    }
};

const previousQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--;
    }
};

const submitQuiz = () => {
    router.post(`/quiz/${quiz.value.id}/submit`, {
        answers: answers.value,
    });
};
</script>
