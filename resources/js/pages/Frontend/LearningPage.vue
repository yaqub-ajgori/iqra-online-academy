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

                        <!-- Learning Sequence -->
                        <div v-if="!sidebarCollapsed" class="flex-1 overflow-y-auto py-2 pr-1">
                            <!-- Group by modules -->
                            <div v-for="moduleGroup in groupedLearningItems" :key="moduleGroup.title"
                                class="mb-2 overflow-hidden rounded-xl border border-gray-100 bg-white shadow-sm">
                                
                                <!-- Module Header -->
                                <button
                                    @click="toggleModule(moduleGroup.title)"
                                    class="group flex w-full items-center justify-between bg-white px-4 py-3 text-left transition-colors hover:bg-[#f5f6fd]"
                                >
                                    <div>
                                        <h3 class="text-base font-medium text-gray-900 group-hover:text-[#5f5fcd]">{{ moduleGroup.title }}</h3>
                                        <p class="text-xs text-gray-500">{{ moduleGroup.items.length }} আইটেম</p>
                                    </div>
                                    <ChevronDownIcon
                                        :class="[
                                            'h-5 w-5 text-gray-400 transition-transform duration-300',
                                            expandedModules.includes(moduleGroup.title) ? 'rotate-180 text-[#5f5fcd]' : '',
                                        ]"
                                    />
                                </button>

                                <!-- Learning Items -->
                                <transition name="module-scale-rotate">
                                    <div v-show="expandedModules.includes(moduleGroup.title)" class="overflow-hidden bg-[#f5f6fd]">
                                        <button
                                            v-for="item in moduleGroup.items"
                                            :key="`${item.type}-${item.id}`"
                                            @click="!isItemLocked(item) && selectLearningItem(item)"
                                            :disabled="isItemLocked(item)"
                                            :class="[
                                                'flex w-full items-center space-x-3 border-l-4 px-6 py-3 text-left transition-colors',
                                                isItemLocked(item)
                                                    ? 'cursor-not-allowed border-gray-200 bg-gray-100 text-gray-400 opacity-60'
                                                    : currentItem?.type === item.type && currentItem?.id === item.id
                                                      ? 'border-[#5f5fcd] bg-[#e7eafd] font-semibold text-[#5f5fcd] hover:bg-[#e7eafd]'
                                                      : item.completed
                                                        ? 'border-[#2d5a27] bg-white text-[#2d5a27] hover:bg-[#e7eafd]'
                                                        : 'border-transparent bg-white text-gray-700 hover:bg-[#e7eafd]',
                                            ]"
                                            :title="isItemLocked(item) ? 'Complete previous items to unlock.' : item.title"
                                        >
                                            <div class="flex-shrink-0">
                                                <div
                                                    v-if="item.completed"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#2d5a27]"
                                                >
                                                    <CheckIcon class="h-4 w-4 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="currentItem?.type === item.type && currentItem?.id === item.id"
                                                    class="flex h-6 w-6 items-center justify-center rounded-full bg-[#5f5fcd]"
                                                >
                                                    <PlayIcon class="h-3 w-3 text-white" />
                                                </div>
                                                <div
                                                    v-else-if="isItemLocked(item)"
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
                                                <div class="flex items-center space-x-2">
                                                    <component :is="getItemTypeIcon(item)" class="h-4 w-4 flex-shrink-0" />
                                                    <p class="truncate">{{ item.title }}</p>
                                                </div>
                                                <div class="flex items-center space-x-2 text-xs text-gray-400">
                                                    <span>{{ getItemTypeLabel(item.type) }}</span>
                                                    <span v-if="item.duration">• {{ item.duration }}</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </transition>
                            </div>
                        </div>

                        <!-- Collapsed Sidebar Learning Items -->
                        <div v-else class="flex flex-1 flex-col items-center gap-2 overflow-y-auto py-2">
                            <div v-for="(item, index) in allLearningItems" :key="`${item.type}-${item.id}`" class="">
                                <button
                                    @click="selectLearningItem(item)"
                                    :class="[
                                        'flex h-10 w-10 items-center justify-center rounded-full transition-colors',
                                        currentItem?.type === item.type && currentItem?.id === item.id
                                            ? 'bg-[#5f5fcd] text-white shadow-lg'
                                            : item.completed
                                              ? 'bg-[#2d5a27] text-white'
                                              : 'bg-gray-200 text-gray-400 hover:bg-gray-300',
                                    ]"
                                    :title="item.title"
                                >
                                    <CheckIcon v-if="item.completed" class="h-4 w-4" />
                                    <PlayIcon v-else-if="currentItem?.type === item.type && currentItem?.id === item.id" class="h-3 w-3" />
                                    <component v-else :is="getItemTypeIcon(item)" class="h-4 w-4" />
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
                        <!-- No Lessons Available -->
                        <div v-else-if="!currentLesson && totalLessons === 0" class="flex flex-col items-center justify-center p-8 text-center">
                            <BookOpenIcon class="mb-4 h-16 w-16 text-gray-300" />
                            <h2 class="mb-2 text-xl font-semibold text-gray-700">কোনো পাঠ নেই</h2>
                            <p class="text-gray-500">এই কোর্সে এখনও কোনো পাঠ যোগ করা হয়নি</p>
                        </div>
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
                        <div v-else-if="currentItem?.type === 'quiz'" class="flex h-full w-full flex-col items-center justify-center bg-white p-4 sm:p-8">
                            <div class="max-w-2xl text-center">
                                <div class="mb-6 inline-flex h-16 w-16 items-center justify-center rounded-full bg-[#5f5fcd] text-white">
                                    <HelpCircleIcon class="h-8 w-8" />
                                </div>
                                <h1 class="mb-4 text-2xl font-bold text-gray-900">{{ currentItem.title }}</h1>
                                <p v-if="currentItem.description" class="mb-6 text-gray-600">{{ currentItem.description }}</p>
                                
                                <div class="mb-8 flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                                    <span class="flex items-center">
                                        <HelpCircleIcon class="mr-1 h-4 w-4" />
                                        {{ currentItem.total_questions }} প্রশ্ন
                                    </span>
                                    <span v-if="currentItem.time_limit_minutes" class="flex items-center">
                                        <ClockIcon class="mr-1 h-4 w-4" />
                                        {{ currentItem.time_limit_minutes }} মিনিট
                                    </span>
                                    <span class="flex items-center">
                                        <CheckCircleIcon class="mr-1 h-4 w-4" />
                                        পাশ: {{ currentItem.passing_score }}%
                                    </span>
                                </div>
                                
                                <button
                                    @click="takeQuiz"
                                    class="inline-flex transform items-center rounded-lg bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] px-6 py-3 font-medium text-white transition-all hover:scale-105 hover:shadow-lg focus:ring-2 focus:ring-[#5f5fcd] focus:ring-offset-2 focus:outline-none"
                                >
                                    <PlayIcon class="mr-2 h-4 w-4" />
                                    কুইজ শুরু করুন
                                </button>
                            </div>
                        </div>
                        <!-- PDF Content -->
                        <div
                            v-else-if="currentLesson?.type === 'pdf' && currentLesson.primary_file_url"
                            class="h-full w-full rounded-none border-none shadow-none"
                            style="height: 70vh"
                        >
                            <iframe
                                :src="currentLesson.primary_file_url"
                                class="h-full w-full rounded-xl"
                                frameborder="0"
                                title="Lesson PDF"
                            ></iframe>
                        </div>
                        <!-- Mixed Content -->
                        <div v-else-if="currentLesson?.type === 'mixed' || !currentLesson?.type" class="h-full w-full overflow-y-auto bg-white">
                            <!-- Video Section (if available) -->
                            <div v-if="currentLesson?.video_url" class="relative mb-6">
                                <div class="aspect-video w-full bg-black">
                                    <iframe
                                        :src="getEmbedUrl(currentLesson?.video_url || '')"
                                        class="h-full w-full"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen
                                        title="Lesson Video"
                                    ></iframe>
                                </div>
                            </div>

                            <!-- Content Section -->
                            <div class="px-4 sm:px-8">
                                <h1 class="mb-6 text-xl leading-tight font-extrabold text-gray-900 sm:text-3xl">
                                    {{ currentLesson?.title || '' }}
                                </h1>

                                <!-- Text Content -->
                                <div
                                    v-if="currentLesson?.content"
                                    class="prose prose-lg mb-8 max-w-none leading-relaxed text-gray-800"
                                    v-html="sanitizeHtml(currentLesson?.content || '')"
                                ></div>

                                <!-- Primary File Download -->
                                <div v-if="currentLesson?.primary_file_url" class="mb-6">
                                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-4">
                                        <h3 class="mb-3 font-semibold text-gray-900">প্রধান ফাইল</h3>
                                        <a
                                            :href="currentLesson?.primary_file_url"
                                            download
                                            class="inline-flex items-center space-x-2 text-[#5f5fcd] hover:text-[#4a4ab8]"
                                        >
                                            <DownloadIcon class="h-4 w-4" />
                                            <span>{{ getFileTypeDisplay(currentLesson?.primary_file_type) }} ডাউনলোড করুন</span>
                                        </a>
                                    </div>
                                </div>

                                <!-- PDF File Download -->
                                <div v-if="currentLesson?.type === 'pdf' && currentLesson?.file_path" class="mb-6">
                                    <h3 class="mb-3 font-semibold text-gray-900">পিডিএফ ফাইল</h3>
                                    <div class="rounded border border-gray-200 bg-gray-50 p-3">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ currentLesson.title }}.pdf</p>
                                                <p class="text-sm text-gray-500">পিডিএফ ডকুমেন্ট</p>
                                            </div>
                                            <a
                                                :href="currentLesson.file_url || `/storage/${currentLesson.file_path}`"
                                                download
                                                class="text-[#5f5fcd] hover:text-[#4a4ab8]"
                                            >
                                                <DownloadIcon class="h-4 w-4" />
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <!-- External Resources -->
                                <div v-if="currentLesson?.resources && currentLesson?.resources.length > 0" class="mb-6">
                                    <h3 class="mb-3 font-semibold text-gray-900">বাহ্যিক রিসোর্স</h3>
                                    <div class="space-y-2">
                                        <a
                                            v-for="resource in currentLesson?.resources"
                                            :key="resource.url"
                                            :href="resource.url"
                                            target="_blank"
                                            class="block rounded border border-gray-200 bg-gray-50 p-3 hover:bg-gray-100"
                                        >
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ resource.title }}</p>
                                                    <p class="text-sm text-gray-500">{{ resource.type }}</p>
                                                </div>
                                                <ExternalLinkIcon class="h-4 w-4 text-[#5f5fcd]" />
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Audio Content -->
                        <div
                            v-else-if="currentLesson?.type === 'audio' && currentLesson?.audio_url"
                            class="h-full w-full overflow-y-auto bg-white p-4 sm:p-8"
                        >
                            <h1 class="mb-6 text-lg leading-tight font-bold text-gray-900 sm:text-2xl">{{ currentLesson?.title }}</h1>
                            <audio controls class="mb-4 w-full">
                                <source :src="currentLesson?.audio_url" type="audio/mpeg" />
                                আপনার ব্রাউজার অডিও সাপোর্ট করে না।
                            </audio>
                            <div class="prose prose-base text-gray-700" v-html="sanitizeHtml(currentLesson?.content || '')"></div>
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
                            <!-- Item Info -->
                            <div class="text-sm text-gray-500">আইটেম {{ currentItemIndex + 1 }} / {{ allLearningItems.length }}</div>
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
    BookOpenIcon,
    CheckCircleIcon,
    CheckIcon,
    ChevronDownIcon,
    ChevronLeftIcon,
    ChevronRightIcon,
    ClockIcon,
    DownloadIcon,
    ExternalLinkIcon,
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
import { route } from 'ziggy-js';

// Type definitions
interface Lesson {
    id: number;
    order: number;
    title: string;
    duration: string;
    type: 'video' | 'text' | 'pdf';
    video_url?: string;
    content?: string;
    file_path?: string;
    file_url?: string;
    resources?: Array<{
        title: string;
        url: string;
        type: string;
    }>;
    pdf_url?: string; // Keep for backward compatibility
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
    modules?: Module[];
    learning_items?: any[];
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
const expandedModules = ref<string[]>([]);
const currentLesson = ref<Lesson | null>(null);
const currentItem = ref<any>(null);
const completedLessons = ref(0);


// Computed properties
const allLearningItems = computed(() => {
    return course.value?.learning_items || [];
});

const allLessons = computed(() => {
    return allLearningItems.value.filter((item: any) => item.type === 'lesson');
});

const totalLessons = computed(() => allLessons.value.length);

const progressPercentage = computed(() => {
    return totalLessons.value > 0 ? (completedLessons.value / totalLessons.value) * 100 : 0;
});

const currentItemIndex = computed(() => {
    if (!currentItem.value) return -1;
    return allLearningItems.value.findIndex((item: any) => 
        item.type === currentItem.value.type && item.id === currentItem.value.id
    );
});

const currentLessonIndex = computed(() => currentItemIndex.value);

const hasPreviousLesson = computed(() => currentItemIndex.value > 0);

const hasNextLesson = computed(() => currentItemIndex.value < allLearningItems.value.length - 1);

const groupedLearningItems = computed(() => {
    const groups: { [key: string]: any[] } = {};
    
    allLearningItems.value.forEach((item: any) => {
        const moduleTitle = item.module_title || 'Unknown Module';
        if (!groups[moduleTitle]) {
            groups[moduleTitle] = [];
        }
        groups[moduleTitle].push(item);
    });
    
    return Object.entries(groups).map(([title, items]) => ({
        title,
        items
    }));
});

// Helper: Get the index of an item in the learning sequence
function getItemIndex(item: any) {
    return allLearningItems.value.findIndex((i: any) => 
        i.type === item.type && i.id === item.id
    );
}

// Helper: Is an item locked?
function isItemLocked(item: any) {
    const idx = getItemIndex(item);
    if (idx === 0) return false; // First item always unlocked
    
    // All previous lessons must be completed (quizzes don't block progression)
    const previousItems = allLearningItems.value.slice(0, idx);
    return !previousItems.filter((i: any) => i.type === 'lesson').every((i: any) => i.completed);
}

// Helper: Type icon mapping
function getItemTypeIcon(item: any) {
    if (item.type === 'lesson') {
        return getLessonTypeIcon(item.lesson_type || 'mixed');
    } else if (item.type === 'quiz') {
        return HelpCircleIcon;
    }
    return FileTextIcon;
}

// Helper: Type label mapping
function getItemTypeLabel(type: string) {
    switch (type) {
        case 'lesson':
            return 'পাঠ';
        case 'quiz':
            return 'কুইজ';
        default:
            return 'অজানা';
    }
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

        // Set first learning item as current if none selected and no quiz results to handle
        const hasQuizResults = page.props.flash?.quiz_results || page.props.quiz_results;
        if (!currentItem.value && allLearningItems.value.length > 0 && !hasQuizResults) {
            selectLearningItem(allLearningItems.value[0]);
        }
    } catch (err) {
        // Handle course loading error silently
    }
};

const toggleSidebar = () => {
    sidebarCollapsed.value = !sidebarCollapsed.value;
};

const toggleModule = (moduleTitle: string) => {
    if (expandedModules.value.includes(moduleTitle)) {
        expandedModules.value = []; // Close all if clicking the open one
    } else {
        expandedModules.value = [moduleTitle]; // Only one open at a time
    }
};

const selectLearningItem = async (item: any) => {
    contentLoading.value = true;
    currentItem.value = item;
    
    // If it's a lesson, also set currentLesson for compatibility
    if (item.type === 'lesson') {
        currentLesson.value = {
            ...item,
            type: item.lesson_type || 'mixed'
        };
    }

    // Expand the module containing this item
    const moduleTitle = item.module_title;
    if (moduleTitle && !expandedModules.value.includes(moduleTitle)) {
        expandedModules.value = [moduleTitle];
    }

    // Simulate content loading
    setTimeout(() => {
        contentLoading.value = false;
    }, 500);
};

const selectLesson = async (lesson: Lesson) => {
    // Convert lesson to learning item format for compatibility
    const learningItem = {
        type: 'lesson',
        id: lesson.id,
        title: lesson.title,
        lesson_type: lesson.type,
        completed: lesson.completed,
        ...lesson
    };
    selectLearningItem(learningItem);
};

const goToPreviousLesson = () => {
    if (hasPreviousLesson.value) {
        const previousItem = allLearningItems.value[currentItemIndex.value - 1];
        selectLearningItem(previousItem);
    }
};

const goToNextLesson = () => {
    if (hasNextLesson.value) {
        const nextItem = allLearningItems.value[currentItemIndex.value + 1];
        selectLearningItem(nextItem);
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
        // Handle lesson completion error silently
    } finally {
        markingComplete.value = false;
    }
};

const goBackToDashboard = () => {
    router.visit(route('frontend.student.dashboard'));
};

const takeQuiz = () => {
    if (currentItem.value && currentItem.value.type === 'quiz') {
        router.visit(route('quiz.show', currentItem.value.id));
    }
};


const getLessonTypeIcon = (type: string) => {
    switch (type) {
        case 'video':
            return VideoIcon;
        case 'text':
            return FileTextIcon;
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


// File type display helper
const getFileTypeDisplay = (fileType: string | null): string => {
    if (!fileType) return 'ফাইল';

    switch (fileType.toLowerCase()) {
        case 'pdf':
            return 'পিডিএফ';
        case 'doc':
        case 'docx':
            return 'ওয়ার্ড ডকুমেন্ট';
        case 'ppt':
        case 'pptx':
            return 'প্রেজেন্টেশন';
        case 'jpg':
        case 'jpeg':
        case 'png':
        case 'gif':
            return 'ছবি';
        case 'mp3':
        case 'wav':
            return 'অডিও';
        case 'mp4':
        case 'avi':
            return 'ভিডিও';
        default:
            return 'ফাইল';
    }
};


// Initialize on mount
onMounted(async () => {
    await loadCourseData();
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
