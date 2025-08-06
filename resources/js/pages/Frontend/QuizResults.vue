<template>
  <FrontendLayout title="Quiz Results" :show-breadcrumb="false">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
      <div class="container mx-auto px-4 py-8">
        <!-- Results Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <div class="text-center">
            <div class="mb-4">
              <div 
                class="inline-flex items-center justify-center w-20 h-20 rounded-full text-3xl font-bold"
                :class="attempt.is_passed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'"
              >
                {{ Math.round(attempt.score) }}%
              </div>
            </div>
            
            <h1 class="text-3xl font-bold mb-2" :class="attempt.is_passed ? 'text-green-600' : 'text-red-600'">
              {{ attempt.is_passed ? 'অভিনন্দন! আপনি পাস করেছেন' : 'দুঃখিত, আপনি পাস করতে পারেননি' }}
            </h1>
            
            <p class="text-gray-600 mb-4">{{ attempt.quiz.title }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
              <div class="bg-blue-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-blue-600">{{ attempt.score }}%</div>
                <div class="text-sm text-gray-600">আপনার স্কোর</div>
              </div>
              
              <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-yellow-600">{{ attempt.quiz.passing_score }}%</div>
                <div class="text-sm text-gray-600">পাসিং স্কোর</div>
              </div>
              
              <div class="bg-green-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-green-600">{{ correctAnswers }}</div>
                <div class="text-sm text-gray-600">সঠিক উত্তর</div>
              </div>
              
              <div class="bg-purple-50 p-4 rounded-lg">
                <div class="text-2xl font-bold text-purple-600">{{ formatTime(attempt.time_taken_seconds) }}</div>
                <div class="text-sm text-gray-600">সময় নেওয়া</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Detailed Results (if review is allowed) -->
        <div v-if="detailedResults" class="bg-white rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-xl font-bold mb-6">বিস্তারিত ফলাফল</h2>
          
          <div class="space-y-6">
            <div 
              v-for="(result, index) in detailedResults"
              :key="index"
              class="border rounded-lg p-4"
              :class="result.is_correct ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50'"
            >
              <div class="flex items-start justify-between mb-3">
                <h3 class="font-semibold text-gray-800">
                  {{ index + 1 }}. {{ result.question }}
                </h3>
                <div class="flex items-center space-x-2">
                  <span 
                    class="px-2 py-1 rounded text-sm font-medium"
                    :class="result.is_correct 
                      ? 'bg-green-100 text-green-800' 
                      : 'bg-red-100 text-red-800'"
                  >
                    {{ result.is_correct ? 'সঠিক' : 'ভুল' }}
                  </span>
                  <span class="text-sm text-gray-500">
                    {{ result.earned_points }}/{{ result.points }} পয়েন্ট
                  </span>
                </div>
              </div>
              
              <!-- Show options for multiple choice -->
              <div v-if="result.type === 'multiple_choice' || result.type === 'true_false'" class="mb-3">
                <div class="text-sm text-gray-600 mb-2">বিকল্পসমূহ:</div>
                <div class="space-y-1">
                  <div 
                    v-for="option in result.options"
                    :key="option"
                    class="flex items-center p-2 rounded text-sm"
                    :class="{
                      'bg-green-100 text-green-800': result.correct_answers.includes(option),
                      'bg-red-100 text-red-800': result.student_answer === option && !result.correct_answers.includes(option),
                      'bg-gray-100 text-gray-600': result.student_answer !== option && !result.correct_answers.includes(option)
                    }"
                  >
                    <span class="mr-2">
                      <svg v-if="result.correct_answers.includes(option)" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                      </svg>
                      <svg v-else-if="result.student_answer === option" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                      </svg>
                    </span>
                    {{ option }}
                  </div>
                </div>
              </div>

              <!-- Show answers for short answer -->
              <div v-else-if="result.type === 'short_answer'" class="mb-3">
                <div class="mb-2">
                  <strong class="text-sm text-gray-600">আপনার উত্তর:</strong>
                  <div class="mt-1 p-2 bg-gray-100 rounded text-sm">
                    {{ result.student_answer || 'কোনো উত্তর দেওয়া হয়নি' }}
                  </div>
                </div>
                <div>
                  <strong class="text-sm text-gray-600">নমুনা উত্তর:</strong>
                  <div class="mt-1 p-2 bg-green-100 rounded text-sm">
                    {{ result.correct_answers.join(', ') }}
                  </div>
                </div>
              </div>

              <!-- Explanation -->
              <div v-if="result.explanation" class="mt-3 p-3 bg-blue-50 rounded">
                <strong class="text-sm text-blue-800">ব্যাখ্যা:</strong>
                <p class="text-sm text-blue-700 mt-1">{{ result.explanation }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <button
              v-if="canRetake"
              @click="retakeQuiz"
              class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              আবার চেষ্টা করুন
            </button>
            
            <button
              @click="goToCourse"
              class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors"
            >
              কোর্সে ফিরে যান
            </button>
            
            <button
              v-if="attempt.is_passed"
              @click="downloadCertificate"
              class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
            >
              সার্টিফিকেট ডাউনলোড করুন
            </button>
          </div>
        </div>
      </div>
    </div>
  </FrontendLayout>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

const props = defineProps({
  attempt: Object,
  detailedResults: Array,
  canRetake: Boolean,
})

const correctAnswers = computed(() => {
  if (!props.detailedResults) return 0
  return props.detailedResults.filter(result => result.is_correct).length
})

const formatTime = (seconds) => {
  const minutes = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${minutes}:${secs.toString().padStart(2, '0')}`
}

const retakeQuiz = () => {
  router.visit(route('quiz.show', props.attempt.quiz.id))
}

const goToCourse = () => {
  router.visit(route('frontend.learning.show', props.attempt.quiz.course.slug))
}

const downloadCertificate = () => {
  router.post(route('certificates.generate'), {
    course_id: props.attempt.quiz.course.id
  })
}
</script>