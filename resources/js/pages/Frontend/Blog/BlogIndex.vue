<template>
  <FrontendLayout title="ব্লগ - ইকরা অনলাইন একাডেমি">
    <Head>
      <title>ব্লগ - ইকরা অনলাইন একাডেমি</title>
      <meta name="description" content="ইসলামিক শিক্ষা, কুরআন, হাদিস এবং ইসলামিক জীবনযাত্রা সম্পর্কে সর্বশেষ নিবন্ধ ও তথ্য পড়ুন।" />
      <meta name="keywords" content="ইসলামিক ব্লগ, কুরআন, হাদিস, ইসলামিক শিক্ষা, ইসলামিক জীবনযাত্রা" />
      <meta property="og:title" content="ব্লগ - ইকরা অনলাইন একাডেমি" />
      <meta property="og:description" content="ইসলামিক শিক্ষা, কুরআন, হাদিস এবং ইসলামিক জীবনযাত্রা সম্পর্কে সর্বশেষ নিবন্ধ ও তথ্য পড়ুন।" />
      <meta property="og:type" content="website" />
    </Head>

    <!-- Page Header -->
    <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 pattern-dots opacity-10"></div>
      
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
          <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
            <BookOpenIcon class="w-4 h-4 text-white mr-2" />
            <span class="text-white text-sm font-medium">ইসলামিক ব্লগ</span>
          </div>
          
          <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
            জ্ঞান ও <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">আধ্যাত্মিকতার</span> আলোকবর্তিকা
          </h1>
          
          <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
            কুরআন, হাদিস, ইসলামিক জীবনযাত্রা এবং ইতিহাস সম্পর্কে গভীর আলোচনা ও দিকনির্দেশনা
          </p>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <section class="py-12 lg:py-16 bg-neutral-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <!-- Main Content Area -->
          <div class="lg:col-span-3">
            <!-- Featured Posts -->
            <div v-if="featuredPosts.length > 0" class="mb-12">
              <h2 class="text-2xl font-bold text-primary mb-6 flex items-center">
                <StarIcon class="w-6 h-6 mr-2 text-[#d4a574]" />
                ফিচার্ড পোস্ট
              </h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <BlogCard 
                  v-for="post in featuredPosts.slice(0, 2)" 
                  :key="post.id"
                  :post="post"
                />
              </div>
            </div>

            <!-- Filter Tabs -->
            <div class="mb-8">
              <div class="border-b border-neutral-200">
                <nav class="flex space-x-8">
                  <button
                    v-for="filter in filters"
                    :key="filter.key"
                    @click="activeFilter = filter.key"
                    class="py-4 px-1 border-b-2 font-medium text-sm transition-colors"
                    :class="activeFilter === filter.key 
                      ? 'border-[#5f5fcd] text-[#5f5fcd]' 
                      : 'border-transparent text-muted hover:text-neutral-700 hover:border-neutral-300'"
                  >
                    {{ filter.label }}
                    <span class="ml-2 text-xs bg-neutral-100 text-secondary px-2 py-1 rounded-full">
                      {{ filter.count }}
                    </span>
                  </button>
                </nav>
              </div>
            </div>

            <!-- Posts Grid -->
            <BlogGrid :posts="filteredPosts" :loading="loading" />

            <!-- Load More / Pagination -->
            <div v-if="hasMorePosts" class="mt-12 text-center">
              <button
                @click="loadMorePosts"
                :disabled="loadingMore"
                class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] hover:from-[#4a4aa6] hover:to-[#1f3e1b] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd] transition-all duration-200 disabled:opacity-50"
              >
                <Loader2Icon v-if="loadingMore" class="w-5 h-5 mr-2 animate-spin" />
                {{ loadingMore ? 'লোড হচ্ছে...' : 'আরও পোস্ট দেখুন' }}
              </button>
            </div>
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <BlogSidebar 
              :categories="categories"
              :popular-tags="popularTags"
              :recent-posts="recentPosts"
              :active-category="activeCategory"
            />
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head, usePage } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import BlogGrid from '@/components/Frontend/Blog/BlogGrid.vue'
import BlogCard from '@/components/Frontend/Blog/BlogCard.vue'
import BlogSidebar from '@/components/Frontend/Blog/BlogSidebar.vue'
import { Star as StarIcon, Loader2 as Loader2Icon, BookOpen as BookOpenIcon } from 'lucide-vue-next'

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
  posts: BlogPost[]
  featuredPosts: BlogPost[]
  categories: Array<{
    id: number
    name: string
    slug: string
    color?: string
    posts_count: number
  }>
  popularTags: Array<{
    id: number
    name: string
    slug: string
    posts_count: number
  }>
  recentPosts: Array<{
    id: number
    slug: string
    title: string
    featured_image_url?: string
    published_at: string
  }>
  hasMorePosts?: boolean
  activeCategory?: string
}

const props = defineProps<Props>()

const activeFilter = ref('all')
const loading = ref(false)
const loadingMore = ref(false)
const allPosts = ref([...props.posts])

const filters = [
  { key: 'all', label: 'সব পোস্ট', count: allPosts.value.length },
  { key: 'recent', label: 'সাম্প্রতিক', count: allPosts.value.filter(p => isRecent(p.published_at)).length },
  { key: 'popular', label: 'জনপ্রিয়', count: allPosts.value.filter(p => (p.views_count || 0) > 100).length },
  { key: 'featured', label: 'ফিচার্ড', count: allPosts.value.filter(p => p.is_featured).length },
]

const filteredPosts = computed(() => {
  switch (activeFilter.value) {
    case 'recent':
      return allPosts.value.filter(post => isRecent(post.published_at))
    case 'popular':
      return allPosts.value.filter(post => (post.views_count || 0) > 100)
    case 'featured':
      return allPosts.value.filter(post => post.is_featured)
    default:
      return allPosts.value
  }
})

const loadMorePosts = async () => {
  loadingMore.value = true
  try {
    // Implement load more logic using Inertia
    await new Promise(resolve => setTimeout(resolve, 1000)) // Simulate API call
  } catch (error) {
    console.error('Error loading more posts:', error)
  } finally {
    loadingMore.value = false
  }
}

function isRecent(publishedAt: string): boolean {
  const publishDate = new Date(publishedAt)
  const now = new Date()
  const diffTime = Math.abs(now.getTime() - publishDate.getTime())
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  return diffDays <= 7 // Posts from last 7 days
}

onMounted(() => {
  // Any initialization logic
})
</script>

<style scoped>
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

@keyframes float-delayed {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-15px);
  }
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
  animation: float-delayed 8s ease-in-out infinite;
}
</style>