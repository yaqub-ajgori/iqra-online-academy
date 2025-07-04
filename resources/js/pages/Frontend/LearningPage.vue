<template>
  <div class="h-screen bg-gray-50 flex flex-col overflow-hidden">
    <Head :title="`${currentLesson?.title || 'Loading...'} - ${course?.title || 'Course'}`" />

    <!-- Main Learning Interface -->
    <div class="h-screen flex flex-col overflow-hidden">
      <!-- Header Bar -->
      <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between z-10 shadow-sm">
        <div class="flex items-center space-x-4">
          <!-- Back to Dashboard -->
          <PrimaryButton
            @click="goBackToDashboard"
            variant="ghost"
            size="sm"
            :icon="ArrowLeftIcon"
            class="inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 px-3 py-1.5 text-sm bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white hover:shadow-islamic-lg hover:from-[#1f3e1b] hover:to-[#4a4aa6] border border-transparent rounded-lg text-[#5f5fcd] border-[#5f5fcd] hover:bg-[#5f5fcd] hover:text-white"
          >
            ড্যাশবোর্ডে ফিরে যান
          </PrimaryButton>
          <div class="h-6 w-px bg-gray-200"></div>
          <!-- Course Title -->
          <div>
            <h1 class="hidden sm:block text-lg sm:text-xl font-bold text-gray-900">{{ course?.title }}</h1>
            <p class="text-xs sm:text-sm text-gray-500">{{ currentLesson?.title }}</p>
          </div>
        </div>
        <!-- Progress -->
        <div class="flex items-center space-x-4">
          <div class="text-sm text-gray-500">
            {{ completedLessons }}/{{ totalLessons }} পাঠ সম্পন্ন
          </div>
          <div class="w-32 bg-gray-200 rounded-full h-2">
            <div 
              class="bg-[#5f5fcd] h-2 rounded-full transition-all duration-300"
              :style="{ width: progressPercentage + '%' }"
            ></div>
          </div>
          <span class="text-sm text-gray-700">{{ Math.round(progressPercentage) }}%</span>
        </div>
      </header>

      <!-- Main Content Area -->
      <div class="flex flex-1 overflow-hidden bg-gray-50">
        <!-- Collapsible Sidebar -->
        <transition name="sidebar-slide" mode="out-in">
          <aside 
            :key="sidebarCollapsed ? 'collapsed' : 'expanded'"
            :class="[
              'bg-white border-r border-gray-200 transition-all duration-500 flex flex-col shadow-lg z-20',
              sidebarCollapsed ? 'w-14 min-w-[56px]' : 'w-80 min-w-[320px]'
            ]"
          >
            <!-- Sidebar Header -->
            <div class="p-4 border-b border-gray-100 flex items-center justify-between">
              <h2 v-if="!sidebarCollapsed" class="text-lg font-bold text-[#5f5fcd] tracking-tight">কোর্স কনটেন্ট</h2>
              <button 
                @click="toggleSidebar"
                class="p-2 text-[#5f5fcd] hover:text-white hover:bg-[#5f5fcd] rounded-lg transition-colors"
                :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
              >
                <PanelLeftIcon v-if="!sidebarCollapsed" class="w-5 h-5" />
                <PanelRightIcon v-else class="w-5 h-5" />
              </button>
            </div>

            <!-- Course Modules -->
            <div v-if="!sidebarCollapsed" class="flex-1 overflow-y-auto py-2 pr-1">
              <div v-for="module in course?.modules" :key="module.id" class="mb-2 rounded-xl overflow-hidden bg-white shadow-sm border border-gray-100">
                <!-- Module Header -->
                <button 
                  @click="toggleModule(module.id)"
                  class="w-full px-4 py-3 text-left bg-white hover:bg-[#f5f6fd] transition-colors flex items-center justify-between group"
                >
                  <div>
                    <h3 class="text-base font-medium text-gray-900 group-hover:text-[#5f5fcd]">{{ module.title }}</h3>
                    <p class="text-xs text-gray-500">{{ module.lessons?.length || 0 }} পাঠ • {{ module.duration }}</p>
                  </div>
                  <ChevronDownIcon 
                    :class="[
                      'w-5 h-5 text-gray-400 transition-transform duration-300',
                      expandedModules.includes(module.id) ? 'rotate-180 text-[#5f5fcd]' : ''
                    ]" 
                  />
                </button>
                <!-- Module Lessons -->
                <transition name="module-scale-rotate">
                  <div v-show="expandedModules.includes(module.id)" class="bg-[#f5f6fd] overflow-hidden">
                    <button 
                      v-for="lesson in module.lessons" 
                      :key="lesson.id"
                      @click="!isLessonLocked(lesson) && selectLesson(lesson)"
                      :disabled="isLessonLocked(lesson)"
                      :class="[
                        'w-full px-6 py-3 text-left transition-colors flex items-center space-x-3 border-l-4',
                        isLessonLocked(lesson)
                          ? 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed opacity-60'
                          : currentLesson?.id === lesson.id
                            ? 'bg-[#e7eafd] border-[#5f5fcd] text-[#5f5fcd] font-semibold hover:bg-[#e7eafd]'
                            : lesson.completed
                              ? 'border-[#2d5a27] text-[#2d5a27] bg-white hover:bg-[#e7eafd]'
                              : 'border-transparent text-gray-700 bg-white hover:bg-[#e7eafd]'
                      ]"
                      :title="isLessonLocked(lesson) ? 'Complete previous lessons to unlock.' : lesson.title"
                    >
                      <div class="flex-shrink-0">
                        <div v-if="lesson.completed" class="w-6 h-6 bg-[#2d5a27] rounded-full flex items-center justify-center">
                          <CheckIcon class="w-4 h-4 text-white" />
                        </div>
                        <div v-else-if="currentLesson?.id === lesson.id" class="w-6 h-6 bg-[#5f5fcd] rounded-full flex items-center justify-center">
                          <PlayIcon class="w-3 h-3 text-white" />
                        </div>
                        <div v-else-if="isLessonLocked(lesson)" class="w-6 h-6 border-2 border-gray-300 rounded-full flex items-center justify-center">
                          <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 12V8a4 4 0 10-8 0v4M5 12h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"/></svg>
                        </div>
                        <div v-else class="w-6 h-6 border-2 border-gray-300 rounded-full"></div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="truncate">{{ lesson.title }}</p>
                        <div class="flex items-center space-x-2 text-xs text-gray-400">
                          <component :is="getLessonTypeIcon(lesson.type)" class="w-4 h-4" />
                          <span>{{ lesson.duration }}</span>
                        </div>
                      </div>
                    </button>
                  </div>
                </transition>
              </div>
            </div>

            <!-- Collapsed Sidebar Lessons -->
            <div v-else class="flex-1 overflow-y-auto py-2 flex flex-col items-center gap-2">
              <div v-for="lesson in allLessons" :key="lesson.id" class="">
                <button 
                  @click="selectLesson(lesson)"
                  :class="[
                    'w-10 h-10 rounded-full flex items-center justify-center transition-colors',
                    currentLesson?.id === lesson.id 
                      ? 'bg-[#5f5fcd] text-white shadow-lg' 
                      : lesson.completed
                        ? 'bg-[#2d5a27] text-white'
                        : 'bg-gray-200 text-gray-400 hover:bg-gray-300'
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
        </transition>

        <!-- Main Learning Content -->
        <main
          class="flex-1 flex flex-col overflow-hidden bg-white rounded-tl-3xl shadow-xl transition-all duration-500"
          :class="sidebarCollapsed ? 'rounded-none' : 'rounded-tl-3xl'"
        >
          <!-- Video/Content Area: Add bottom padding for controls bar -->
          <div
            class="flex-1 flex items-center justify-center transition-all duration-500 pb-[80px]"
            :class="'px-0 sm:px-0'"
          >
            <!-- Loading Content -->
            <div v-if="contentLoading" class="text-center text-lg text-gray-400 font-medium">
              Loading...
            </div>
            <!-- Responsive Video Player -->
            <div
              v-else-if="currentLesson?.type === 'video' && currentLesson.video_url"
              class="w-full h-full"
            >
              <div class="relative w-full" style="padding-top: 56.25%;">
                <iframe
                  :src="getEmbedUrl(currentLesson.video_url)"
                  class="absolute top-0 left-0 w-full h-full rounded-xl shadow-lg border border-gray-200"
                  frameborder="0"
                  allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen
                  @load="handleVideoLoad"
                  title="Lesson Video"
                ></iframe>
              </div>
              <div class="mt-6 text-center">
                <h2 class="text-base sm:text-2xl font-bold text-gray-900 mb-2 leading-tight">{{ currentLesson.title }}</h2>
                <p class="text-base text-gray-500">ভিডিও পাঠ</p>
              </div>
            </div>
            <!-- Text Content -->
            <div
              v-else-if="currentLesson?.type === 'text'"
              class="w-full h-full bg-white p-4 sm:p-8 overflow-y-auto"
            >
              <h1 class="text-xl sm:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">{{ currentLesson.title }}</h1>
              <div class="prose prose-lg max-w-none text-gray-800 leading-relaxed" v-html="currentLesson.content"></div>
            </div>
            <!-- Quiz Content -->
            <div
              v-else-if="currentLesson?.type === 'quiz'"
              class="w-full h-full bg-white p-4 sm:p-8 overflow-y-auto"
            >
              <h1 class="text-lg sm:text-2xl font-bold text-gray-900 mb-6 leading-tight">{{ currentLesson.title }}</h1>
              <div class="bg-[#f5f6fd] rounded-lg p-6 text-gray-600 text-base">
                কুইজ কনটেন্ট এখানে আসবে...
              </div>
            </div>
            <!-- PDF Content -->
            <div
              v-else-if="currentLesson?.type === 'pdf' && currentLesson.pdf_url"
              class="w-full h-full rounded-none border-none shadow-none"
              style="height: 70vh;"
            >
              <iframe
                :src="currentLesson.pdf_url"
                class="w-full h-full rounded-xl"
                frameborder="0"
                title="Lesson PDF"
              ></iframe>
            </div>
            <!-- Audio Content -->
            <div
              v-else-if="currentLesson?.type === 'audio' && currentLesson.audio_url"
              class="w-full h-full bg-white p-4 sm:p-8 overflow-y-auto"
            >
              <h1 class="text-lg sm:text-2xl font-bold text-gray-900 mb-6 leading-tight">{{ currentLesson.title }}</h1>
              <audio controls class="w-full mb-4">
                <source :src="currentLesson.audio_url" type="audio/mpeg">
                আপনার ব্রাউজার অডিও সাপোর্ট করে না।
              </audio>
              <div class="prose prose-base text-gray-700" v-html="currentLesson.content"></div>
            </div>
          </div>
          <!-- Lesson Controls: Always fixed at bottom -->
          <div
            class="bg-[#f5f6fd] border-t border-gray-200 px-6 py-4 flex items-center justify-between transition-all duration-500 z-30 fixed left-0 right-0 bottom-0 w-full max-w-none mx-0 shadow-lg"
            style="min-height: 64px;"
          >
            <div class="flex items-center space-x-4">
              <!-- Previous Lesson -->
              <PrimaryButton
                @click="goToPreviousLesson"
                :disabled="!hasPreviousLesson"
                variant="ghost"
                size="sm"
                :icon="ChevronLeftIcon"
                class="inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 px-3 py-1.5 text-sm bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white hover:shadow-islamic-lg hover:from-[#1f3e1b] hover:to-[#4a4aa6] border border-transparent rounded-lg text-[#5f5fcd] border-[#5f5fcd] hover:bg-[#5f5fcd] hover:text-white"
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
                class="inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 px-3 py-1.5 text-sm bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white hover:shadow-islamic-lg hover:from-[#1f3e1b] hover:to-[#4a4aa6] border border-transparent rounded-lg text-[#5f5fcd] border-[#5f5fcd] hover:bg-[#5f5fcd] hover:text-white"
              >
                সম্পূর্ণ হিসেবে চিহ্নিত করুন
              </PrimaryButton>
            </div>
            <div class="flex items-center space-x-4">
              <!-- Lesson Info -->
              <div class="text-sm text-gray-500">
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
                class="bg-[#5f5fcd] border-[#5f5fcd] hover:bg-[#4a4ab8] text-white disabled:opacity-50"
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

// Helper: Get a flat list of all lessons in order
const flatLessons = computed(() => {
  if (!course.value?.modules) return []
  return course.value.modules.flatMap(module => module.lessons)
})

// Helper: Get the index of a lesson in the flat list
function getLessonIndex(lessonId: number) {
  return flatLessons.value.findIndex(l => l.id === lessonId)
}

// Helper: Is a lesson locked?
function isLessonLocked(lesson: Lesson) {
  const idx = getLessonIndex(lesson.id)
  if (idx === 0) return false // First lesson always unlocked
  // All previous lessons must be completed
  return !flatLessons.value.slice(0, idx).every(l => l.completed)
}

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
    expandedModules.value = [] // Close all if clicking the open one
  } else {
    expandedModules.value = [moduleId] // Only one open at a time
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

// Add a utility to convert YouTube/Vimeo URLs to embed URLs
function getEmbedUrl(url: string): string {
  if (!url) return ''
  // YouTube
  const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/)
  if (ytMatch) {
    return `https://www.youtube.com/embed/${ytMatch[1]}?rel=0&showinfo=0&autoplay=0`;
  }
  // Vimeo
  const vimeoMatch = url.match(/vimeo\.com\/(\d+)/)
  if (vimeoMatch) {
    return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
  }
  // Default: return as-is
  return url;
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

/* Video aspect ratio utility */
.responsive-video {
  position: relative;
  width: 100%;
  padding-top: 56.25%; /* 16:9 Aspect Ratio */
}
.responsive-video iframe {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 0.75rem;
  box-shadow: 0 4px 24px 0 rgba(0,0,0,0.08);
  background: #000;
}

/* Prose overrides for lesson content */
.prose-lg {
  font-size: 1.15rem;
  line-height: 1.8;
}
.prose h1, .prose h2, .prose h3 {
  color: #5f5fcd;
  font-weight: 700;
}
.prose strong {
  color: #222;
}

/* Accordion animation */
.module-scale-rotate-enter-active,
.module-scale-rotate-leave-active {
  transition: opacity 0.4s cubic-bezier(0.4,0,0.2,1), transform 0.5s cubic-bezier(0.4,0,0.2,1);
  will-change: opacity, transform;
  transform-origin: top center;
}
.module-scale-rotate-enter-from,
.module-scale-rotate-leave-to {
  opacity: 0;
  transform: scale(0.95) rotateX(-10deg);
}
.module-scale-rotate-enter-to,
.module-scale-rotate-leave-from {
  opacity: 1;
  transform: scale(1) rotateX(0deg);
}
</style> 