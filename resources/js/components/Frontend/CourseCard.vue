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
      
      <!-- Enhanced Image Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      
      <!-- Course Level Badge with Enhanced Design -->
      <div class="absolute top-3 left-3">
        <span :class="levelBadgeClasses" class="shadow-lg">
          {{ getLevelText(course.level) }}
        </span>
      </div>

      <!-- Enhanced Price Badge -->
      <div class="absolute top-3 right-3">
        <span v-if="course.price === 0" class="bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
          ফ্রি
        </span>
        <span v-else class="bg-white/95 backdrop-blur-sm text-gray-800 px-3 py-1 rounded-full text-xs font-semibold shadow-lg border border-gray-200">
          ৳{{ formatPrice(course.price) }}
        </span>
      </div>

      <!-- Enhanced Favorite Button -->
      <button 
        v-if="showFavorite"
        @click.prevent="toggleFavorite"
        class="absolute bottom-3 right-3 w-10 h-10 bg-white/95 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white hover:scale-110 transition-all duration-200 shadow-lg"
        :aria-label="isFavorite ? 'Remove from favorites' : 'Add to favorites'"
      >
        <HeartIcon 
          :class="[
            'w-5 h-5 transition-all duration-200',
            isFavorite ? 'text-red-500 fill-red-500 scale-110' : 'text-gray-600 hover:text-red-500'
          ]"
        />
      </button>

      <!-- Course Progress Overlay (if enrolled) -->
      <div v-if="course.enrolled && course.progress !== undefined" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-3">
        <div class="flex items-center justify-between text-white text-xs mb-1">
          <span>সম্পন্ন হয়েছে</span>
          <span class="font-semibold">{{ course.progress }}%</span>
        </div>
        <div class="w-full bg-white/20 rounded-full h-1.5">
          <div 
            class="bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] h-1.5 rounded-full transition-all duration-500"
            :style="{ width: `${course.progress}%` }"
          ></div>
        </div>
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
      <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
        {{ course.description }}
      </p>

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
          <!-- Verified Badge for Instructor -->
          <div class="absolute -top-1 -right-1 w-4 h-4 bg-[#2d5a27] rounded-full flex items-center justify-center">
            <CheckIcon class="w-2 h-2 text-white" />
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
          v-if="!course.enrolled"
          :href="courseUrl"
          tag="a"
          variant="primary"
          size="sm"
          full-width
          :icon="BookOpenIcon"
          @click="handleEnrollClick"
          class="hover:scale-105 transition-transform duration-200"
        >
          {{ course.price === 0 ? 'ফ্রি এনরোল' : 'এনরোল করুন' }}
        </PrimaryButton>

        <PrimaryButton 
          v-else
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

    <!-- New Course Status Badge -->
    <div v-if="course.isNew" class="absolute top-0 left-0 bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] text-white px-3 py-1 text-xs font-semibold rounded-br-lg shadow-lg">
      নতুন
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { 
  HeartIcon, 
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
  instructor?: {
    name: string
    avatar?: string
  }
}

interface Props {
  course: Course
  showFavorite?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  showFavorite: true
})

const emit = defineEmits<{
  enroll: [course: Course]
  favorite: [course: Course, isFavorite: boolean]
}>()

const isFavorite = ref(false)

const levelBadgeClasses = computed(() => {
  const baseClasses = 'px-3 py-1 rounded-full text-xs font-semibold shadow-lg'
  
  const levelClasses = {
    beginner: 'bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300',
    intermediate: 'bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300',
    advanced: 'bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300'
  }

  return `${baseClasses} ${levelClasses[props.course.level]}`
})

const courseUrl = computed(() => `/courses/${props.course.slug}`)
const continueUrl = computed(() => `/courses/${props.course.slug}/learn`)

const getLevelText = (level: string) => {
  const levelTexts: Record<string, string> = {
    beginner: 'নতুন',
    intermediate: 'মধ্যম',
    advanced: 'উন্নত'
  }
  return levelTexts[level] || level
}

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

const handleEnrollClick = (event: Event) => {
  emit('enroll', props.course)
}

const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
  emit('favorite', props.course, isFavorite.value)
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

