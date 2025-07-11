<template>
  <article class="group relative bg-white rounded-2xl shadow-islamic hover:shadow-islamic-lg transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-neutral-100 hover:border-[#5f5fcd]/20">
    <!-- Featured Image -->
    <div class="relative overflow-hidden h-48">
      <img 
        v-if="post.featured_image"
        :src="post.featured_image_url || post.featured_image" 
        :alt="post.title"
        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
        loading="lazy"
      />
      <div 
        v-else
        class="w-full h-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 flex items-center justify-center"
      >
        <NewspaperIcon class="w-16 h-16 text-[#5f5fcd]/30" />
      </div>
      
      <!-- Category Badge -->
      <div class="absolute top-3 left-3">
        <span 
          class="px-3 py-1 rounded-full text-xs font-semibold shadow-md text-white"
          :style="{ backgroundColor: post.category?.color || '#5f5fcd' }"
        >
          {{ post.category?.name }}
        </span>
      </div>

      <!-- Featured/Sticky Badges -->
      <div class="absolute top-3 right-3 flex flex-col space-y-2">
        <span v-if="post.is_featured" class="px-3 py-1 rounded-full text-xs font-semibold shadow-lg bg-gradient-to-r from-yellow-400 to-amber-500 text-white border border-yellow-300">
          ফিচার্ড
        </span>
        <span v-if="post.is_sticky" class="px-3 py-1 rounded-full text-xs font-semibold shadow-lg bg-gradient-to-r from-blue-500 to-blue-600 text-white border border-blue-400">
          পিন করা
        </span>
      </div>

      <!-- Image Overlay -->
      <div class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </div>

    <!-- Content -->
    <div class="p-6">
      <!-- Meta Information -->
      <div class="flex items-center justify-between mb-3 text-sm text-muted">
        <div class="flex items-center space-x-4">
          <span class="flex items-center">
            <CalendarIcon class="w-4 h-4 mr-1" />
            {{ formatDate(post.published_at) }}
          </span>
          <span class="flex items-center">
            <ClockIcon class="w-4 h-4 mr-1" />
            {{ post.reading_time || 5 }} মিনিট
          </span>
        </div>
        <span class="flex items-center">
          <EyeIcon class="w-4 h-4 mr-1" />
          {{ formatNumber(post.views_count || 0) }}
        </span>
      </div>

      <!-- Title -->
      <h3 class="text-lg font-bold text-primary mb-3 group-hover:text-[#5f5fcd] transition-colors duration-200 line-clamp-2 leading-tight">
        <Link :href="postUrl" class="focus:outline-none">
          {{ post.title }}
        </Link>
      </h3>

      <!-- Excerpt -->
      <p class="text-secondary text-sm mb-4 line-clamp-3 leading-relaxed">
        {{ post.excerpt || stripHtml(post.content).substring(0, 150) + '...' }}
      </p>

      <!-- Author Info -->
      <div v-if="post.author" class="flex items-center mb-4">
        <img 
          v-if="post.author.avatar"
          :src="post.author.avatar" 
          :alt="post.author.name"
          class="w-8 h-8 rounded-full mr-3"
        />
        <div 
          v-else
          class="w-8 h-8 rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] flex items-center justify-center mr-3"
        >
          <span class="text-white font-semibold text-xs">
            {{ getInitials(post.author.name) }}
          </span>
        </div>
        <div>
          <p class="text-sm font-medium text-neutral-700">{{ post.author.name }}</p>
          <p class="text-xs text-muted">লেখক</p>
        </div>
      </div>

      <!-- Tags -->
      <div v-if="post.tags && post.tags.length > 0" class="flex flex-wrap gap-2 mb-4">
        <span 
          v-for="tag in post.tags.slice(0, 3)" 
          :key="tag.id"
          class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-neutral-100 text-neutral-600 hover:bg-neutral-200 transition-colors"
        >
          #{{ tag.name }}
        </span>
        <span v-if="post.tags.length > 3" class="text-xs text-muted">
          +{{ post.tags.length - 3 }} আরও
        </span>
      </div>

      <!-- Actions -->
      <div class="flex items-center justify-center border-t border-neutral-200 pt-4 mt-4">
        <Link 
          :href="postUrl"
          class="text-[#5f5fcd] hover:text-[#4a4aa6] font-medium text-sm flex items-center transition-colors"
        >
          পড়ুন
          <ArrowRightIcon class="w-4 h-4 ml-1" />
        </Link>
      </div>
    </div>

    <!-- Decorative Corner -->
    <div class="absolute top-0 right-0 w-20 h-20 overflow-hidden">
      <div class="absolute top-3 right-3 w-10 h-10 bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/20 transform rotate-45 rounded-lg group-hover:scale-110 transition-transform duration-300"></div>
    </div>
  </article>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { 
  Calendar as CalendarIcon, 
  Clock as ClockIcon, 
  Eye as EyeIcon,
  Heart as HeartIcon,
  MessageCircle as MessageCircleIcon,
  ArrowRight as ArrowRightIcon,
  Newspaper as NewspaperIcon
} from 'lucide-vue-next'

interface BlogPost {
  id: number
  slug: string
  title: string
  excerpt?: string
  content: string
  featured_image?: string
  featured_image_url?: string
  published_at: string
  reading_time?: number
  views_count?: number
  likes_count?: number
  comments_count?: number
  user_liked?: boolean
  is_featured?: boolean
  is_sticky?: boolean
  category?: {
    id: number
    name: string
    slug: string
    color?: string
  }
  author?: {
    id: number
    name: string
    avatar?: string
  }
  tags?: Array<{
    id: number
    name: string
    slug: string
  }>
}

interface Props {
  post: BlogPost
}

const props = defineProps<Props>()

const postUrl = computed(() => route('frontend.blog.show', { slug: props.post.slug }))

const formatDate = (date: string) => {
  const d = new Date(date)
  const options: Intl.DateTimeFormatOptions = { 
    year: 'numeric', 
    month: 'long', 
    day: 'numeric' 
  }
  return new Intl.DateTimeFormat('bn-BD', options).format(d)
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

const stripHtml = (html: string): string => {
  const tmp = document.createElement('div')
  tmp.innerHTML = html
  return tmp.textContent || tmp.innerText || ''
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.line-clamp-3 {
  display: -webkit-box;
  -webkit-line-clamp: 3;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>