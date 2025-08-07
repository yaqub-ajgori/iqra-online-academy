<template>
    <FrontendLayout :title="quiz.title">
        <div class="min-h-screen bg-gradient-to-br from-[#fdfcff] to-[#fafbf9] py-8">
            <div class="max-w-2xl mx-auto px-4">
                <!-- Quiz Header -->
                <div class="bg-white rounded-2xl shadow-islamic border border-gray-100 p-8 mb-6">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h1 class="text-3xl font-bold text-gradient-islamic mb-3">{{ quiz.title }}</h1>
                        <p v-if="quiz.description" class="text-[#6c757d] mb-4 leading-relaxed">{{ quiz.description }}</p>
                        <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 border border-[#5f5fcd]/25">
                            <svg class="w-4 h-4 text-[#5f5fcd] mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-semibold text-[#5f5fcd]">মোট প্রশ্ন: {{ quiz.questions.length }} টি</span>
                        </div>
                    </div>
                </div>

                <!-- Question Card -->
                <div v-if="currentQuestion" class="bg-white rounded-2xl shadow-islamic border border-gray-100 p-8 mb-6">
                    <!-- Progress -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 text-sm font-semibold text-[#5f5fcd]">
                                প্রশ্ন {{ currentQuestionIndex + 1 }} / {{ quiz.questions.length }}
                            </span>
                            <span class="text-sm text-[#6c757d] font-medium">
                                {{ Math.round(((currentQuestionIndex + 1) / quiz.questions.length) * 100) }}% সম্পন্ন
                            </span>
                        </div>
                        <div class="w-full bg-gradient-to-r from-gray-100 to-gray-200 rounded-full h-3 shadow-inner">
                            <div class="bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] h-3 rounded-full transition-all duration-500 shadow-sm" 
                                 :style="{ width: ((currentQuestionIndex + 1) / quiz.questions.length) * 100 + '%' }"></div>
                        </div>
                    </div>

                    <!-- Question -->
                    <div class="mb-8">
                        <h2 class="text-xl font-bold text-[#212529] mb-2 leading-relaxed">{{ currentQuestion.question }}</h2>
                        <div class="h-1 w-20 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] rounded-full"></div>
                    </div>

                    <!-- Multiple Choice -->
                    <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-4">
                        <label v-for="(option, index) in currentQuestion.options" :key="index"
                               class="flex items-start p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-islamic group"
                               :class="answers[currentQuestion.id] == index ? 'border-[#5f5fcd] bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5 shadow-islamic' : 'border-gray-200 hover:border-[#5f5fcd]/50 hover:bg-gray-50'">
                            <input type="radio" :value="index" v-model="answers[currentQuestion.id]" class="mt-1 mr-4 text-[#5f5fcd] focus:ring-[#5f5fcd] focus:ring-2">
                            <span class="text-[#212529] font-medium group-hover:text-[#5f5fcd] transition-colors">{{ option }}</span>
                        </label>
                    </div>

                    <!-- True/False -->
                    <div v-else-if="currentQuestion.type === 'true_false'" class="space-y-4">
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-islamic group"
                               :class="answers[currentQuestion.id] === 'সত্য' ? 'border-[#2d5a27] bg-gradient-to-r from-[#2d5a27]/10 to-[#2d5a27]/5 shadow-islamic' : 'border-gray-200 hover:border-[#2d5a27]/50 hover:bg-gray-50'">
                            <input type="radio" value="সত্য" v-model="answers[currentQuestion.id]" class="mr-4 text-[#2d5a27] focus:ring-[#2d5a27] focus:ring-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-[#2d5a27]/20 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-[#2d5a27]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-[#212529] font-medium group-hover:text-[#2d5a27] transition-colors">সত্য</span>
                            </div>
                        </label>
                        <label class="flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-300 hover:shadow-islamic group"
                               :class="answers[currentQuestion.id] === 'মিথ্যা' ? 'border-red-500 bg-gradient-to-r from-red-50 to-red-25 shadow-islamic' : 'border-gray-200 hover:border-red-400 hover:bg-gray-50'">
                            <input type="radio" value="মিথ্যা" v-model="answers[currentQuestion.id]" class="mr-4 text-red-500 focus:ring-red-500 focus:ring-2">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <span class="text-[#212529] font-medium group-hover:text-red-500 transition-colors">মিথ্যা</span>
                            </div>
                        </label>
                    </div>

                    <!-- Short Answer -->
                    <div v-else-if="currentQuestion.type === 'short_answer'">
                        <div class="relative">
                            <input v-model="answers[currentQuestion.id]" 
                                   type="text"
                                   class="w-full p-4 border-2 border-gray-200 rounded-xl focus:border-[#5f5fcd] focus:outline-none focus:ring-2 focus:ring-[#5f5fcd]/20 transition-all duration-300 bg-gray-50 focus:bg-white"
                                   placeholder="আপনার উত্তর লিখুন...">
                            <div class="absolute top-0 left-4 -mt-2 px-2 bg-white text-sm text-[#6c757d] font-medium">
                                উত্তর
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="bg-white rounded-2xl shadow-islamic border border-gray-100 p-8">
                    <div class="flex justify-between items-center">
                        <button v-if="currentQuestionIndex > 0" 
                                @click="previousQuestion"
                                class="inline-flex items-center px-6 py-3 text-[#6c757d] border-2 border-gray-200 rounded-xl hover:border-[#5f5fcd]/50 hover:text-[#5f5fcd] hover:bg-gray-50 transition-all duration-300 font-semibold group">
                            <svg class="w-4 h-4 mr-2 group-hover:-translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            পূর্ববর্তী
                        </button>
                        <div v-else></div>

                        <button v-if="currentQuestionIndex < quiz.questions.length - 1" 
                                @click="nextQuestion"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white rounded-xl hover:shadow-islamic-lg transition-all duration-300 font-semibold group transform hover:scale-105">
                            পরবর্তী
                            <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                        <button v-else 
                                @click="submitQuiz"
                                :disabled="!canSubmit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-[#2d5a27] to-[#198754] text-white rounded-xl hover:shadow-islamic-lg transition-all duration-300 font-semibold group transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
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
import { ref, computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

// Props
const page = usePage()
const quiz = computed(() => page.props.quiz)

// State
const currentQuestionIndex = ref(0)
const answers = ref({})

// Computed
const currentQuestion = computed(() => {
    return quiz.value.questions[currentQuestionIndex.value]
})

const canSubmit = computed(() => {
    // Check if all questions are answered
    return quiz.value.questions.every(question => {
        const answer = answers.value[question.id]
        return answer !== undefined && answer !== null && answer !== ''
    })
})

// Methods
const nextQuestion = () => {
    if (currentQuestionIndex.value < quiz.value.questions.length - 1) {
        currentQuestionIndex.value++
    }
}

const previousQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--
    }
}

const submitQuiz = () => {
    router.post(`/quiz/${quiz.value.id}/submit`, {
        answers: answers.value
    })
}
</script>