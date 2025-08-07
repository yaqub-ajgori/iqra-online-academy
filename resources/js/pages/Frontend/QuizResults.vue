<template>
    <FrontendLayout :title="`${quiz.title} - ফলাফল`">
        <div class="min-h-screen bg-gray-50 py-8">
            <div class="max-w-2xl mx-auto px-4">
                <!-- Results Card -->
                <div class="bg-white rounded-lg shadow-sm border p-8 text-center">
                    <!-- Icon -->
                    <div class="w-20 h-20 mx-auto mb-6 rounded-full flex items-center justify-center"
                         :class="attempt.is_passed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                        <svg v-if="attempt.is_passed" class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <svg v-else class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </div>

                    <!-- Status -->
                    <h1 class="text-3xl font-bold mb-2" :class="attempt.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ attempt.is_passed ? 'উত্তীর্ণ!' : 'অনুত্তীর্ণ' }}
                    </h1>

                    <!-- Score -->
                    <div class="text-5xl font-bold mb-4" :class="attempt.is_passed ? 'text-green-600' : 'text-red-600'">
                        {{ Math.round(attempt.score) }}%
                    </div>

                        <!-- Details -->
                        <div class="bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-2xl p-6 mb-8">
                            <div class="grid grid-cols-3 gap-6">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#5f5fcd] mb-1">{{ attempt.correct_answers }}</div>
                                    <div class="text-sm text-[#6c757d] font-medium">সঠিক উত্তর</div>
                                </div>
                                <div class="text-center border-x border-gray-200">
                                    <div class="text-2xl font-bold text-[#6c757d] mb-1">{{ attempt.total_questions }}</div>
                                    <div class="text-sm text-[#6c757d] font-medium">মোট প্রশ্ন</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-[#2d5a27] mb-1">{{ quiz.passing_score }}%</div>
                                    <div class="text-sm text-[#6c757d] font-medium">পাসিং স্কোর</div>
                                </div>
                            </div>
                        </div>

                        <!-- Performance Message -->
                        <div v-if="attempt.is_passed" class="bg-gradient-to-r from-[#2d5a27]/10 to-[#198754]/10 rounded-xl p-4 mb-8">
                            <p class="text-[#2d5a27] font-semibold">🎉 অভিনন্দন! আপনি সফলভাবে কুইজটি সম্পন্ন করেছেন।</p>
                        </div>
                        <div v-else class="bg-gradient-to-r from-red-50 to-red-25 rounded-xl p-4 mb-8">
                            <p class="text-red-600 font-semibold">😔 দুঃখিত! আরেকবার চেষ্টা করুন।</p>
                        </div>

                        <!-- Action Button -->
                        <button @click="goBack" 
                                class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white rounded-xl hover:shadow-islamic-lg transition-all duration-300 font-semibold text-lg transform hover:scale-105 group">
                            <svg class="w-5 h-5 mr-3 group-hover:-translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L6.414 9H17a1 1 0 110 2H6.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
                            </svg>
                            কোর্সে ফিরুন
                        </button>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

// Props
const page = usePage()
const quiz = computed(() => page.props.quiz)
const attempt = computed(() => page.props.attempt)

// Methods
const goBack = () => {
    router.visit(`/learning/${quiz.value.course.slug}`)
}
</script>