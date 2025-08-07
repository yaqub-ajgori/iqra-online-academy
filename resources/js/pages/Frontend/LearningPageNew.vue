<template>
    <div class="min-h-screen bg-gray-50">
        <Head :title="`Learning - ${course?.title || 'Course'}`" />

        <!-- Header -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">{{ course?.title }}</h1>
                        <p class="text-sm text-gray-500">{{ currentLesson?.title }}</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-600">Progress: {{ Math.round(progressPercentage) }}%</span>
                        <button @click="goBack" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Back to Dashboard
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                
                <!-- Lesson List Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow p-4">
                        <h2 class="font-bold text-gray-900 mb-4">Course Content</h2>
                        <div v-if="course?.modules" class="space-y-2">
                            <div v-for="module in course.modules" :key="module.id" class="space-y-1">
                                <h3 class="font-medium text-gray-700 text-sm">{{ module.title }}</h3>
                                <div v-for="lesson in module.lessons" :key="lesson.id" 
                                     @click="selectLesson(lesson)"
                                     class="p-2 text-sm rounded cursor-pointer hover:bg-gray-100"
                                     :class="currentLesson?.id === lesson.id ? 'bg-blue-100 text-blue-700' : 'text-gray-600'">
                                    <div class="flex items-center justify-between">
                                        <span>{{ lesson.title }}</span>
                                        <span v-if="lesson.completed" class="text-green-500">✓</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-lg shadow p-6">
                        
                        <!-- Video Lesson -->
                        <div v-if="currentLesson?.type === 'video' && currentLesson?.video_url">
                            <h2 class="text-xl font-bold mb-4">{{ currentLesson.title }}</h2>
                            <div class="mb-4">
                                <video controls class="w-full rounded">
                                    <source :src="currentLesson.video_url" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                            <div v-if="currentLesson.content" class="prose max-w-none" v-html="currentLesson.content"></div>
                            <button @click="markComplete" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Mark as Complete
                            </button>
                        </div>

                        <!-- Text Lesson -->
                        <div v-else-if="currentLesson?.type === 'text'">
                            <h2 class="text-xl font-bold mb-4">{{ currentLesson.title }}</h2>
                            <div v-if="currentLesson.content" class="prose max-w-none" v-html="currentLesson.content"></div>
                            <button @click="markComplete" class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                Mark as Complete
                            </button>
                        </div>

                        <!-- Quiz Lesson -->
                        <div v-else-if="currentLesson?.type === 'quiz' && currentLesson?.quiz">
                            <h2 class="text-xl font-bold mb-4">{{ currentLesson.quiz.title }}</h2>
                            
                            <!-- Quiz Not Started -->
                            <div v-if="!quizStarted && !quizCompleted">
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <p class="text-blue-800">{{ currentLesson.quiz.description }}</p>
                                    <p class="text-sm text-blue-600 mt-2">Questions: {{ currentLesson.quiz.total_questions }}</p>
                                    <p class="text-sm text-blue-600">Time Limit: {{ currentLesson.quiz.time_limit_minutes }} minutes</p>
                                </div>
                                <button @click="startQuiz" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    Start Quiz
                                </button>
                            </div>

                            <!-- Quiz Started -->
                            <div v-else-if="quizStarted && !quizCompleted">
                                <div class="mb-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Question {{ currentQuestionIndex + 1 }} of {{ totalQuestions }}</span>
                                        <span class="text-sm text-gray-600">Time: {{ timeRemaining }}</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-blue-600 h-2 rounded-full" :style="`width: ${((currentQuestionIndex + 1) / totalQuestions) * 100}%`"></div>
                                    </div>
                                </div>

                                <div v-if="currentQuestion">
                                    <h3 class="text-lg font-medium mb-4">{{ currentQuestion.question }}</h3>
                                    
                                    <!-- Multiple Choice -->
                                    <div v-if="currentQuestion.type === 'multiple_choice'" class="space-y-3">
                                        <label v-for="(option, index) in currentQuestion.options" :key="index" 
                                               class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" :name="`question_${currentQuestion.id}`" 
                                                   :value="index" v-model="answers[currentQuestion.id]" class="mr-3">
                                            <span>{{ option }}</span>
                                        </label>
                                    </div>

                                    <!-- Navigation -->
                                    <div class="flex justify-between mt-6">
                                        <button @click="previousQuestion" :disabled="currentQuestionIndex === 0"
                                                class="px-4 py-2 bg-gray-600 text-white rounded disabled:opacity-50">
                                            Previous
                                        </button>
                                        <div class="space-x-2">
                                            <button v-if="currentQuestionIndex < totalQuestions - 1" @click="nextQuestion"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                Next
                                            </button>
                                            <button v-else @click="submitQuiz"
                                                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                                Submit Quiz
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Quiz Results -->
                            <div v-else-if="quizCompleted && quizResults">
                                <div class="text-center mb-6">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4"
                                         :class="quizResults.is_passed ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
                                        <span v-if="quizResults.is_passed">✓</span>
                                        <span v-else>✗</span>
                                    </div>
                                    <h3 class="text-xl font-bold">Quiz Completed!</h3>
                                    <p class="text-gray-600">Your Score: {{ quizResults.score }}%</p>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        <div>Correct Answers: {{ quizResults.correct_answers }}/{{ quizResults.total_questions }}</div>
                                        <div>Time Taken: {{ quizResults.time_taken }}</div>
                                    </div>
                                </div>

                                <div class="flex justify-center space-x-4">
                                    <button @click="retakeQuiz" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                        Retake Quiz
                                    </button>
                                    <button v-if="quizResults.is_passed" @click="markComplete" 
                                            class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                                        Continue to Next Lesson
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Default/Loading -->
                        <div v-else>
                            <div class="text-center py-12">
                                <p class="text-gray-500">Loading lesson content...</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'

const page = usePage()

// Props
const props = defineProps({
    course: Object,
    enrollment: Object,
    course_slug: String,
})

// Reactive data
const currentLesson = ref(null)
const quizStarted = ref(false)
const quizCompleted = ref(false)
const quizResults = ref(null)
const currentQuestionIndex = ref(0)
const answers = ref({})
const startTime = ref(null)
const timeRemaining = ref('')

// Computed
const progressPercentage = computed(() => {
    if (!props.course?.modules) return 0
    const totalLessons = props.course.modules.reduce((total, module) => total + module.lessons.length, 0)
    const completedLessons = props.course.modules.reduce((total, module) => 
        total + module.lessons.filter(lesson => lesson.completed).length, 0)
    return totalLessons > 0 ? (completedLessons / totalLessons) * 100 : 0
})

const currentQuestion = computed(() => {
    if (!currentLesson.value?.quiz?.questions) return null
    return currentLesson.value.quiz.questions[currentQuestionIndex.value]
})

const totalQuestions = computed(() => {
    return currentLesson.value?.quiz?.questions?.length || 0
})

// Methods
const goBack = () => {
    router.get(route('frontend.student.dashboard'))
}

const selectLesson = (lesson) => {
    currentLesson.value = lesson
    resetQuizState()
}

const resetQuizState = () => {
    quizStarted.value = false
    quizCompleted.value = false
    quizResults.value = null
    currentQuestionIndex.value = 0
    answers.value = {}
    startTime.value = null
}

const startQuiz = () => {
    quizStarted.value = true
    startTime.value = new Date()
    currentQuestionIndex.value = 0
    answers.value = {}
}

const nextQuestion = () => {
    if (currentQuestionIndex.value < totalQuestions.value - 1) {
        currentQuestionIndex.value++
    }
}

const previousQuestion = () => {
    if (currentQuestionIndex.value > 0) {
        currentQuestionIndex.value--
    }
}

const submitQuiz = () => {
    if (!currentLesson.value?.quiz) return
    
    router.post(route('quiz.submit', currentLesson.value.quiz.id), {
        answers: answers.value,
        started_at: startTime.value.toISOString(),
    })
}

const retakeQuiz = () => {
    resetQuizState()
}

const markComplete = () => {
    if (!currentLesson.value) return
    
    router.post(route('frontend.learning.complete'), {
        lesson_id: currentLesson.value.id,
        course_id: props.course.id,
    })
}

// Initialize
onMounted(() => {
    // Select first lesson if available
    if (props.course?.modules && props.course.modules.length > 0) {
        const firstModule = props.course.modules[0]
        if (firstModule.lessons && firstModule.lessons.length > 0) {
            currentLesson.value = firstModule.lessons[0]
        }
    }
    
    // Check for quiz results
    if (page.props.flash?.quiz_results) {
        quizResults.value = page.props.flash.quiz_results
        quizCompleted.value = true
        quizStarted.value = false
    }
})
</script>

<style scoped>
.prose {
    max-width: none;
}
</style>