<template>
    <div class="course-progress-container">
        <!-- Progress Header -->
        <div class="mb-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                    <TrendingUpIcon class="h-5 w-5 text-white" />
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">অগ্রগতি</h3>
                    <p class="text-sm text-gray-500">{{ course.title }}</p>
                </div>
            </div>

            <div class="text-right">
                <div class="text-2xl font-bold text-[#5f5fcd]">{{ Math.round(progress.percentage) }}%</div>
                <div class="text-xs text-gray-500">সম্পন্ন</div>
            </div>
        </div>

        <!-- Main Progress Bar -->
        <div class="relative mb-6">
            <div class="h-4 w-full overflow-hidden rounded-full bg-gray-200">
                <!-- Background Pattern -->
                <div class="pattern-dots absolute inset-0 opacity-20"></div>

                <!-- Progress Fill -->
                <div
                    class="relative h-full overflow-hidden rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] transition-all duration-1000 ease-out"
                    :style="{ width: `${progress.percentage}%` }"
                >
                    <!-- Animated Shine Effect -->
                    <div class="animate-shine absolute inset-0 h-full w-1/4 bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>
                </div>

                <!-- Progress Milestones -->
                <div class="absolute top-0 left-0 flex h-full w-full items-center">
                    <div
                        v-for="milestone in milestones"
                        :key="milestone.percentage"
                        class="-trangray-x-1 absolute -mt-1 h-6 w-2 transform"
                        :style="{ left: `${milestone.percentage}%` }"
                    >
                        <div
                            :class="[
                                'h-6 w-2 rounded-full border-2 border-white transition-all duration-300',
                                progress.percentage >= milestone.percentage ? 'bg-[#d4a574] shadow-lg' : 'bg-gray-300',
                            ]"
                        ></div>
                    </div>
                </div>
            </div>

            <!-- Progress Labels -->
            <div class="mt-2 flex justify-between text-xs text-gray-500">
                <span>শুরু</span>
                <span>৫০%</span>
                <span>সম্পন্ন</span>
            </div>
        </div>

        <!-- Detailed Progress Stats -->
        <div class="mb-6 grid grid-cols-2 gap-4 lg:grid-cols-4">
            <!-- Completed Lessons -->
            <div class="rounded-xl bg-gray-50 p-4 text-center">
                <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#2d5a27] to-[#2d5a27]/80">
                    <CheckCircleIcon class="h-4 w-4 text-white" />
                </div>
                <div class="text-lg font-bold text-gray-900">{{ progress.completed_lessons }}</div>
                <div class="text-xs text-gray-500">সম্পন্ন পাঠ</div>
            </div>

            <!-- Total Lessons -->
            <div class="rounded-xl bg-gray-50 p-4 text-center">
                <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd] to-[#5f5fcd]/80">
                    <BookOpenIcon class="h-4 w-4 text-white" />
                </div>
                <div class="text-lg font-bold text-gray-900">{{ progress.total_lessons }}</div>
                <div class="text-xs text-gray-500">মোট পাঠ</div>
            </div>

            <!-- Time Spent -->
            <div class="rounded-xl bg-gray-50 p-4 text-center">
                <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#d4a574] to-[#d4a574]/80">
                    <ClockIcon class="h-4 w-4 text-white" />
                </div>
                <div class="text-lg font-bold text-gray-900">{{ formatTime(progress.time_spent) }}</div>
                <div class="text-xs text-gray-500">ব্যয়িত সময়</div>
            </div>

            <!-- Remaining Time -->
            <div class="rounded-xl bg-gray-50 p-4 text-center">
                <div class="mx-auto mb-2 flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-[#5f5fcd]/60 to-[#2d5a27]/60">
                    <TargetIcon class="h-4 w-4 text-white" />
                </div>
                <div class="text-lg font-bold text-gray-900">{{ formatTime(progress.estimated_remaining) }}</div>
                <div class="text-xs text-gray-500">আনুমানিক বাকি</div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div v-if="showActivity && recentActivity.length > 0" class="mb-6">
            <h4 class="mb-3 flex items-center text-sm font-semibold text-gray-700">
                <ActivityIcon class="mr-2 h-4 w-4 text-[#5f5fcd]" />
                সাম্প্রতিক কার্যক্রম
            </h4>
            <div class="space-y-2">
                <div v-for="activity in recentActivity.slice(0, 3)" :key="activity.id" class="flex items-center space-x-3 rounded-lg bg-gray-50 p-3">
                    <div :class="['h-2 w-2 rounded-full', activity.type === 'completed' ? 'bg-[#2d5a27]' : 'bg-[#d4a574]']"></div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-700">{{ activity.description }}</p>
                        <p class="text-xs text-gray-500">{{ formatDate(activity.created_at) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Achievement Badges -->
        <div v-if="achievements.length > 0" class="mb-6">
            <h4 class="mb-3 flex items-center text-sm font-semibold text-gray-700">
                <AwardIcon class="mr-2 h-4 w-4 text-[#d4a574]" />
                অর্জিত ব্যাজ
            </h4>
            <div class="flex flex-wrap gap-2">
                <div
                    v-for="achievement in achievements"
                    :key="achievement.id"
                    class="inline-flex items-center rounded-full border border-[#5f5fcd]/20 bg-gradient-to-r from-[#d4a574]/10 to-[#5f5fcd]/10 px-3 py-2 text-xs font-medium text-[#5f5fcd]"
                >
                    <AwardIcon class="mr-1 h-3 w-3" />
                    {{ achievement.name }}
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div v-if="showActions" class="flex flex-col gap-3 sm:flex-row">
            <PrimaryButton :href="continueUrl" tag="a" variant="primary" size="md" class="flex-1" :icon="PlayIcon"> পরবর্তী পাঠ </PrimaryButton>

            <PrimaryButton
                v-if="progress.percentage >= 80"
                :href="certificateUrl"
                tag="a"
                variant="accent"
                size="md"
                class="flex-1"
                :icon="AwardIcon"
            >
                সার্টিফিকেট পান
            </PrimaryButton>
        </div>

        <!-- Islamic Divider -->
        <div class="divider-islamic mx-auto mt-8 w-24"></div>
    </div>
</template>

<script setup lang="ts">
import { ActivityIcon, AwardIcon, BookOpenIcon, CheckCircleIcon, ClockIcon, PlayIcon, TargetIcon, TrendingUpIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import PrimaryButton from './PrimaryButton.vue';

interface Course {
    id: number;
    title: string;
    slug: string;
}

interface Progress {
    percentage: number;
    completed_lessons: number;
    total_lessons: number;
    time_spent: number; // in minutes
    estimated_remaining: number; // in minutes
}

interface Activity {
    id: number;
    type: 'completed' | 'started' | 'watched';
    description: string;
    created_at: string;
}

interface Achievement {
    id: number;
    name: string;
    icon?: string;
}

interface Props {
    course: Course;
    progress: Progress;
    recentActivity?: Activity[];
    achievements?: Achievement[];
    showActivity?: boolean;
    showActions?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    recentActivity: () => [],
    achievements: () => [],
    showActivity: true,
    showActions: true,
});

const milestones = [{ percentage: 25 }, { percentage: 50 }, { percentage: 75 }];

const continueUrl = computed(() => `/learn/${props.course.slug}`);
const certificateUrl = computed(() => `/courses/${props.course.slug}/certificate`);

const formatTime = (minutes: number): string => {
    if (minutes < 60) {
        return `${minutes}মি`;
    }
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours}ঘ ${remainingMinutes}মি` : `${hours}ঘ`;
};

const formatDate = (dateString: string): string => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60));

    if (diffInHours < 1) {
        return 'এইমাত্র';
    } else if (diffInHours < 24) {
        return `${diffInHours} ঘন্টা আগে`;
    } else {
        const diffInDays = Math.floor(diffInHours / 24);
        return `${diffInDays} দিন আগে`;
    }
};
</script>

<style scoped>
/* Shine animation for progress bar */
@keyframes shine {
    0% {
        transform: translateX(-100%);
    }
    100% {
        transform: translateX(400%);
    }
}

.animate-shine {
    animation: shine 2s infinite;
}

/* Enhanced progress bar animations */
@keyframes progressFill {
    from {
        width: 0%;
    }
    to {
        width: var(--target-width);
    }
}

/* Islamic geometric pattern for background */
.pattern-dots {
    background-image: radial-gradient(circle, currentColor 1px, transparent 1px);
    background-size: 8px 8px;
}

/* Milestone achievement animation */
@keyframes milestoneAchieved {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.3);
    }
    100% {
        transform: scale(1);
    }
}

/* Smooth transitions for all elements */
.course-progress-container * {
    transition: all 0.3s ease;
}

/* Islamic-inspired glow effect */
@keyframes islamicGlow {
    0%,
    100% {
        box-shadow: 0 0 5px rgba(95, 95, 205, 0.1);
    }
    50% {
        box-shadow: 0 0 15px rgba(95, 95, 205, 0.3);
    }
}

.course-progress-container:hover {
    animation: islamicGlow 3s ease-in-out infinite;
}
</style>
