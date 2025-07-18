<template>
    <div class="flex h-screen flex-col overflow-hidden bg-gray-50">
        <Head :title="`${currentLesson?.title || 'Loading...'} - ${course?.title || 'Course'}`" />

        <!-- Main Learning Interface -->
        <div class="flex h-screen flex-col overflow-hidden">
            <!-- Header Bar -->
            <header class="z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-3 shadow-sm">
                <div class="flex items-center space-x-4">
                    <!-- Back to Dashboard -->
                    <PrimaryButton
                        @click="goBackToDashboard"
                        variant="ghost"
                        size="sm"
                        :icon="ArrowLeftIcon"
                        class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        ড্যাশবোর্ডে ফিরে যান
                    </PrimaryButton>
                    <div class="h-6 w-px bg-gray-200"></div>
                    <!-- Course Title -->
                    <div>
                        <h1 class="hidden text-lg font-bold text-gray-900 sm:block sm:text-xl">{{ course?.title }}</h1>
                        <p class="text-xs text-gray-500 sm:text-sm">{{ currentLesson?.title }}</p>
                    </div>
                </div>
                <!-- Progress -->
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">{{ completedLessons }}/{{ totalLessons }} পাঠ সম্পন্ন</div>
                    <div class="h-2 w-32 rounded-full bg-gray-200">
                        <div class="h-2 rounded-full bg-[#5f5fcd] transition-all duration-300" :style="{ width: progressPercentage + '%' }"></div>
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
                            'z-20 flex flex-col border-r border-gray-200 bg-white shadow-lg transition-all duration-500',
                            sidebarCollapsed ? 'w-14 min-w-[56px]' : 'w-80 min-w-[320px]',
                        ]"
                    >
                        <!-- Sidebar Header -->
                        <div class="flex items-center justify-between border-b border-gray-100 p-4">
                            <h2 v-if="!sidebarCollapsed" class="text-lg font-bold tracking-tight text-[#5f5fcd]">কোর্স কনটেন্ট</h2>
                            <button
                                @click="toggleSidebar"
                                class="rounded-lg p-2 text-[#5f5fcd] transition-colors hover:bg-[#5f5fcd] hover:text-white"
                                :aria-label="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
                            >
                                <PanelLeftIcon v-if="!sidebarCollapsed" class="h-5 w-5" />
                                <PanelRightIcon v-else class="h-5 w-5" />
                            </button>
                        </div>

                        <!-- Course Modules -->
                        <div v-if="!sidebarCollapsed" class="flex-1 overflow-y-auto py-2 pr-1">
                            <div
                                v-for="module in course?.modules"
                                :key="module.id"
                                class="mb-2 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm"
                            >
                                <!-- Module Header -->
                                <button
                                    @click="toggleModule(module.id)"
                                    class="group flex w-full items-center justify-between bg-white px-4 py-3 text-left transition-colors hover:bg-[#f5f6fd]"
                                >
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900 group-hover:text-[#5f5fcd]">{{ module.title }}</h3>
                                        <p class="text-xs text-gray-500">{{ module.lessons?.length || 0 }} পাঠ • {{ module.duration }}</p>
                                    </div>
                                    <ChevronDownIcon
                                        :class="[
                                            'h-5 w-5 text-gray-400 transition-transform duration-300',
                                            expandedModules.includes(module.id) ? 'rotate-180 text-[#5f5fcd]' : '',
                                        ]"
                                    />
                                </button>
                                <!-- Module Lessons -->
                                <transition name="module-scale-rotate">
                                    <div v-show="expandedModules.includes(module.id)" class="overflow-hidden bg-[#f5f6fd]">
                                        <button
                                            v-for="lesson in module.lessons"
                                            :key="lesson.id"
                                            @click="!isLessonLocked(lesson) && selectLesson(lesson)"
                                            :disabled="isLessonLocked(lesson)"
                                            :class="[
                                                'flex w-full items-center space-x-3 border-l-4 px-6 py-3 text-left transition-colors',
                                                isLessonLocked(lesson)
                                                    ? 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400 opacity-60'
                                                    : currentLesson?.id === lesson.id
                                                      ? 'border-[#5f5fcd] bg-[#e7eafd] font-semibold text-[#5f5fcd] hover:bg-[#e7eafd]'
                                                      : lesson.completed
                                                        ? 'border-[#2d5a27] bg-white text-[#2d5a27] hover:bg-[#e7eafd]'
                                                        : 'border-transparent bg-white text-gray-700 hover:bg-[#e7eafd]',
                                            ]"
                                            :title="isLessonLocked(lesson) ? 'Complete previous lessons to unlock.' : lesson.title"
                                        >
                                            <div class="flex-shrink-0">
                                                <div
                                                    v-if="lesson.completed"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#2d5a27]"
                                                >
                                                    <CheckIcon class="h-4 w-4 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="currentLesson?.id === lesson.id"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#5f5fcd]"
                                                >
                                                    <PlayIcon class="h-3 w-3 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="isLessonLocked(lesson)"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full border-2 border-gray-300"
                                                >
                                                    <svg
                                                        class="h-3 w-3 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M16 12V8a4 4 0 10-8 0v4M5 12h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z"
                                                        />
                                                    </svg>
                                                </div>
                                                <div v-else class="h-6 w-6 rounded-full border-2 border-gray-300"></div>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <p class="truncate">{{ lesson.title }}</p>
                                                <div class="flex items-center space-x-2 text-xs text-gray-400">
                                                    <component :is="getLessonTypeIcon(lesson.type)" class="h-4 w-4" />
                                                    <span>{{ lesson.duration }}</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <!-- Collapsed Sidebar Lessons -->
                        <div v-else class="flex flex-1 flex-col items-center gap-2 overflow-y-auto py-2">
                            <div v-for="lesson in allLessons" :key="lesson.id" class="">
                                <button
                                    @click="selectLesson(lesson)"
                                    :class="[
                                        'flex h-10 w-10 items-center justify-center rounded-full transition-colors',
                                        currentLesson?.id === lesson.id
                                            ? 'bg-[#5f5fcd] text-white shadow-lg'
                                            : lesson.completed
                                              ? 'bg-[#2d5a27] text-white'
                                              : 'bg-gray-200 text-gray-400 hover:bg-gray-300',
                                    ]"
                                    :title="lesson.title"
                                >
                                    <CheckIcon v-if="lesson.completed" class="h-4 w-4" />
                                    <PlayIcon v-else-if="currentLesson?.id === lesson.id" class="h-3 w-3" />
                                    <span v-else class="text-xs">{{ lesson.order.toString() }}</span>
                                </button>
                            </div>
                        </div>
                    </aside>
                </transition>

                <!-- Main Learning Content -->
                <main
                    class="flex flex-1 flex-col overflow-hidden rounded-tl-3xl bg-white shadow-xl transition-all duration-500"
                    :class="sidebarCollapsed ? 'rounded-none' : 'rounded-tl-3xl'"
                >
                    <!-- Video/Content Area: Add bottom padding for controls bar -->
                    <div class="flex flex-1 items-center justify-center pb-[80px] transition-all duration-500" :class="'px-0 sm:px-0'">
                        <!-- Loading Content -->
                        <div v-if="contentLoading" class="text-center text-lg font-medium text-gray-400">Loading...</div>
                        <!-- Responsive Video Player -->
                        <div v-else-if="currentLesson?.type === 'video' && currentLesson.video_url" class="h-full w-full">
                            <div class="relative w-full" style="padding-top: 56.25%">
                                <iframe
                                    :src="getEmbedUrl(currentLesson.video_url)"
                                    class="absolute top-0 left-0 h-full w-full rounded-xl border border-gray-200 shadow-lg"
                                    frameborder="0"
                                    sandbox="allow-scripts allow-same-origin allow-presentation allow-fullscreen"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; fullscreen"
                                    allowfullscreen
                                    @load="handleVideoLoad"
                                    title="Lesson Video"
                                    loading="lazy"
                                ></iframe>
                            </div>
                            <div class="mt-6 text-center">
                                <h2 class="mb-2 text-base leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson.title }}</h2>
                                <p class="text-base text-gray-500">ভিডিও পাঠ</p>
                            </div>
                        </div>
                        <!-- Text Content -->
                        <div v-else-if="currentLesson?.type === 'text'" class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8">
                            <h1 class="mb-6 text-xl leading-tight font-extrabold text-gray-900 sm:text-3xl">{{ currentLesson.title }}</h1>
                            <div class="prose prose-lg max-w-none leading-relaxed text-gray-800" v-html="sanitizeHtml(currentLesson.content)"></div>
                        </div>
                        <!-- Quiz Content -->
                        <div v-else-if="currentLesson?.type === 'quiz'" class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8">
                            <h1 class="mb-6 text-lg leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson.title }}</h1>
                            <div class="rounded-lg bg-[#f5f6fd] p-6 text-base text-gray-600">কুইজ কনটেন্ট এখানে আসবে...</div>
                        </div>
                        <!-- PDF Content -->
                        <div
                            v-else-if="currentLesson?.type === 'pdf' && currentLesson.pdf_url"
                            class="h-full w-full rounded-none border-none shadow-none"
                            style="height: 70vh"
                        >
                            <iframe :src="currentLesson.pdf_url" class="h-full w-full rounded-xl" frameborder="0" title="Lesson PDF"></iframe>
                        </div>
                        <!-- Audio Content -->
                        <div
                            v-else-if="currentLesson?.type === 'audio' && currentLesson.audio_url"
                            class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8"
                        >
                            <h1 class="mb-6 text-lg leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson.title }}</h1>
                            <audio controls class="mb-4 w-full">
                                <source :src="currentLesson.audio_url" type="audio/mpeg" />
                                আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                            </audio>
                            <div class="prose prose-base text-gray-700" v-html="sanitizeHtml(currentLesson.content)"></div>
                        </div>
                    </div>
                    <!-- Lesson Controls: Always fixed at bottom -->
                    <div
                        class="sticky right-0 bottom-0 left-0 z-30 flex w-full flex-col items-center justify-between border-t border-gray-200 bg-[#f5f6fd] px-4 py-3 shadow-lg transition-all duration-500 sm:flex-row sm:px-6 sm:py-4"
                        style="min-height: 64px"
                    >
                        <div class="mb-2 flex items-center space-x-2 sm:mb-0 sm:space-x-4">
                            <!-- Previous Lesson -->
                            <PrimaryButton
                                @click="goToPreviousLesson"
                                :disabled="!hasPreviousLesson"
                                variant="ghost"
                                size="sm"
                                :icon="ChevronLeftIcon"
                                class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
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
                                class="hover:shadow-islamic-lg inline-flex transform items-center justify-center rounded-lg border border-[#5f5fcd] border-transparent bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-3 py-1.5 text-sm font-medium text-[#5f5fcd] text-white transition-all duration-200 ease-in-out hover:scale-105 hover:bg-[#5f5fcd] hover:from-[#1f3e1b] hover:to-[#4a4aa6] hover:text-white focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none active:scale-95 disabled:cursor-not-allowed disabled:opacity-50"
                            >
                                সম্পূর্ণ হিসেবে চিহ্নিত করুন
                            </PrimaryButton>
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Lesson Info -->
                            <div class="text-sm text-gray-500">পাঠ {{ currentLessonIndex + 1 }} / {{ totalLessons }}</div>
                            <!-- Next Lesson -->
                            <PrimaryButton
                                @click="goToNextLesson"
                                :disabled="!hasNextLesson"
                                variant="primary"
                                size="sm"
                                :icon="ChevronRightIcon"
                                icon-position="right"
                                class="border-[#5f5fcd] bg-[#5f5fcd] text-white hover:bg-[#4a4ab8] disabled:opacity-50"
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
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { useIntervalFn } from '@vueuse/core';
import {
    ArrowLeftIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    FileIcon,
    FileTextIcon,
    HeadphonesIcon,
    HelpCircleIcon,
    PanelLeftIcon,
    PanelRightIcon,
    PlayIcon,
    VideoIcon,
} from 'lucide-vue-next';
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

// Type definitions
interface Lesson {
    id: number;
    order: number;
    title: string;
    duration: string;
    type: 'video' | 'text' | 'quiz' | 'pdf' | 'audio';
    video_url?: string;
    content?: string;
    pdf_url?: string;
    audio_url?: string;
    completed: boolean;
    module_id: number;
}

interface Module {
    id: number;
    title: string;
    duration: string;
    lessons: Lesson[];
}

interface Course {
    id: number;
    title: string;
    slug: string;
    modules: Module[];
    total_lessons: number;
    completed_lessons: number;
}

// Get data from props
const page = usePage();
const courseSlug = computed(() => (page.props.course_slug as string) || '');
const course = ref<Course | null>(null);
const contentLoading = ref(false);
const markingComplete = ref(false);

// State
const sidebarCollapsed = ref(false);
const expandedModules = ref<number[]>([]);
const currentLesson = ref<Lesson | null>(null);
const completedLessons = ref(0);

// Computed properties
const allLessons = computed(() => {
    if (!course.value?.modules) return [];
    return course.value.modules.flatMap((module) => module.lessons);
});

const totalLessons = computed(() => allLessons.value.length);

const progressPercentage = computed(() => {
    return totalLessons.value > 0 ? (completedLessons.value / totalLessons.value) * 100 : 0;
});

const currentLessonIndex = computed(() => {
    if (!currentLesson.value) return -1;
    return allLessons.value.findIndex((lesson) => lesson.id === currentLesson.value!.id);
});

const hasPreviousLesson = computed(() => currentLessonIndex.value > 0);

const hasNextLesson = computed(() => currentLessonIndex.value < totalLessons.value - 1);

// Helper: Get a flat list of all lessons in order
const flatLessons = computed(() => {
    if (!course.value?.modules) return [];
    return course.value.modules.flatMap((module) => module.lessons);
});

// Helper: Get the index of a lesson in the flat list
function getLessonIndex(lessonId: number) {
    return flatLessons.value.findIndex((l) => l.id === lessonId);
}

// Helper: Is a lesson locked?
function isLessonLocked(lesson: Lesson) {
    const idx = getLessonIndex(lesson.id);
    if (idx === 0) return false; // First lesson always unlocked
    // All previous lessons must be completed
    return !flatLessons.value.slice(0, idx).every((l) => l.completed);
}

// Methods
const loadCourseData = async () => {
    try {
        await router.get(
            route('frontend.learning.show', courseSlug.value),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                only: ['course', 'enrollment'],
            },
        );

        // Get updated data from page props
        const pageData = usePage();
        course.value = pageData.props.course as Course;
        completedLessons.value = (pageData.props.enrollment as any)?.completed_lessons || 0;

        // Set first lesson as current if none selected
        if (!currentLesson.value && allLessons.value.length > 0) {
            currentLesson.value = allLessons.value[0];
            // Expand the module containing this lesson
            const module = course.value?.modules.find((m) => m.lessons.some((l) => l.id === currentLesson.value!.id));
            if (module) {
                expandedModules.value = [module.id];
            }
        }
    } catch (err) {
        console.error('Error loading course:', err);
    }
};

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const toggleModule = (moduleId: number) => {
    if (expandedModules.value.includes(moduleId)) {
        expandedModules.value = []; // Close all if clicking the open one
    } else {
        expandedModules.value = [moduleId]; // Only one open at a time
    }
};

const selectLesson = async (lesson: Lesson) => {
    contentLoading.value = true;
    currentLesson.value = lesson;

    // Expand the module containing this lesson
    const module = course.value?.modules.find((m) => m.lessons.some((l) => l.id === lesson.id));
    if (module && !expandedModules.value.includes(module.id)) {
        expandedModules.value.push(module.id);
    }

    // Simulate content loading
    setTimeout(() => {
        contentLoading.value = false;
    }, 500);
};

const goToPreviousLesson = () => {
    if (hasPreviousLesson.value) {
        const previousLesson = allLessons.value[currentLessonIndex.value - 1];
        selectLesson(previousLesson);
    }
};

const goToNextLesson = () => {
    if (hasNextLesson.value) {
        const nextLesson = allLessons.value[currentLessonIndex.value + 1];
        selectLesson(nextLesson);
    }
};

const markLessonComplete = async () => {
    if (!currentLesson.value) return;

    markingComplete.value = true;

    try {
        await router.post(
            route('frontend.learning.complete-lesson'),
            {
                lesson_id: currentLesson.value.id,
                course_id: course.value?.id,
            },
            {
                preserveState: true,
                preserveScroll: true,
                only: ['enrollment'],
            },
        );

        currentLesson.value.completed = true;
        completedLessons.value++;

        // Auto-advance to next lesson after a delay
        if (hasNextLesson.value) {
            setTimeout(() => {
                goToNextLesson();
            }, 1000);
        }
    } catch (err) {
        console.error('Error marking lesson complete:', err);
    } finally {
        markingComplete.value = false;
    }
};

const goBackToDashboard = () => {
    router.visit(route('frontend.student.dashboard'));
};

const getLessonTypeIcon = (type: string) => {
    switch (type) {
        case 'video':
            return VideoIcon;
        case 'text':
            return FileTextIcon;
        case 'quiz':
            return HelpCircleIcon;
        case 'pdf':
            return FileIcon;
        case 'audio':
            return HeadphonesIcon;
        default:
            return FileTextIcon;
    }
};

// Add a utility to convert YouTube/Vimeo URLs to embed URLs
function getEmbedUrl(url: string): string {
    if (!url) return '';
    // YouTube
    const ytMatch = url.match(/(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))([\w-]{11})/);
    if (ytMatch) {
        return `https://www.youtube.com/embed/${ytMatch[1]}?rel=0&showinfo=0&autoplay=0`;
    }
    // Vimeo
    const vimeoMatch = url.match(/vimeo\.com\/(\d+)/);
    if (vimeoMatch) {
        return `https://player.vimeo.com/video/${vimeoMatch[1]}`;
    }
    // Default: return as-is
    return url;
}

const sanitizeHtml = (html: string): string => {
    if (!html) return '';
    // Create a new DOMParser to safely parse HTML without executing scripts
    const parser = new DOMParser();
    const doc = parser.parseFromString(html, 'text/html');
    return doc.body.innerHTML || '';
};

// Initialize on mount
onMounted(() => {
    loadCourseData();
});

// Watch for course slug changes
watch(courseSlug, () => {
    if (courseSlug.value) {
        loadCourseData();
    }
});

// Track if user is actively learning (watching videos, reading content)
const isActiveLearning = ref(false);
const lastActivity = ref(Date.now());

// Set active learning state when user interacts with content
const updateActivity = () => {
    isActiveLearning.value = true;
    lastActivity.value = Date.now();
};

// Stop active learning after 5 minutes of inactivity
const checkInactivity = () => {
    if (Date.now() - lastActivity.value > 300000) {
        // 5 minutes
        isActiveLearning.value = false;
    }
};

// Poll lesson progress every 60 seconds during active learning
const { pause: pauseProgressPolling, resume: resumeProgressPolling } = useIntervalFn(
    () => {
        if (isActiveLearning.value && courseSlug.value) {
            router.reload({ only: ['enrollment'] });
        }
        checkInactivity();
    },
    60000,
    { immediate: false },
);

// Start polling when user becomes active
watch(isActiveLearning, (active) => {
    if (active) {
        resumeProgressPolling();
    } else {
        pauseProgressPolling();
    }
});

// Add event listeners to track user activity
onMounted(() => {
    const events = ['click', 'scroll', 'keydown', 'mousemove'];
    events.forEach((event) => {
        document.addEventListener(event, updateActivity);
    });
});

// Cleanup on unmount
onUnmounted(() => {
    pauseProgressPolling();
    const events = ['click', 'scroll', 'keydown', 'mousemove'];
    events.forEach((event) => {
        document.removeEventListener(event, updateActivity);
    });
});
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

.prose h1,
.prose h2,
.prose h3,
.prose h4 {
    color: #111827;
    font-weight: 600;
    margin-bottom: 1rem;
    margin-top: 2rem;
}

.prose p {
    margin-bottom: 1rem;
    line-height: 1.625;
}

.prose ul,
.prose ol {
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
    box-shadow: 0 4px 24px 0 rgba(0, 0, 0, 0.08);
    background: #000;
}

/* Prose overrides for lesson content */
.prose-lg {
    font-size: 1.15rem;
    line-height: 1.8;
}
.prose h1,
.prose h2,
.prose h3 {
    color: #5f5fcd;
    font-weight: 700;
}
.prose strong {
    color: #222;
}

/* Accordion animation */
.module-scale-rotate-enter-active,
.module-scale-rotate-leave-active {
    transition:
        opacity 0.4s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
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
