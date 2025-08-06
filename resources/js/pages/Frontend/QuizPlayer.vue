<template>
  <FrontendLayout title="Quiz Player" :show-breadcrumb="false">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
      <div class="container mx-auto px-4 py-8">
        <!-- Quiz Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <div class="flex justify-between items-center mb-4">
            <div>
              <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ quiz.title }}</h1>
              <p class="text-gray-600">{{ quiz.course?.title }}</p>
            </div>
            <div class="text-right">
              <div class="text-sm text-gray-500">Attempt {{ attemptCount + 1 }} of {{ maxAttempts }}</div>
              <div v-if="quiz.time_limit_minutes" class="text-lg font-bold text-orange-600">
                {{ formatTime(timeRemaining) }}
              </div>
            </div>
          </div>
          
          <!-- Progress Bar -->
          <div class="w-full bg-gray-200 rounded-full h-2">
            <div 
              class="bg-blue-600 h-2 rounded-full transition-all duration-300"
              :style="{ width: `${progress}%` }"
            ></div>
          </div>
          <div class="text-sm text-gray-600 mt-1">
            Question {{ currentQuestionIndex + 1 }} of {{ quiz.questions.length }}
          </div>
        </div>

        <!-- Quiz Content -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
          <div v-if="currentQuestion" class="mb-6">
            <!-- Question -->
            <div class="mb-6">
              <h2 class="text-xl font-semibold text-gray-800 mb-4">
                {{ currentQuestionIndex + 1 }}. {{ currentQuestion.question }}
              </h2>
              
              <!-- Multiple Choice -->
              <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-3">
                <label 
                  v-for="(option, index) in currentQuestion.options"
                  :key="index"
                  class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                  :class="{ 'border-blue-500 bg-blue-50': answers[currentQuestion.id] === option }"
                >
                  <input
                    type="radio"
                    :name="`question_${currentQuestion.id}`"
                    :value="option"
                    v-model="answers[currentQuestion.id]"
                    class="mr-3 text-blue-600"
                  />
                  <span class="text-gray-800">{{ option }}</span>
                </label>
              </div>

              <!-- True/False -->
              <div v-else-if="currentQuestion.type === 'true_false'" class="space-y-3">
                <label 
                  class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                  :class="{ 'border-blue-500 bg-blue-50': answers[currentQuestion.id] === 'সত্য' }"
                >
                  <input
                    type="radio"
                    :name="`question_${currentQuestion.id}`"
                    value="সত্য"
                    v-model="answers[currentQuestion.id]"
                    class="mr-3 text-blue-600"
                  />
                  <span class="text-gray-800">সত্য</span>
                </label>
                <label 
                  class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors"
                  :class="{ 'border-blue-500 bg-blue-50': answers[currentQuestion.id] === 'মিথ্যা' }"
                >
                  <input
                    type="radio"
                    :name="`question_${currentQuestion.id}`"
                    value="মিথ্যা"
                    v-model="answers[currentQuestion.id]"
                    class="mr-3 text-blue-600"
                  />
                  <span class="text-gray-800">মিথ্যা</span>
                </label>
              </div>

              <!-- Short Answer -->
              <div v-else-if="currentQuestion.type === 'short_answer'" class="space-y-3">
                <textarea
                  v-model="answers[currentQuestion.id]"
                  rows="4"
                  class="w-full p-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                  :placeholder="'আপনার উত্তর লিখুন...'"
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Navigation Buttons -->
          <div class="flex justify-between items-center">
            <button
              @click="previousQuestion"
              :disabled="currentQuestionIndex === 0"
              class="px-4 py-2 bg-gray-500 text-white rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-600 transition-colors"
            >
              পূর্ববর্তী
            </button>

            <div class="flex space-x-2">
              <button
                v-if="currentQuestionIndex < quiz.questions.length - 1"
                @click="nextQuestion"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
              >
                পরবর্তী
              </button>
              
              <button
                v-else
                @click="showSubmitModal = true"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
              >
                জমা দিন
              </button>
            </div>
          </div>
        </div>

        <!-- Question Navigation -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h3 class="text-lg font-semibold mb-4">Question Navigation</h3>
          <div class="grid grid-cols-10 gap-2">
            <button
              v-for="(question, index) in quiz.questions"
              :key="question.id"
              @click="goToQuestion(index)"
              class="w-10 h-10 rounded-lg text-sm font-medium transition-colors"
              :class="[
                index === currentQuestionIndex 
                  ? 'bg-blue-600 text-white' 
                  : answers[question.id] 
                    ? 'bg-green-100 text-green-800 border border-green-300' 
                    : 'bg-gray-100 text-gray-600 border border-gray-300'
              ]"
            >
              {{ index + 1 }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Submit Confirmation Modal -->
    <div v-if="showSubmitModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold mb-4">কুইজ জমা দিন</h3>
        <p class="text-gray-600 mb-4">
          আপনি কি নিশ্চিত যে কুইজ জমা দিতে চান? জমা দেওয়ার পর আর কোনো পরিবর্তন করা যাবে না।
        </p>
        <div class="text-sm text-gray-500 mb-6">
          উত্তর দেওয়া প্রশ্ন: {{ answeredCount }} / {{ quiz.questions.length }}
        </div>
        <div class="flex justify-end space-x-3">
          <button
            @click="showSubmitModal = false"
            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
          >
            বাতিল
          </button>
          <button
            @click="submitQuiz"
            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"
          >
            নিশ্চিত করুন
          </button>
        </div>
      </div>
    </div>
  </FrontendLayout>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { router } from '@inertiajs/vue3'
import FrontendLayout from '@/Layouts/FrontendLayout.vue'

const props = defineProps({
  quiz: Object,
  attemptCount: Number,
  maxAttempts: Number,
  previousAttempts: Array,
})

const currentQuestionIndex = ref(0)
const answers = ref({})
const startTime = ref(new Date())
const timeRemaining = ref(props.quiz.time_limit_minutes ? props.quiz.time_limit_minutes * 60 : null)
const showSubmitModal = ref(false)
let timer = null

const currentQuestion = computed(() => {
  return props.quiz.questions[currentQuestionIndex.value]
})

const progress = computed(() => {
  return ((currentQuestionIndex.value + 1) / props.quiz.questions.length) * 100
})

const answeredCount = computed(() => {
  return Object.keys(answers.value).length
})

const formatTime = (seconds) => {
  if (!seconds) return ''
  const minutes = Math.floor(seconds / 60)
  const secs = seconds % 60
  return `${minutes}:${secs.toString().padStart(2, '0')}`
}

const nextQuestion = () => {
  if (currentQuestionIndex.value < props.quiz.questions.length - 1) {
    currentQuestionIndex.value++
  }
}

const previousQuestion = () => {
  if (currentQuestionIndex.value > 0) {
    currentQuestionIndex.value--
  }
}

const goToQuestion = (index) => {
  currentQuestionIndex.value = index
}

const submitQuiz = () => {
  router.post(route('quiz.submit', props.quiz.id), {
    answers: answers.value,
    started_at: startTime.value.toISOString(),
  })
}

const autoSubmit = () => {
  if (timeRemaining.value !== null && timeRemaining.value <= 0) {
    submitQuiz()
  }
}

onMounted(() => {
  // Start timer if time limit is set
  if (props.quiz.time_limit_minutes) {
    timer = setInterval(() => {
      if (timeRemaining.value > 0) {
        timeRemaining.value--
      } else {
        autoSubmit()
      }
    }, 1000)
  }
})

onBeforeUnmount(() => {
  if (timer) {
    clearInterval(timer)
  }
})
</script>