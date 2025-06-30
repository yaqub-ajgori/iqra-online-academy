<template>
  <div class="group relative bg-white rounded-xl shadow-islamic hover:shadow-islamic-lg transition-all duration-300 transform hover:-trangray-y-2 overflow-hidden border border-gray-100">
    <!-- Course Image -->
    <div class="relative overflow-hidden">
      <img 
        :src="course.image || 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=250&fit=crop'" 
        :alt="course.title"
        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
      />
      
      <!-- Image Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
      
      <!-- Course Level Badge -->
      <div class="absolute top-3 left-3">
        <span :class="levelBadgeClasses">
          {{ getLevelText(course.level) }}
        </span>
      </div>

      <!-- Price Badge -->
      <div class="absolute top-3 right-3">
        <span v-if="course.price === 0" class="bg-[#2d5a27] text-white px-2 py-1 rounded-full text-xs font-medium">
          ফ্রি
        </span>
        <span v-else class="bg-white/90 backdrop-blur-sm text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
          ৳{{ formatPrice(course.price) }}
        </span>
      </div>

      <!-- Favorite Button -->
      <button 
        v-if="showFavorite"
        @click.prevent="toggleFavorite"
        class="absolute bottom-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white transition-colors duration-200"
      >
        <HeartIcon 
          :class="[
            'w-4 h-4 transition-colors duration-200',
            isFavorite ? 'text-red-500 fill-red-500' : 'text-gray-600'
          ]"
        />
      </button>
    </div>

    <!-- Course Content -->
    <div class="p-6">
      <!-- Category & Duration -->
      <div class="flex items-center justify-between mb-3">
        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 text-[#5f5fcd] border border-[#5f5fcd]/20">
          {{ course.category }}
        </span>
        <div class="flex items-center text-gray-500 text-xs">
          <ClockIcon class="w-3 h-3 mr-1" />
          {{ course.duration || 'স্ব-নির্ধারিত' }}
        </div>
      </div>

      <!-- Course Title -->
      <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-[#5f5fcd] transition-colors duration-200 line-clamp-2">
        {{ course.title }}
      </h3>

      <!-- Course Description -->
      <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
        {{ course.description }}
      </p>

      <!-- Progress Bar (if enrolled) -->
      <div v-if="course.enrolled && course.progress !== undefined" class="mb-4">
        <div class="flex items-center justify-between mb-1">
          <span class="text-xs text-gray-600">সম্পন্ন হয়েছে</span>
          <span class="text-xs font-medium text-[#5f5fcd]">{{ course.progress }}%</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-2">
          <div 
            class="bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] h-2 rounded-full transition-all duration-300"
            :style="{ width: `${course.progress}%` }"
          ></div>
        </div>
      </div>

      <!-- Course Meta -->
      <div class="flex items-center justify-between mb-4 text-sm text-gray-500">
        <!-- Student Count -->
        <div class="flex items-center">
          <UsersIcon class="w-4 h-4 mr-1" />
          <span>{{ formatNumber(course.students_count || 0) }} শিক্ষার্থী</span>
        </div>

        <!-- Rating -->
        <div v-if="course.rating" class="flex items-center">
          <StarIcon class="w-4 h-4 mr-1 text-yellow-400 fill-yellow-400" />
          <span>{{ course.rating.toFixed(1) }}</span>
        </div>
      </div>

      <!-- Instructor -->
      <div v-if="course.instructor" class="flex items-center mb-4">
        <img 
          :src="course.instructor.avatar || 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'" 
          :alt="course.instructor.name"
          class="w-8 h-8 rounded-full mr-2 border-2 border-[#d4a574]"
        />
        <div>
          <p class="text-sm font-medium text-gray-700">{{ course.instructor.name }}</p>
          <p class="text-xs text-gray-500">ইন্সট্রাক্টর</p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="space-y-2">
        <PrimaryButton 
          v-if="!course.enrolled"
          :href="courseUrl"
          tag="a"
          variant="primary"
          size="sm"
          full-width
          :icon="BookOpenIcon"
          @click="handleEnrollClick"
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
        >
          শেখা চালিয়ে যান
        </PrimaryButton>


      </div>
    </div>

    <!-- Islamic Decorative Corner -->
    <div class="absolute top-0 right-0 w-16 h-16 overflow-hidden">
      <div class="absolute top-2 right-2 w-8 h-8 bg-gradient-to-br from-[#d4a574]/20 to-transparent transform rotate-45 rounded-sm"></div>
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
  PlayIcon
} from 'lucide-vue-next'
import PrimaryButton from './PrimaryButton.vue'

interface Course {
  id: number
  title: string
  description: string
  image?: string
  category: string
  level: 'beginner' | 'intermediate' | 'advanced'
  price: number
  duration?: string
  students_count?: number
  rating?: number
  enrolled?: boolean
  progress?: number
  has_preview?: boolean
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
  const baseClasses = 'px-2 py-1 rounded-full text-xs font-medium'
  
  const levelClasses = {
    beginner: 'bg-green-100 text-green-800 border border-green-200',
    intermediate: 'bg-yellow-100 text-yellow-800 border border-yellow-200',
    advanced: 'bg-red-100 text-red-800 border border-red-200'
  }

  return `${baseClasses} ${levelClasses[props.course.level]}`
})

const courseUrl = computed(() => `/courses/${props.course.id}`)
const continueUrl = computed(() => `/courses/${props.course.id}/learn`)

const getLevelText = (level: string) => {
  const levelTexts = {
    beginner: 'নতুন',
    intermediate: 'মধ্যম',
    advanced: 'উন্নত'
  }
  return levelTexts[level as keyof typeof levelTexts] || level
}

const formatPrice = (price: number) => {
  return new Intl.NumberFormat('bn-BD').format(price)
}

const formatNumber = (num: number) => {
  if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'k'
  }
  return num.toString()
}

const handleEnrollClick = (event: Event) => {
  emit('enroll', props.course)
}

const toggleFavorite = () => {
  isFavorite.value = !isFavorite.value
  emit('favorite', props.course, isFavorite.value)
}


</script>

<style scoped>
/* Line clamp utilities */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Islamic-inspired hover effects */
.group:hover .islamic-pattern {
  opacity: 1;
}

/* Enhanced card shadow on hover */
@keyframes cardFloat {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-4px);
  }
}

.group:hover {
  animation: cardFloat 2s ease-in-out infinite;
}

/* Progress bar animation */
@keyframes progressFill {
  from {
    width: 0%;
  }
  to {
    width: var(--progress-width);
  }
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
</style> 
