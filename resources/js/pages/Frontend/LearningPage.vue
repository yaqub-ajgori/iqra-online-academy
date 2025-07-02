<template>
  <div class="h-screen bg-gray-900 flex flex-col overflow-hidden">
    <Head :title="`${currentLesson?.title || 'Loading...'} - ${course?.title || 'Course'}`" />

    <!-- Main Learning Interface -->
    <div class="h-screen flex flex-col overflow-hidden">
      <!-- Header Bar -->
      <header class="bg-gray-800 border-b border-gray-700 px-6 py-3 flex items-center justify-between z-10">
        <div class="flex items-center space-x-4">
          <!-- Back to Dashboard -->
          <PrimaryButton
            @click="goBackToDashboard"
            variant="ghost"
            size="sm"
            :icon="ArrowLeftIcon"
            class="text-gray-300 hover:text-white hover:bg-gray-700"
          >
            ড্যাশবোর্ডে ফিরে যান
          </PrimaryButton>
          
          <div class="h-6 w-px bg-gray-600"></div>
          
          <!-- Course Title -->
          <div>
            <h1 class="text-lg font-semibold text-white">{{ course?.title }}</h1>
            <p class="text-sm text-gray-400">{{ currentLesson?.title }}</p>
          </div>
        </div>

        <!-- Progress -->
        <div class="flex items-center space-x-4">
          <div class="text-sm text-gray-400">
            {{ completedLessons }}/{{ totalLessons }} পাঠ সম্পন্ন
          </div>
          <div class="w-32 bg-gray-700 rounded-full h-2">
            <div 
              class="bg-[#2d5a27] h-2 rounded-full transition-all duration-300"
              :style="{ width: progressPercentage + '%' }"
            ></div>
          </div>
          <span class="text-sm text-gray-300">{{ Math.round(progressPercentage) }}%</span>
        </div>
      </header>

      <!-- Main Content Area -->
      <div class="flex flex-1 overflow-hidden">
        
        <!-- Collapsible Sidebar -->
        <aside 
          :class="[
            'bg-gray-800 border-r border-gray-700 transition-all duration-300 flex flex-col',
            sidebarCollapsed ? 'w-12' : 'w-80'
          ]"
        >
          <!-- Sidebar Header -->
          <div class="p-4 border-b border-gray-700 flex items-center justify-between">
            <h2 v-if="!sidebarCollapsed" class="text-lg font-semibold text-white">কোর্স কনটেন্ট</h2>
            <button 
              @click="toggleSidebar"
              class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
            >
              <PanelLeftIcon v-if="!sidebarCollapsed" class="w-5 h-5" />
              <PanelRightIcon v-else class="w-5 h-5" />
            </button>
          </div>

          <!-- Course Modules -->
          <div v-if="!sidebarCollapsed" class="flex-1 overflow-y-auto">
            <div v-for="module in course?.modules" :key="module.id" class="border-b border-gray-700">
              <!-- Module Header -->
              <button 
                @click="toggleModule(module.id)"
                class="w-full px-4 py-3 text-left bg-gray-750 hover:bg-gray-700 transition-colors flex items-center justify-between"
              >
                <div>
                  <h3 class="font-medium text-white">{{ module.title }}</h3>
                  <p class="text-sm text-gray-400">{{ module.lessons?.length || 0 }} পাঠ • {{ module.duration }}</p>
                </div>
                <ChevronDownIcon 
                  :class="[
                    'w-5 h-5 text-gray-400 transition-transform duration-200',
                    expandedModules.includes(module.id) ? 'rotate-180' : ''
                  ]" 
                />
              </button>
              
              <!-- Module Lessons -->
              <div v-show="expandedModules.includes(module.id)" class="bg-gray-800">
                <button 
                  v-for="lesson in module.lessons" 
                  :key="lesson.id"
                  @click="selectLesson(lesson)"
                  :class="[
                    'w-full px-6 py-3 text-left hover:bg-gray-700 transition-colors flex items-center space-x-3 border-l-4',
                    currentLesson?.id === lesson.id 
                      ? 'bg-gray-700 border-[#2d5a27] text-white' 
                      : 'border-transparent text-gray-300'
                  ]"
                >
                  <div class="flex-shrink-0">
                    <div v-if="lesson.completed" class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center">
                      <CheckIcon class="w-4 h-4 text-white" />
                    </div>
                    <div v-else-if="currentLesson?.id === lesson.id" class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                      <PlayIcon class="w-3 h-3 text-white" />
                    </div>
                    <div v-else class="w-6 h-6 border-2 border-gray-600 rounded-full"></div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-medium truncate">{{ lesson.title }}</p>
                    <div class="flex items-center space-x-2 text-sm text-gray-400">
                      <component :is="getLessonTypeIcon(lesson.type)" class="w-4 h-4" />
                      <span>{{ lesson.duration }}</span>
                    </div>
                  </div>
                </button>
              </div>
            </div>
          </div>

          <!-- Collapsed Sidebar Lessons -->
          <div v-else class="flex-1 overflow-y-auto py-2">
            <div v-for="lesson in allLessons" :key="lesson.id" class="px-2 mb-2">
              <button 
                @click="selectLesson(lesson)"
                :class="[
                  'w-8 h-8 rounded-full flex items-center justify-center transition-colors',
                  currentLesson?.id === lesson.id 
                    ? 'bg-[#5f5fcd] text-white' 
                    : lesson.completed
                      ? 'bg-[#2d5a27] text-white'
                      : 'bg-gray-600 text-gray-300 hover:bg-gray-500'
                ]"
                :title="lesson.title"
              >
                <CheckIcon v-if="lesson.completed" class="w-4 h-4" />
                <PlayIcon v-else-if="currentLesson?.id === lesson.id" class="w-3 h-3" />
                                  <span v-else class="text-xs">{{ lesson.order.toString() }}</span>
              </button>
            </div>
          </div>
        </aside>

        <!-- Main Learning Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
          
          <!-- Video/Content Area -->
          <div class="flex-1 bg-black flex items-center justify-center">
            <!-- Loading Content -->
            <div v-if="contentLoading" class="text-center">
              Loading...
            </div>

            <!-- Video Player -->
            <div v-else-if="currentLesson?.type === 'video'" class="w-full h-full">
              <iframe 
                :src="currentLesson.video_url" 
                class="w-full h-full" 
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                allowfullscreen
                @load="handleVideoLoad"
              ></iframe>
            </div>

            <!-- Text Content -->
            <div v-else-if="currentLesson?.type === 'text'" class="w-full h-full overflow-y-auto bg-white">
              <div class="max-w-4xl mx-auto px-8 py-12">
                <h1 class="text-3xl font-bold text-gray-900 mb-8">{{ currentLesson.title }}</h1>
                <div class="prose prose-lg max-w-none" v-html="currentLesson.content"></div>
              </div>
            </div>

            <!-- Quiz Content -->
            <div v-else-if="currentLesson?.type === 'quiz'" class="w-full h-full overflow-y-auto bg-gray-50">
              <div class="max-w-2xl mx-auto px-8 py-12">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ currentLesson.title }}</h1>
                <!-- Quiz component would go here -->
                <div class="bg-white rounded-lg p-6 shadow-lg">
                  <p class="text-gray-600">কুইজ কনটেন্ট এখানে আসবে...</p>
                </div>
              </div>
            </div>

            <!-- PDF Content -->
            <div v-else-if="currentLesson?.type === 'pdf'" class="w-full h-full">
              <iframe 
                :src="currentLesson.pdf_url" 
                class="w-full h-full"
                frameborder="0"
              ></iframe>
            </div>

            <!-- Audio Content -->
            <div v-else-if="currentLesson?.type === 'audio'" class="w-full h-full overflow-y-auto bg-white">
              <div class="max-w-2xl mx-auto px-8 py-12">
                <h1 class="text-2xl font-bold text-gray-900 mb-8">{{ currentLesson.title }}</h1>
                <div class="bg-white rounded-lg p-6 shadow-lg">
                  <audio controls class="w-full">
                    <source :src="currentLesson.audio_url" type="audio/mpeg">
                    আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                  </audio>
                  <div class="mt-4 prose prose-sm" v-html="currentLesson.content"></div>
                </div>
              </div>
            </div>
          </div>

          <!-- Lesson Controls -->
          <div class="bg-gray-800 border-t border-gray-700 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-4">
              <!-- Previous Lesson -->
              <PrimaryButton
                @click="goToPreviousLesson"
                :disabled="!hasPreviousLesson"
                variant="ghost"
                size="sm"
                :icon="ChevronLeftIcon"
                class="text-gray-300 hover:text-white disabled:opacity-50"
              >
                আগের পাঠ
              </PrimaryButton>

              <!-- Mark as Complete -->
              <PrimaryButton
                v-if="!currentLesson?.completed"
                @click="markLessonComplete"
                :loading="markingComplete"
                variant="secondary"
                size="sm"
                :icon="CheckCircleIcon"
              >
                সম্পূর্ণ হিসেবে চিহ্নিত করুন
              </PrimaryButton>
            </div>

            <div class="flex items-center space-x-4">
              <!-- Lesson Info -->
              <div class="text-sm text-gray-400">
                পাঠ {{ currentLessonIndex + 1 }} / {{ totalLessons }}
              </div>

              <!-- Next Lesson -->
              <PrimaryButton
                @click="goToNextLesson"
                :disabled="!hasNextLesson"
                variant="primary"
                size="sm"
                :icon="ChevronRightIcon"
                icon-position="right"
              >
                পরবর্তী পাঠ
              </PrimaryButton>
            </div>
          </div>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'
import { router, Head, usePage } from '@inertiajs/vue3'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  ArrowLeftIcon,
  PanelLeftIcon,
  PanelRightIcon,
  ChevronDownIcon,
  CheckIcon,
  PlayIcon,
  VideoIcon,
  FileTextIcon,
  HelpCircleIcon,
  FileIcon,
  HeadphonesIcon,
  ChevronLeftIcon,
  ChevronRightIcon,
  CheckCircleIcon,
  AlertTriangleIcon
} from 'lucide-vue-next'

// Type definitions
interface Lesson {
  id: number
  order: number
  title: string
  duration: string
  type: 'video' | 'text' | 'quiz' | 'pdf' | 'audio'
  video_url?: string
  content?: string
  pdf_url?: string
  audio_url?: string
  completed: boolean
  module_id: number
}

interface Module {
  id: number
  title: string
  duration: string
  lessons: Lesson[]
}

interface Course {
  id: number
  title: string
  slug: string
  modules: Module[]
  total_lessons: number
  completed_lessons: number
}

// Get data from props
const page = usePage()
const courseSlug = computed(() => (page.props.course_slug as string) || '')
const course = ref<Course | null>(null)
const contentLoading = ref(false)
const markingComplete = ref(false)

// State
const sidebarCollapsed = ref(false)
const expandedModules = ref<number[]>([])
const currentLesson = ref<Lesson | null>(null)
const completedLessons = ref(0)

// Computed properties
const allLessons = computed(() => {
  if (!course.value?.modules) return []
  return course.value.modules.flatMap(module => module.lessons)
})

const totalLessons = computed(() => allLessons.value.length)

const progressPercentage = computed(() => {
  return totalLessons.value > 0 ? (completedLessons.value / totalLessons.value) * 100 : 0
})

const currentLessonIndex = computed(() => {
  if (!currentLesson.value) return -1
  return allLessons.value.findIndex(lesson => lesson.id === currentLesson.value!.id)
})

const hasPreviousLesson = computed(() => currentLessonIndex.value > 0)

const hasNextLesson = computed(() => currentLessonIndex.value < totalLessons.value - 1)

// Methods
const loadCourseData = async () => {
  try {
    await router.get(route('frontend.learning.show', courseSlug.value), {}, {
      preserveState: true,
      preserveScroll: true,
      only: ['course', 'enrollment']
    })
    
    // Get updated data from page props
    const pageData = usePage()
    course.value = pageData.props.course as Course
    completedLessons.value = (pageData.props.enrollment as any)?.completed_lessons || 0
    
    // Set first lesson as current if none selected
    if (!currentLesson.value && allLessons.value.length > 0) {
      currentLesson.value = allLessons.value[0]
      // Expand the module containing this lesson
      const module = course.value?.modules.find(m => m.lessons.some(l => l.id === currentLesson.value!.id))
      if (module) {
        expandedModules.value = [module.id]
      }
    }
  } catch (err) {
    console.error('Error loading course:', err)
  }
}

const toggleSidebar = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value
}

const toggleModule = (moduleId: number) => {
  if (expandedModules.value.includes(moduleId)) {
    expandedModules.value = expandedModules.value.filter(id => id !== moduleId)
  } else {
    expandedModules.value.push(moduleId)
  }
}

const selectLesson = async (lesson: Lesson) => {
  contentLoading.value = true
  currentLesson.value = lesson
  
  // Expand the module containing this lesson
  const module = course.value?.modules.find(m => m.lessons.some(l => l.id === lesson.id))
  if (module && !expandedModules.value.includes(module.id)) {
    expandedModules.value.push(module.id)
  }
  
  // Simulate content loading
  setTimeout(() => {
    contentLoading.value = false
  }, 500)
}

const goToPreviousLesson = () => {
  if (hasPreviousLesson.value) {
    const previousLesson = allLessons.value[currentLessonIndex.value - 1]
    selectLesson(previousLesson)
  }
}

const goToNextLesson = () => {
  if (hasNextLesson.value) {
    const nextLesson = allLessons.value[currentLessonIndex.value + 1]
    selectLesson(nextLesson)
  }
}

const markLessonComplete = async () => {
  if (!currentLesson.value) return
  
  markingComplete.value = true
  
  try {
    await router.post(route('frontend.learning.complete-lesson'), {
      lesson_id: currentLesson.value.id,
      course_id: course.value?.id
    }, {
      preserveState: true,
      preserveScroll: true,
      only: ['enrollment']
    })
    
    currentLesson.value.completed = true
    completedLessons.value++
    
    // Auto-advance to next lesson after a delay
    if (hasNextLesson.value) {
      setTimeout(() => {
        goToNextLesson()
      }, 1000)
    }
  } catch (err) {
    console.error('Error marking lesson complete:', err)
  } finally {
    markingComplete.value = false
  }
}

const handleVideoLoad = () => {
  console.log('Video loaded')
}

const goBackToDashboard = () => {
  router.visit(route('frontend.student.dashboard'))
}

const getLessonTypeIcon = (type: string) => {
  switch (type) {
    case 'video':
      return VideoIcon
    case 'text':
      return FileTextIcon
    case 'quiz':
      return HelpCircleIcon
    case 'pdf':
      return FileIcon
    case 'audio':
      return HeadphonesIcon
    default:
      return FileTextIcon
  }
}

// Initialize on mount
onMounted(() => {
  loadCourseData()
})

// Watch for course slug changes
watch(courseSlug, () => {
  if (courseSlug.value) {
    loadCourseData()
  }
})
</script>

<style scoped>
/* Custom scrollbar for sidebar */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #374151;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #6b7280;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Ensure video responsiveness */
iframe {
  min-height: 400px;
}

@media (max-width: 768px) {
  iframe {
    min-height: 250px;
  }
}

/* Prose styles for text content */
.prose {
  color: #6b7280;
}

.prose h1, .prose h2, .prose h3, .prose h4 {
  color: #111827;
  font-weight: 600;
  margin-bottom: 1rem;
  margin-top: 2rem;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.625;
}

.prose ul, .prose ol {
  margin-bottom: 1.5rem;
}

.prose li {
  margin-bottom: 0.5rem;
}
</style> 