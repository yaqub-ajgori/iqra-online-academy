<template>
  <div class="course-progress-container">
    <!-- Progress Header -->
    <div class="flex items-center justify-between mb-4">
      <div class="flex items-center space-x-3">
        <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center">
          <TrendingUpIcon class="w-5 h-5 text-white" />
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
      <div class="w-full bg-gray-200 rounded-full h-4 overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 pattern-dots opacity-20"></div>
        
        <!-- Progress Fill -->
        <div 
          class="h-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] rounded-full transition-all duration-1000 ease-out relative overflow-hidden"
          :style="{ width: `${progress.percentage}%` }"
        >
          <!-- Animated Shine Effect -->
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent w-1/4 h-full animate-shine"></div>
        </div>

        <!-- Progress Milestones -->
        <div class="absolute top-0 left-0 w-full h-full flex items-center">
          <div 
            v-for="milestone in milestones" 
            :key="milestone.percentage"
            class="absolute w-2 h-6 -mt-1 transform -trangray-x-1"
            :style="{ left: `${milestone.percentage}%` }"
          >
            <div 
              :class="[
                'w-2 h-6 rounded-full border-2 border-white transition-all duration-300',
                progress.percentage >= milestone.percentage 
                  ? 'bg-[#d4a574] shadow-lg' 
                  : 'bg-gray-300'
              ]"
            ></div>
          </div>
        </div>
      </div>

      <!-- Progress Labels -->
      <div class="flex justify-between mt-2 text-xs text-gray-500">
        <span>শুরু</span>
        <span>৫০%</span>
        <span>সম্পন্ন</span>
      </div>
    </div>

    <!-- Detailed Progress Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <!-- Completed Lessons -->
      <div class="bg-gray-50 rounded-xl p-4 text-center">
        <div class="w-8 h-8 bg-gradient-to-br from-[#2d5a27] to-[#2d5a27]/80 rounded-lg flex items-center justify-center mx-auto mb-2">
          <CheckCircleIcon class="w-4 h-4 text-white" />
        </div>
        <div class="text-lg font-bold text-gray-900">{{ progress.completed_lessons }}</div>
        <div class="text-xs text-gray-500">সম্পন্ন পাঠ</div>
      </div>

      <!-- Total Lessons -->
      <div class="bg-gray-50 rounded-xl p-4 text-center">
        <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd] to-[#5f5fcd]/80 rounded-lg flex items-center justify-center mx-auto mb-2">
          <BookOpenIcon class="w-4 h-4 text-white" />
        </div>
        <div class="text-lg font-bold text-gray-900">{{ progress.total_lessons }}</div>
        <div class="text-xs text-gray-500">মোট পাঠ</div>
      </div>

      <!-- Time Spent -->
      <div class="bg-gray-50 rounded-xl p-4 text-center">
        <div class="w-8 h-8 bg-gradient-to-br from-[#d4a574] to-[#d4a574]/80 rounded-lg flex items-center justify-center mx-auto mb-2">
          <ClockIcon class="w-4 h-4 text-white" />
        </div>
        <div class="text-lg font-bold text-gray-900">{{ formatTime(progress.time_spent) }}</div>
        <div class="text-xs text-gray-500">ব্যয়িত সময়</div>
      </div>

      <!-- Remaining Time -->
      <div class="bg-gray-50 rounded-xl p-4 text-center">
        <div class="w-8 h-8 bg-gradient-to-br from-[#5f5fcd]/60 to-[#2d5a27]/60 rounded-lg flex items-center justify-center mx-auto mb-2">
          <TargetIcon class="w-4 h-4 text-white" />
        </div>
        <div class="text-lg font-bold text-gray-900">{{ formatTime(progress.estimated_remaining) }}</div>
        <div class="text-xs text-gray-500">আনুমানিক বাকি</div>
      </div>
    </div>

    <!-- Recent Activity -->
    <div v-if="showActivity && recentActivity.length > 0" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
        <ActivityIcon class="w-4 h-4 mr-2 text-[#5f5fcd]" />
        সাম্প্রতিক কার্যক্রম
      </h4>
      <div class="space-y-2">
        <div 
          v-for="activity in recentActivity.slice(0, 3)" 
          :key="activity.id"
          class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg"
        >
          <div 
            :class="[
              'w-2 h-2 rounded-full',
              activity.type === 'completed' ? 'bg-[#2d5a27]' : 'bg-[#d4a574]'
            ]"
          ></div>
          <div class="flex-1">
            <p class="text-sm text-gray-700">{{ activity.description }}</p>
            <p class="text-xs text-gray-500">{{ formatDate(activity.created_at) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Achievement Badges -->
    <div v-if="achievements.length > 0" class="mb-6">
      <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
        <AwardIcon class="w-4 h-4 mr-2 text-[#d4a574]" />
        অর্জিত ব্যাজ
      </h4>
      <div class="flex flex-wrap gap-2">
        <div 
          v-for="achievement in achievements" 
          :key="achievement.id"
          class="inline-flex items-center px-3 py-2 bg-gradient-to-r from-[#d4a574]/10 to-[#5f5fcd]/10 rounded-full text-xs font-medium text-[#5f5fcd] border border-[#5f5fcd]/20"
        >
          <AwardIcon class="w-3 h-3 mr-1" />
          {{ achievement.name }}
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div v-if="showActions" class="flex flex-col sm:flex-row gap-3">
      <PrimaryButton 
        :href="continueUrl"
        tag="a"
        variant="primary"
        size="md"
        class="flex-1"
        :icon="PlayIcon"
      >
        পরবর্তী পাঠ
      </PrimaryButton>
      
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
    <div class="divider-islamic w-24 mx-auto mt-8"></div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {
  TrendingUpIcon,
  CheckCircleIcon,
  BookOpenIcon,
  ClockIcon,
  TargetIcon,
  ActivityIcon,
  AwardIcon,
  PlayIcon
} from 'lucide-vue-next'
import PrimaryButton from './PrimaryButton.vue'

interface Course {
  id: number
  title: string
  slug: string
}

interface Progress {
  percentage: number
  completed_lessons: number
  total_lessons: number
  time_spent: number // in minutes
  estimated_remaining: number // in minutes
}

interface Activity {
  id: number
  type: 'completed' | 'started' | 'watched'
  description: string
  created_at: string
}

interface Achievement {
  id: number
  name: string
  icon?: string
}

interface Props {
  course: Course
  progress: Progress
  recentActivity?: Activity[]
  achievements?: Achievement[]
  showActivity?: boolean
  showActions?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  recentActivity: () => [],
  achievements: () => [],
  showActivity: true,
  showActions: true
})

const milestones = [
  { percentage: 25 },
  { percentage: 50 },
  { percentage: 75 }
]

const continueUrl = computed(() => `/learn/${props.course.slug}`)
const certificateUrl = computed(() => `/courses/${props.course.slug}/certificate`)

const formatTime = (minutes: number): string => {
  if (minutes < 60) {
    return `${minutes}মি`
  }
  const hours = Math.floor(minutes / 60)
  const remainingMinutes = minutes % 60
  return remainingMinutes > 0 ? `${hours}ঘ ${remainingMinutes}মি` : `${hours}ঘ`
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  const now = new Date()
  const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60))
  
  if (diffInHours < 1) {
    return 'এইমাত্র'
  } else if (diffInHours < 24) {
    return `${diffInHours} ঘন্টা আগে`
  } else {
    const diffInDays = Math.floor(diffInHours / 24)
    return `${diffInDays} দিন আগে`
  }
}
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
  0%, 100% {
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
