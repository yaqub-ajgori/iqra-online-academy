<template>
  <aside class="space-y-8">
    <!-- Search -->
    <div class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
      <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
        <SearchIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
        অনুসন্ধান
      </h3>
      <div class="relative">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="পোস্ট অনুসন্ধান করুন..."
          class="w-full pl-10 pr-4 py-3 border border-neutral-200 rounded-xl focus:ring-[#5f5fcd] focus:border-[#5f5fcd] bg-neutral-50"
          @keyup.enter="performSearch"
        />
        <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-neutral-400" />
        <button 
          v-if="searchQuery"
          @click="clearSearch"
          class="absolute right-3 top-1/2 transform -translate-y-1/2 text-neutral-400 hover:text-secondary"
        >
          <XMarkIcon class="w-4 h-4" />
        </button>
      </div>
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
      <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
        <TagIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
        বিভাগসমূহ
      </h3>
      <ul class="space-y-2">
        <li v-for="category in categories" :key="category.id">
          <Link 
            :href="route('frontend.blog.category', { slug: category.slug })"
            class="flex items-center justify-between p-3 rounded-lg hover:bg-neutral-50 transition-colors group"
            :class="{ 'bg-[#5f5fcd]/10 text-[#5f5fcd]': category.slug === activeCategory }"
          >
            <div class="flex items-center">
              <div 
                class="w-3 h-3 rounded-full mr-3"
                :style="{ backgroundColor: category.color || '#5f5fcd' }"
              ></div>
              <span class="font-medium">{{ category.name }}</span>
            </div>
            <span class="text-sm text-muted group-hover:text-neutral-700">
              {{ category.posts_count }}
            </span>
          </Link>
        </li>
      </ul>
    </div>

    <!-- Popular Tags -->
    <div class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
      <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
        <HashtagIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
        জনপ্রিয় ট্যাগ
      </h3>
      <div class="flex flex-wrap gap-2">
        <Link
          v-for="tag in popularTags"
          :key="tag.id"
          :href="route('frontend.blog.tag', { slug: tag.slug })"
          class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-neutral-100 text-neutral-700 hover:bg-[#5f5fcd] hover:text-white transition-colors"
          :class="{ 'bg-[#5f5fcd] text-white': tag.slug === activeTag }"
        >
          #{{ tag.name }}
          <span class="ml-1 text-xs opacity-75">({{ tag.posts_count }})</span>
        </Link>
      </div>
    </div>

    <!-- Recent Posts -->
    <div class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
      <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
        <ClockIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
        সাম্প্রতিক পোস্ট
      </h3>
      <div class="space-y-4">
        <article 
          v-for="post in recentPosts" 
          :key="post.id"
          class="group cursor-pointer"
        >
          <Link :href="route('frontend.blog.show', { slug: post.slug })" class="block">
            <div class="flex space-x-3">
              <img 
                v-if="post.featured_image_url"
                :src="post.featured_image_url" 
                :alt="post.title"
                class="w-16 h-16 rounded-lg object-cover flex-shrink-0"
              />
              <div 
                v-else
                class="w-16 h-16 rounded-lg bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 flex items-center justify-center flex-shrink-0"
              >
                <NewspaperIcon class="w-6 h-6 text-[#5f5fcd]/30" />
              </div>
              <div class="flex-1 min-w-0">
                <h4 class="text-sm font-medium text-primary line-clamp-2 group-hover:text-[#5f5fcd] transition-colors">
                  {{ post.title }}
                </h4>
                <p class="text-xs text-muted mt-1 flex items-center">
                  <CalendarIcon class="w-3 h-3 mr-1" />
                  {{ formatDate(post.published_at) }}
                </p>
              </div>
            </div>
          </Link>
        </article>
      </div>
    </div>

  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
  Search as SearchIcon,
  Tag as TagIcon,
  Hash as HashtagIcon,
  Clock as ClockIcon,
  X as XMarkIcon,
  Calendar as CalendarIcon,
  Newspaper as NewspaperIcon
} from 'lucide-vue-next'

interface Category {
  id: number
  name: string
  slug: string
  color?: string
  posts_count: number
}

interface Tag {
  id: number
  name: string
  slug: string
  posts_count: number
}

interface RecentPost {
  id: number
  slug: string
  title: string
  featured_image_url?: string
  published_at: string
}

interface Props {
  categories: Category[]
  popularTags: Tag[]
  recentPosts: RecentPost[]
  activeCategory?: string
  activeTag?: string
}

defineProps<Props>()

const searchQuery = ref('')

const performSearch = () => {
  if (searchQuery.value.trim()) {
    router.get(route('frontend.blog.search'), {
      q: searchQuery.value.trim()
    })
  }
}

const clearSearch = () => {
  searchQuery.value = ''
}


const formatDate = (date: string) => {
  const d = new Date(date)
  const options: Intl.DateTimeFormatOptions = { 
    month: 'short', 
    day: 'numeric' 
  }
  return new Intl.DateTimeFormat('bn-BD', options).format(d)
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>