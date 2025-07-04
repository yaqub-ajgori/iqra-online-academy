<template>
  <div class="group relative bg-white rounded-2xl shadow-islamic hover:shadow-islamic-lg transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100 hover:border-[#5f5fcd]/20">
    <!-- Course Image with Enhanced Placeholder -->
    <div class="relative overflow-hidden">
      <img 
        v-if="isValidImage(course.image)"
        :src="course.image" 
        :alt="course.title"
        class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-700"
      />
      <CoursePlaceholder 
        v-else
        class="w-full h-48" 
        :text="course.category"
      />
      
      <!-- Badges Container -->
      <div class="absolute top-3 left-3 flex flex-col space-y-2">
        <!-- Featured Badge -->
        <span v-if="course.is_featured" class="px-3 py-1 rounded-full text-xs font-semibold shadow-lg bg-gradient-to-r from-yellow-400 to-amber-500 text-white border border-yellow-300">
          ফিচার্ড
        </span>
        <!-- Free Badge -->
        <span v-if="course.is_free" class="px-3 py-1 rounded-full text-xs font-semibold shadow-lg bg-gradient-to-r from-green-500 to-emerald-600 text-white border border-green-400">
          ফ্রি
        </span>
      </div>

      <!-- Enhanced Image Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

      <!-- Course Progress Overlay (if enrolled) -->
      <div v-if="course.enrolled && course.progress !== undefined" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
        <div class="h-1 bg-white rounded-full overflow-hidden">
          <div class="h-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]" :style="{ width: course.progress + '%' }"></div>
        </div>
        <p class="text-white text-xs mt-1">{{ course.progress }}% পূর্ণ হয়েছে</p>
      </div>
    </div>

    <!-- Enhanced Course Content -->
    <div class="p-6">
      <!-- Category & Duration with Enhanced Design -->
      <div class="flex items-center justify-between mb-4">
        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-[#5f5fcd]/15 to-[#2d5a27]/15 text-[#5f5fcd] border border-[#5f5fcd]/25 hover:from-[#5f5fcd]/20 hover:to-[#2d5a27]/20 transition-all duration-200">
          {{ course.category }}
        </span>
        <div class="flex items-center text-gray-500 text-xs bg-gray-50 px-2 py-1 rounded-full">
          <ClockIcon class="w-3 h-3 mr-1" />
          {{ course.duration || 'স্ব-নির্ধারিত' }}
        </div>
      </div>

      <!-- Enhanced Course Title -->
      <h3 class="text-lg font-bold text-gray-900 mb-3 group-hover:text-[#5f5fcd] transition-colors duration-200 line-clamp-2 leading-tight">
        {{ course.title }}
      </h3>

      <!-- Enhanced Course Description -->
      <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed" v-html="course.description"></p>

      <!-- Enhanced Course Meta -->
      <div class="flex items-center justify-between mb-4 text-sm">
        <!-- Student Count with Enhanced Design -->
        <div class="flex items-center bg-gray-50 px-3 py-1 rounded-full">
          <UsersIcon class="w-4 h-4 mr-1 text-[#5f5fcd]" />
          <span class="text-gray-700 font-medium">{{ formatNumber(course.students_count || 0) }} শিক্ষার্থী</span>
        </div>

        <!-- Enhanced Rating -->
        <div v-if="course.rating" class="flex items-center bg-yellow-50 px-3 py-1 rounded-full">
          <StarIcon class="w-4 h-4 mr-1 text-yellow-500 fill-yellow-500" />
          <span class="text-gray-700 font-medium">{{ Number(course.rating || 0).toFixed(1) }}</span>
        </div>
      </div>

      <!-- Enhanced Instructor Section -->
      <div v-if="course.instructor" class="flex items-center mb-5 p-3 bg-gradient-to-r from-[#5f5fcd]/5 to-[#2d5a27]/5 rounded-xl border border-[#5f5fcd]/10">
        <div class="relative">
          <img 
            v-if="isValidImage(course.instructor.avatar)"
            :src="course.instructor.avatar" 
            :alt="course.instructor.name"
            class="w-10 h-10 rounded-full border-2 border-[#d4a574] shadow-sm"
          />
          <div 
            v-else
            class="w-10 h-10 rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] flex items-center justify-center border-2 border-[#d4a574] shadow-sm"
          >
            <span class="text-white font-semibold text-sm">
              {{ getInitials(course.instructor.name) }}
            </span>
          </div>
        </div>
        <div class="ml-3">
          <p class="text-sm font-semibold text-gray-800">{{ course.instructor.name }}</p>
          <p class="text-xs text-gray-500">ইন্সট্রাক্টর</p>
        </div>
      </div>

      <!-- Enhanced Action Buttons -->
      <div class="space-y-3">
        <PrimaryButton 
          :href="courseUrl"
          tag="a"
          variant="primary"
          size="sm"
          full-width
          :icon="BookOpenIcon"
          class="hover:scale-105 transition-transform duration-200"
        >
          {{ course.price === 0 ? 'ফ্রি এনরোল' : 'এনরোল করুন' }}
        </PrimaryButton>

        <PrimaryButton 
          v-if="course.enrolled"
          :href="continueUrl"
          tag="a"
          variant="secondary"
          size="sm"
          full-width
          :icon="PlayIcon"
          class="hover:scale-105 transition-transform duration-200"
        >
          শেখা চালিয়ে যান
        </PrimaryButton>
      </div>
    </div>

    <!-- Enhanced Islamic Decorative Corner -->
    <div class="absolute top-0 right-0 w-20 h-20 overflow-hidden">
      <div class="absolute top-3 right-3 w-10 h-10 bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/20 transform rotate-45 rounded-lg group-hover:scale-110 transition-transform duration-300"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { 
  ClockIcon, 
  UsersIcon, 
  StarIcon, 
  BookOpenIcon,
  PlayIcon,
  CheckIcon
} from 'lucide-vue-next'
import PrimaryButton from './PrimaryButton.vue'
import CoursePlaceholder from './CoursePlaceholder.vue'

interface Course {
  id: number
  slug: string
  title: string
  description: string
  image?: string
  category: string
  level: string
  price: number
  duration?: string
  students_count?: number
  rating?: number
  enrolled?: boolean
  progress?: number
  isNew?: boolean
  is_featured?: boolean
  is_free?: boolean
  instructor?: {
    name: string
    avatar?: string
  }
}

interface Props {
  course: Course
}

const props = defineProps<Props>()

const courseUrl = computed(() => `/courses/${props.course.slug}`)
const continueUrl = computed(() => `/courses/${props.course.slug}/learn`)

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('bn-BD').format(price)
}

const formatNumber = (num: number) => {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M'
  }
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'k'
  }
  return num.toString()
}

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

// Utility function to check for a valid image
const isValidImage = (src: string | undefined | null): boolean => {
  return !!src && typeof src === 'string' && src.trim() !== '' && !src.includes('undefined')
}
</script>

<style scoped>
/* Enhanced line clamp utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Enhanced Islamic-inspired hover effects */
.group:hover .islamic-pattern {
  opacity: 1;
  transform: scale(1.05);
}

/* Enhanced card float animation */
@keyframes cardFloat {
  0%, 100% {
    transform: translateY(0px) rotate(0deg);
  }
  50% {
    transform: translateY(-6px) rotate(0.5deg);
  }
}

.group:hover {
  animation: cardFloat 3s ease-in-out infinite;
}



/* Enhanced shadow animation */
@keyframes shadowPulse {
  0%, 100% {
    box-shadow: 0 4px 12px rgba(95, 95, 205, 0.1), 0 2px 4px rgba(45, 90, 39, 0.05);
  }
  50% {
    box-shadow: 0 8px 25px rgba(95, 95, 205, 0.2), 0 4px 8px rgba(45, 90, 39, 0.1);
  }
}

.group:hover {
  animation: shadowPulse 2s ease-in-out infinite;
}

/* Enhanced focus states for accessibility */
.group:focus-within {
  outline: 2px solid #5f5fcd;
  outline-offset: 2px;
  border-radius: 16px;
}

/* Custom scrollbar for long content */
::-webkit-scrollbar {
  width: 4px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #5f5fcd;
  border-radius: 2px;
}

/* Enhanced button hover effects */
.group:hover button {
  transform: scale(1.05);
}

/* Enhanced image hover effects */
.group:hover img {
  filter: brightness(1.1) contrast(1.1);
}

/* Enhanced placeholder animation */
.group:hover .course-placeholder {
  transform: scale(1.02);
  transition: transform 0.3s ease;
}
</style> 

