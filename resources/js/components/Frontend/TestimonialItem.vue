<template>
  <div class="group relative bg-white rounded-2xl p-8 shadow-islamic hover:shadow-islamic-lg transition-all duration-300 border border-gray-100 overflow-hidden">
    <!-- Quote Icon -->
    <div class="relative mb-6">
      <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-full flex items-center justify-center">
        <QuoteIcon class="w-6 h-6 text-[#5f5fcd]" />
      </div>
    </div>

    <!-- Testimonial Content -->
    <div class="relative mb-6">
      <p class="text-gray-700 leading-relaxed text-lg italic">
        "{{ testimonial.content }}"
      </p>
    </div>

    <!-- Rating Stars -->
    <div class="flex items-center mb-6 space-x-1">
      <StarIcon 
        v-for="star in 5" 
        :key="star"
        :class="[
          'w-5 h-5 transition-colors duration-200',
          star <= testimonial.rating 
            ? 'text-yellow-400 fill-yellow-400' 
            : 'text-gray-300'
        ]"
      />
      <span class="ml-2 text-sm text-gray-500">({{ testimonial.rating_bn || testimonial.rating }}/৫)</span>
    </div>

    <!-- Student Information -->
    <div class="flex items-center space-x-4">
      <!-- Avatar -->
      <div class="relative">
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 flex items-center justify-center border-3 border-[#d4a574]">
            <component v-if="testimonial.student.avatar" :is="testimonial.student.avatar" class="w-8 h-8 text-[#5f5fcd]" />
            <span v-else class="text-white font-semibold text-lg">
                {{ getInitials(testimonial.student.name) }}
            </span>
        </div>
        
        <!-- Verified Badge -->
        <div v-if="testimonial.verified" class="absolute -top-1 -right-1 w-5 h-5 bg-[#2d5a27] rounded-full flex items-center justify-center">
          <CheckIcon class="w-3 h-3 text-white" />
        </div>
      </div>

      <!-- Student Details -->
      <div class="flex-1">
        <h4 class="font-semibold text-gray-900 group-hover:text-[#5f5fcd] transition-colors duration-200">
          {{ testimonial.student.name }}
        </h4>
        <p class="text-sm text-gray-500">
          {{ testimonial.student.title || 'শিক্ষার্থী' }}
        </p>
        <div v-if="testimonial.student.location" class="flex items-center text-xs text-gray-400 mt-1">
          <MapPinIcon class="w-3 h-3 mr-1" />
          {{ testimonial.student.location }}
        </div>
      </div>

      <!-- Date -->
      <div class="text-right">
        <time class="text-xs text-gray-400" :datetime="testimonial.created_at">
          {{ formatDate(testimonial.created_at) }}
        </time>
      </div>
    </div>

    <!-- Islamic Decorative Element -->
    <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
  </div>
</template>

<script setup lang="ts">
import { QuoteIcon, StarIcon, BookOpenIcon, CheckIcon, MapPinIcon } from 'lucide-vue-next'
import type { Component } from 'vue'

interface Student {
  name: string
  avatar?: Component
  title?: string
  location?: string
}

interface Testimonial {
  id: number
  content: string
  rating: number
  rating_bn?: string
  student: Student
  verified: boolean
  created_at: string
}

interface Props {
  testimonial: Testimonial
  variant?: 'default' | 'compact' | 'featured'
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default'
})

const getInitials = (name: string): string => {
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const formatDate = (dateString: string): string => {
  const date = new Date(dateString)
  const months = [
    'জানুয়ারি', 'ফেব্রুয়ারি', 'মার্চ', 'এপ্রিল', 'মে', 'জুন',
    'জুলাই', 'আগস্ট', 'সেপ্টেম্বর', 'অক্টোবর', 'নভেম্বর', 'ডিসেম্বর'
  ]
  
  const day = date.getDate()
  const month = months[date.getMonth()]
  const year = date.getFullYear()
  
  return `${day} ${month}, ${year}`
}
</script>

<style scoped>
/* Islamic-inspired hover effects */
@keyframes testimonialFloat {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-5px);
  }
}

.group:hover {
  animation: testimonialFloat 3s ease-in-out infinite;
}

/* Quote styling enhancement */
.group:hover .quote-decoration {
  opacity: 1;
  transform: scale(1.1);
}

/* Gradient text effect for featured testimonials */
.text-gradient-testimonial {
  background: linear-gradient(135deg, #5f5fcd 0%, #2d5a27 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}

/* Islamic decorative border animation */
@keyframes borderGlow {
  0%, 100% {
    box-shadow: 0 0 5px rgba(95, 95, 205, 0.1);
  }
  50% {
    box-shadow: 0 0 20px rgba(95, 95, 205, 0.2);
  }
}

.group:hover {
  animation: borderGlow 2s ease-in-out infinite;
}
</style> 
