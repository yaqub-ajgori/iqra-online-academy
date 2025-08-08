<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <Head :title="quiz.title" />

        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <button
                    @click="goBack"
                    class="inline-flex items-center text-gray-600 hover:text-[#5f5fcd] transition-colors"
                >
                    <ChevronLeftIcon class="mr-2 h-5 w-5" />
                    <span>পাঠে ফিরে যান</span>
                </button>
            </div>

            <!-- Quiz Header -->
            <div class="mb-8 rounded-xl bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] p-6 text-white shadow-lg">
                <h1 class="mb-2 text-2xl font-bold">{{ quiz.title }}</h1>
                <p v-if="quiz.description" class="text-white/90">{{ quiz.description }}</p>
                
                <div class="mt-4 flex flex-wrap gap-4 text-sm">
                    <span class="flex items-center">
                        <HelpCircleIcon class="mr-1 h-4 w-4" />
                        {{ quiz.total_questions }} প্রশ্ন
                    </span>
                    <span v-if="quiz.time_limit_minutes" class="flex items-center">
                        <ClockIcon class="mr-1 h-4 w-4" />
                        {{ quiz.time_limit_minutes }} মিনিট
                    </span>
                    <span class="flex items-center">
                        <CheckCircleIcon class="mr-1 h-4 w-4" />
                        পাশ: {{ quiz.passing_score }}%
                    </span>
                </div>
            </div>


            <!-- Quiz Start Screen -->
            <div v-if="currentScreen === 'start'" class="rounded-xl border bg-white p-8 shadow-sm text-center">
                <div class="mb-6">
                    <div class="mx-auto mb-4 h-16 w-16 rounded-full bg-[#5f5fcd]/10 flex items-center justify-center">
                        <PlayIcon class="h-8 w-8 text-[#5f5fcd]" />
                    </div>
                    <h3 class="mb-2 text-xl font-semibold text-gray-900">কুইজ শুরু করুন</h3>
                    <p class="text-gray-600">আপনি কি এই কুইজ শুরু করতে প্রস্তুত?</p>
                </div>
                
                <button
                    @click.prevent="startQuiz"
                    type="button"
                    class="inline-flex items-center rounded-lg bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-6 py-3 font-medium text-white transition-all hover:shadow-lg"
                >
                    <PlayIcon class="mr-2 h-4 w-4" />
                    কুইজ শুরু করুন
                </button>
            </div>

            <!-- Quiz Taking Screen -->
            <div v-if="currentScreen === 'quiz'" class="space-y-6">
                <!-- Progress Bar -->
                <div class="rounded-xl bg-white p-4 shadow-sm">
                    <div class="mb-2 flex items-center justify-between">
                        <span class="font-medium text-gray-900">
                            প্রশ্ন {{ currentQuestionIndex + 1 }} / {{ quiz.questions.length }}
                        </span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-200">
                        <div 
                            class="h-2 rounded-full bg-[#5f5fcd] transition-all duration-300"
                            :style="{ width: ((currentQuestionIndex + 1) / quiz.questions.length) * 100 + '%' }"
                        ></div>
                    </div>
                </div>

                <!-- Current Question -->
                <div v-if="currentQuestion" class="rounded-xl border bg-white shadow-sm">
                    <div class="border-b bg-gray-50 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ currentQuestionIndex + 1 }}. {{ currentQuestion.question }}
                        </h3>
                    </div>

                    <div class="p-6">
                        <!-- Multiple Choice Questions -->
                        <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-3">
                            <label
                                v-for="(option, index) in currentQuestion.options"
                                :key="index"
                                class="flex cursor-pointer items-start rounded-lg border-2 p-4 transition-all hover:shadow-md"
                                :class="answers[currentQuestion.id] == index 
                                    ? 'border-[#5f5fcd] bg-[#5f5fcd]/10' 
                                    : 'border-gray-200 hover:border-[#5f5fcd]'"
                            >
                                <input
                                    type="radio"
                                    :value="index"
                                    v-model="answers[currentQuestion.id]"
                                    class="mt-1 h-4 w-4 text-[#5f5fcd] focus:ring-[#5f5fcd]"
                                />
                                <div class="ml-3">
                                    <span class="font-medium text-gray-700">
                                        {{ String.fromCharCode(65 + index) }}.
                                    </span>
                                    <span class="ml-2 text-gray-900">{{ option }}</span>
                                </div>
                            </label>
                        </div>

                        <!-- True/False Questions -->
                        <div v-else-if="currentQuestion.type === 'true_false'" class="grid grid-cols-2 gap-4">
                            <label
                                class="flex cursor-pointer items-center rounded-lg border-2 p-4 transition-all hover:shadow-md"
                                :class="answers[currentQuestion.id] === 'সত্য' 
                                    ? 'border-green-500 bg-green-50' 
                                    : 'border-gray-200 hover:border-green-400'"
                            >
                                <input
                                    type="radio"
                                    value="সত্য"
                                    v-model="answers[currentQuestion.id]"
                                    class="h-4 w-4 text-green-600 focus:ring-green-500"
                                />
                                <div class="ml-3 text-center">
                                    <div class="text-2xl">✓</div>
                                    <div class="text-sm font-medium">সত্য</div>
                                </div>
                            </label>
                            <label
                                class="flex cursor-pointer items-center rounded-lg border-2 p-4 transition-all hover:shadow-md"
                                :class="answers[currentQuestion.id] === 'মিথ্যা' 
                                    ? 'border-red-500 bg-red-50' 
                                    : 'border-gray-200 hover:border-red-400'"
                            >
                                <input
                                    type="radio"
                                    value="মিথ্যা"
                                    v-model="answers[currentQuestion.id]"
                                    class="h-4 w-4 text-red-600 focus:ring-red-500"
                                />
                                <div class="ml-3 text-center">
                                    <div class="text-2xl">✗</div>
                                    <div class="text-sm font-medium">মিথ্যা</div>
                                </div>
                            </label>
                        </div>

                        <!-- Short Answer Questions -->
                        <div v-else-if="currentQuestion.type === 'short_answer'">
                            <input
                                v-model="answers[currentQuestion.id]"
                                type="text"
                                class="w-full rounded-lg border-2 border-gray-200 p-4 transition-colors focus:border-[#5f5fcd] focus:ring-2 focus:ring-[#5f5fcd]/20"
                                placeholder="আপনার উত্তর লিখুন..."
                            />
                        </div>

                        <!-- Unknown question type -->
                        <div v-else class="text-center text-gray-500 py-8">
                            অজানা প্রশ্নের ধরন: "{{ currentQuestion.type }}"
                        </div>
                    </div>

                    <!-- Navigation Footer -->
                    <div class="border-t bg-gray-50 px-6 py-4">
                        <div class="flex items-center justify-between">
                            <button
                                @click="previousQuestion"
                                :disabled="!canGoPrevious"
                                class="inline-flex items-center rounded-lg px-4 py-2 text-sm font-medium transition-all"
                                :class="canGoPrevious
                                    ? 'bg-white border text-[#5f5fcd] hover:bg-[#5f5fcd] hover:text-white'
                                    : 'cursor-not-allowed bg-gray-100 text-gray-400'"
                            >
                                <ChevronLeftIcon class="mr-1 h-4 w-4" />
                                পূর্ববর্তী
                            </button>

                            <button
                                v-if="canGoNext"
                                @click="nextQuestion"
                                class="inline-flex items-center rounded-lg bg-[#5f5fcd] px-4 py-2 text-sm font-medium text-white hover:bg-[#4a4a9f]"
                            >
                                পরবর্তী
                                <ChevronRightIcon class="ml-1 h-4 w-4" />
                            </button>
                            <button
                                v-else
                                @click="submitQuiz"
                                :disabled="submitting"
                                class="inline-flex items-center rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-50"
                            >
                                <CheckIcon class="mr-1 h-4 w-4" />
                                <span v-if="submitting">জমা দিচ্ছে...</span>
                                <span v-else>জমা দিন</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Results Screen -->
            <div v-if="currentScreen === 'results' && quizResults" class="rounded-xl border bg-white p-8 shadow-sm text-center">
                <!-- Score Display -->
                <div class="mb-6">
                    <div
                        class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full"
                        :class="quizResults.is_passed ? 'bg-green-100' : 'bg-red-100'"
                    >
                        <CheckCircleIcon v-if="quizResults.is_passed" class="h-8 w-8 text-green-600" />
                        <AlertTriangleIcon v-else class="h-8 w-8 text-red-600" />
                    </div>
                    
                    <div class="mb-2 text-4xl font-bold" :class="quizResults.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ Math.round(quizResults.score) }}%
                    </div>
                    
                    <div
                        class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold"
                        :class="quizResults.is_passed ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                    >
                        {{ quizResults.is_passed ? 'উত্তীর্ণ' : 'অনুত্তীর্ণ' }}
                    </div>
                </div>

                <!-- Statistics -->
                <div class="mb-6 rounded-lg bg-gray-50 p-4">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ quizResults.correct_answers }}</div>
                            <div class="text-xs text-gray-600">সঠিক</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-red-600">
                                {{ quizResults.total_questions - quizResults.correct_answers }}
                            </div>
                            <div class="text-xs text-gray-600">ভুল</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-[#5f5fcd]">{{ quizResults.total_questions }}</div>
                            <div class="text-xs text-gray-600">মোট</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 justify-center">
                    <button
                        v-if="attemptsLeft > 0"
                        @click="retryQuiz"
                        class="inline-flex items-center rounded-lg border bg-white px-6 py-3 font-medium text-gray-700 hover:bg-gray-50"
                    >
                        <RotateCcwIcon class="mr-2 h-4 w-4" />
                        পুনরায় চেষ্টা
                    </button>
                    <button
                        @click="goBack"
                        class="inline-flex items-center rounded-lg bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-6 py-3 font-medium text-white hover:shadow-lg"
                    >
                        পাঠে ফিরে যান
                        <ChevronRightIcon class="ml-2 h-4 w-4" />
                    </button>
                </div>
            </div>

            <!-- Cannot Take Quiz -->
            <div v-if="currentScreen === 'blocked'" class="rounded-xl border border-red-200 bg-red-50 p-6 text-center">
                <AlertTriangleIcon class="mx-auto mb-4 h-12 w-12 text-red-500" />
                <h3 class="mb-2 text-lg font-semibold text-red-900">কুইজ নেওয়া সম্ভব নয়</h3>
                <p class="text-red-700">আপনি এই কুইজের সর্বোচ্চ চেষ্টার সংখ্যা শেষ করেছেন।</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head, router, usePage } from '@inertiajs/vue3'
import { ref, computed, onMounted } from 'vue'
import { route } from 'ziggy-js'
import {
    AlertTriangleIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ClockIcon,
    HelpCircleIcon,
    PlayIcon,
    RotateCcwIcon,
} from 'lucide-vue-next'

// Props
const props = defineProps({
    quiz: Object,
    userAttempts: Array,
    canTakeQuiz: Boolean,
    attemptsLeft: Number,
})

// Core State
const currentScreen = ref('start') // 'start', 'quiz', 'results', 'blocked'
const currentQuestionIndex = ref(0)
const answers = ref({})
const startTime = ref(null)
const submitting = ref(false)

// Computed
const initialScreen = computed(() => {
    if (!props.canTakeQuiz) return 'blocked'
    return 'start'
})

const currentQuestion = computed(() => {
    if (!props.quiz.questions || currentQuestionIndex.value >= props.quiz.questions.length) {
        return null
    }
    return props.quiz.questions[currentQuestionIndex.value]
})

const isLastQuestion = computed(() => {
    return currentQuestionIndex.value >= props.quiz.questions.length - 1
})

const canGoNext = computed(() => {
    return currentQuestionIndex.value < props.quiz.questions.length - 1
})

const canGoPrevious = computed(() => {
    return currentQuestionIndex.value > 0
})

// Page data for quiz results
const page = usePage()
const quizResults = computed(() => page.props.quiz_results || null)

// Methods
const startQuiz = () => {
    // Reset quiz state
    currentQuestionIndex.value = 0
    answers.value = {}
    startTime.value = new Date()
    submitting.value = false
    
    // Switch to quiz screen
    currentScreen.value = 'quiz'
}

const nextQuestion = () => {
    if (canGoNext.value) {
        currentQuestionIndex.value++
    }
}

const previousQuestion = () => {
    if (canGoPrevious.value) {
        currentQuestionIndex.value--
    }
}

const submitQuiz = () => {
    if (submitting.value) return
    
    // Basic validation
    if (Object.keys(answers.value).length === 0) {
        alert('অন্তত একটি প্রশ্নের উত্তর দিন।')
        return
    }
    
    submitting.value = true
    
    router.post(route('quiz.submit', props.quiz.id), {
        answers: answers.value,
        started_at: startTime.value?.toISOString(),
    }, {
        preserveState: false,
        onSuccess: (page) => {
            // Check if results are in page props
            if (page.props.quiz_results) {
                currentScreen.value = 'results'
            }
            submitting.value = false
        },
        onError: (errors) => {
            alert('কুইজ জমা দিতে সমস্যা হয়েছে।')
            submitting.value = false
        },
        onFinish: () => {
            submitting.value = false
        }
    })
}

const retryQuiz = () => {
    // Reset to start screen
    currentScreen.value = 'start'
}

const goBack = () => {
    if (props.quiz.course_slug) {
        router.visit(route('frontend.learning.show', props.quiz.course_slug))
    } else {
        window.history.back()
    }
}

// Lifecycle
onMounted(() => {
    // Check if we have quiz results in flash data
    if (quizResults.value) {
        currentScreen.value = 'results'
    } else {
        currentScreen.value = initialScreen.value
    }
})
</script>