<template>
  <FrontendLayout :title="post.title">
    <Head>
      <title>{{ post.meta_title || post.title }} - ইকরা অনলাইন একাডেমি</title>
      <meta name="description" :content="post.meta_description || post.excerpt || stripHtml(post.content).substring(0, 160)" />
      <meta name="keywords" :content="post.meta_keywords || post.tags?.map(t => t.name).join(', ')" />
      <meta property="og:title" :content="post.title" />
      <meta property="og:description" :content="post.excerpt || stripHtml(post.content).substring(0, 160)" />
      <meta property="og:type" content="article" />
      <meta property="og:image" :content="post.featured_image_url" />
      <meta property="article:published_time" :content="post.published_at" />
      <meta property="article:author" :content="post.author?.name" />
      <meta property="article:section" :content="post.category?.name" />
      <meta property="article:tag" v-for="tag in post.tags" :key="tag.id" :content="tag.name" />
    </Head>

    <!-- Page Header -->
    <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-16 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 pattern-dots opacity-10"></div>
      
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Breadcrumb -->
        <nav class="flex items-center space-x-2 text-sm mb-8">
          <Link :href="route('frontend.home')" class="text-gray-300 hover:text-white">হোম</Link>
          <ChevronRightIcon class="w-4 h-4 text-gray-400" />
          <Link :href="route('frontend.blog.index')" class="text-gray-300 hover:text-white">ব্লগ</Link>
          <ChevronRightIcon class="w-4 h-4 text-gray-400" />
          <span class="text-white font-medium">{{ post.category?.name || 'পোস্ট' }}</span>
        </nav>
        
        <div class="text-center">
          <div v-if="post.category" class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
            <BookOpenIcon class="w-4 h-4 text-white mr-2" />
            <span class="text-white text-sm font-medium">{{ post.category.name }}</span>
          </div>
          
          <h1 class="text-3xl lg:text-5xl font-bold text-white mb-6 leading-tight">
            {{ post.title }}
          </h1>
          
          <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-gray-300">
            <div class="flex items-center">
              <CalendarIcon class="w-4 h-4 mr-2" />
              {{ formatDate(post.published_at) }}
            </div>
            <div class="flex items-center">
              <ClockIcon class="w-4 h-4 mr-2" />
              {{ post.reading_time }} মিনিট পড়ার সময়
            </div>
            <div class="flex items-center">
              <EyeIcon class="w-4 h-4 mr-2" />
              {{ post.views_count }} বার দেখা হয়েছে
            </div>
            <div v-if="post.author" class="flex items-center">
              <UserIcon class="w-4 h-4 mr-2" />
              {{ post.author.name }}
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <article class="py-12 lg:py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-12">
          <!-- Article Content -->
          <div class="lg:col-span-3">
            <!-- Social Share -->
            <div class="flex items-center space-x-4 mb-8">
              <span class="text-sm font-medium text-neutral-700">শেয়ার করুন:</span>
              <div class="flex space-x-2">
                <button
                  @click="shareOnFacebook"
                  class="p-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                >
                  <Facebook class="w-4 h-4" />
                </button>
                <button
                  @click="shareOnTwitter"
                  class="p-2 rounded-lg bg-sky-500 text-white hover:bg-sky-600 transition-colors"
                >
                  <Twitter class="w-4 h-4" />
                </button>
                <button
                  @click="shareOnWhatsApp"
                  class="p-2 rounded-lg bg-green-600 text-white hover:bg-green-700 transition-colors"
                >
                  <MessageCircle class="w-4 h-4" />
                </button>
                <button
                  @click="copyLink"
                  class="p-2 rounded-lg bg-neutral-600 text-white hover:bg-neutral-700 transition-colors"
                >
                  <LinkIcon class="w-4 h-4" />
                </button>
              </div>
            </div>

            <!-- Featured Image -->
            <div v-if="post.featured_image_url" class="mb-8">
              <img 
                :src="post.featured_image_url" 
                :alt="post.title"
                class="w-full h-64 lg:h-96 object-cover rounded-2xl shadow-lg"
              />
            </div>

            <!-- Article Content -->
            <div class="prose prose-lg max-w-none mb-8">
              <div v-html="post.content" class="blog-content"></div>
            </div>

            <!-- Tags -->
            <div v-if="post.tags && post.tags.length > 0" class="mb-8">
              <h3 class="text-lg font-semibold text-primary mb-4">ট্যাগসমূহ:</h3>
              <div class="flex flex-wrap gap-2">
                <Link
                  v-for="tag in post.tags"
                  :key="tag.id"
                  :href="route('frontend.blog.tag', { slug: tag.slug })"
                  class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-medium bg-neutral-100 text-neutral-700 hover:bg-[#5f5fcd] hover:text-white transition-colors"
                >
                  #{{ tag.name }}
                </Link>
              </div>
            </div>

            <!-- Reactions -->
            <div class="mb-8 p-6 bg-neutral-50 rounded-2xl">
              <h3 class="text-lg font-semibold text-primary mb-4">এই পোস্টটি কেমন লাগল?</h3>
              
              <div class="flex space-x-4">
                <button
                  @click="toggleReaction('like')"
                  :disabled="reactionLoading"
                  class="reaction-btn-like group flex items-center px-4 py-2 rounded-lg border transition-all duration-300 cursor-pointer transform hover:scale-105 active:scale-95"
                  :class="[
                    reactionCounts.like_active 
                      ? 'bg-red-50 border-red-200 text-red-700 shadow-md' 
                      : 'bg-white border-neutral-200 text-neutral-700 hover:bg-red-50 hover:border-red-200 hover:shadow-sm',
                    reactionLoading ? 'opacity-50' : ''
                  ]"
                >
                  <HeartIcon 
                    class="heart-icon w-5 h-5 mr-2 transition-all duration-300" 
                    :class="{ 
                      'fill-current text-red-500': reactionCounts.like_active,
                      'group-hover:text-red-500 group-hover:scale-110': !reactionCounts.like_active
                    }" 
                  />
                  <span class="font-medium">পছন্দ</span>
                  <span 
                    ref="likeCountRef"
                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold transition-all duration-300"
                    :class="reactionCounts.like_active ? 'bg-red-100 text-red-700' : 'bg-neutral-100 text-neutral-600'"
                  >
                    {{ reactionCounts.likes_count }}
                  </span>
                </button>
                <button
                  @click="toggleReaction('helpful')"
                  :disabled="reactionLoading"
                  class="reaction-btn-helpful group flex items-center px-4 py-2 rounded-lg border transition-all duration-300 cursor-pointer transform hover:scale-105 active:scale-95"
                  :class="[
                    reactionCounts.helpful_active 
                      ? 'bg-green-50 border-green-200 text-green-700 shadow-md' 
                      : 'bg-white border-neutral-200 text-neutral-700 hover:bg-green-50 hover:border-green-200 hover:shadow-sm',
                    reactionLoading ? 'opacity-50' : ''
                  ]"
                >
                  <ThumbsUpIcon 
                    class="thumbs-icon w-5 h-5 mr-2 transition-all duration-300" 
                    :class="{ 
                      'fill-current text-green-500': reactionCounts.helpful_active,
                      'group-hover:text-green-500 group-hover:scale-110': !reactionCounts.helpful_active
                    }" 
                  />
                  <span class="font-medium">সহায়ক</span>
                  <span 
                    ref="helpfulCountRef"
                    class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold transition-all duration-300"
                    :class="reactionCounts.helpful_active ? 'bg-green-100 text-green-700' : 'bg-neutral-100 text-neutral-600'"
                  >
                    {{ reactionCounts.helpful_count }}
                  </span>
                </button>
              </div>
            </div>

            <!-- Comments Section -->
            <BlogComments 
              :post-id="post.id"
              :comments="comments"
              @comment-added="handleCommentAdded"
            />
          </div>

          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <div class="sticky top-8 space-y-8">
              <!-- Table of Contents -->
              <div v-if="tableOfContents.length > 0" class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                  <ListIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
                  সূচিপত্র
                </h3>
                <nav>
                  <ul class="space-y-2">
                    <li v-for="item in tableOfContents" :key="item.id">
                      <a 
                        :href="`#${item.id}`"
                        class="text-sm text-secondary hover:text-[#5f5fcd] transition-colors block py-1"
                        :class="{ 'pl-4': item.level > 2 }"
                      >
                        {{ item.text }}
                      </a>
                    </li>
                  </ul>
                </nav>
              </div>

              <!-- Author Info -->
              <div v-if="post.author" class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
                <h3 class="text-base font-bold text-primary mb-3">লেখক সম্পর্কে</h3>
                <div class="flex items-start space-x-4">
                  <img 
                    v-if="post.author.avatar"
                    :src="post.author.avatar" 
                    :alt="post.author.name"
                    class="w-14 h-14 rounded-full object-cover border border-gray-200"
                  />
                  <div 
                    v-else
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] flex items-center justify-center border border-gray-200"
                  >
                    <span class="text-white font-bold text-lg">
                      {{ getInitials(post.author.name) }}
                    </span>
                  </div>
                  <div class="flex flex-col justify-center">
                    <h4 class="text-base font-bold text-neutral-800 leading-tight">{{ post.author.name }}</h4>
                    <p class="text-xs text-gray-500 mt-0.5">ইসলামি স্কলার ও লেখক</p>
                  </div>
                </div>
              </div>

              <!-- Related Posts -->
              <div v-if="relatedPosts.length > 0" class="bg-white rounded-2xl p-6 shadow-islamic border border-neutral-100">
                <h3 class="text-lg font-semibold text-primary mb-4 flex items-center">
                  <BookOpenIcon class="w-5 h-5 mr-2 text-[#5f5fcd]" />
                  সম্পর্কিত পোস্ট
                </h3>
                <div class="space-y-4">
                  <article 
                    v-for="relatedPost in relatedPosts" 
                    :key="relatedPost.id"
                    class="group"
                  >
                    <Link :href="route('frontend.blog.show', { slug: relatedPost.slug })">
                      <h4 class="text-sm font-medium text-primary line-clamp-2 group-hover:text-[#5f5fcd] transition-colors">
                        {{ relatedPost.title }}
                      </h4>
                      <p class="text-xs text-muted mt-1">
                        {{ formatDate(relatedPost.published_at) }}
                      </p>
                    </Link>
                  </article>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </article>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { Head, Link, usePage, useForm, router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import BlogComments from '@/components/Frontend/Blog/BlogComments.vue'
import {
  Calendar as CalendarIcon,
  Clock as ClockIcon,
  Eye as EyeIcon,
  Heart as HeartIcon,
  ThumbsUp as ThumbsUpIcon,
  ChevronRight as ChevronRightIcon,
  Link as LinkIcon,
  List as ListIcon,
  BookOpen as BookOpenIcon,
  User as UserIcon,
  Info as InfoIcon,
  MessageCircle,
  Facebook,
  Twitter
} from 'lucide-vue-next'

interface BlogPost {
  id: number
  slug: string
  title: string
  excerpt?: string
  content: string
  featured_image_url?: string
  published_at: string
  reading_time?: number
  views_count?: number
  likes_count?: number
  helpful_count?: number
  meta_title?: string
  meta_description?: string
  meta_keywords?: string
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
  comments: any[]
  relatedPosts: BlogPost[]
  userReactions: {
    like: boolean
    helpful: boolean
  }
}

const props = defineProps<Props>()

const page = usePage()
const isAuthenticated = computed(() => !!page.props.auth?.user)

// Reactive reaction counts
const reactionCounts = ref({
  likes_count: props.post.likes_count || 0,
  helpful_count: props.post.helpful_count || 0,
  like_active: props.userReactions.like,
  helpful_active: props.userReactions.helpful
})

const reactionLoading = ref(false)
const likeCountRef = ref<HTMLElement>()
const helpfulCountRef = ref<HTMLElement>()

const tableOfContents = ref<Array<{ id: string; text: string; level: number }>>([])

const generateTableOfContents = () => {
  const headings = document.querySelectorAll('.blog-content h2, .blog-content h3, .blog-content h4')
  tableOfContents.value = Array.from(headings).map((heading, index) => {
    const id = `heading-${index}`
    heading.id = id
    return {
      id,
      text: heading.textContent || '',
      level: parseInt(heading.tagName.charAt(1))
    }
  })
}

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
  // Create a new DOMParser to safely parse HTML without executing scripts
  const parser = new DOMParser()
  const doc = parser.parseFromString(html, 'text/html')
  return doc.body.textContent || doc.body.innerText || ''
}

const shareOnFacebook = () => {
  const url = encodeURIComponent(window.location.href)
  window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank')
}

const shareOnTwitter = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(props.post.title)
  window.open(`https://twitter.com/intent/tweet?url=${url}&text=${text}`, '_blank')
}

const shareOnWhatsApp = () => {
  const url = encodeURIComponent(window.location.href)
  const text = encodeURIComponent(props.post.title)
  window.open(`https://wa.me/?text=${text}%20${url}`, '_blank')
}

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(window.location.href)
    // Show success message
  } catch (err) {
    console.error('Failed to copy link:', err)
  }
}

const toggleReaction = (type: 'like' | 'helpful') => {
  if (reactionLoading.value) return

  reactionLoading.value = true

  // Optimistically update UI immediately using Vue reactivity
  const wasActive = type === 'like' ? reactionCounts.value.like_active : reactionCounts.value.helpful_active
  const currentCount = type === 'like' ? reactionCounts.value.likes_count : reactionCounts.value.helpful_count

  if (type === 'like') {
    reactionCounts.value.like_active = !wasActive
    reactionCounts.value.likes_count = wasActive ? currentCount - 1 : currentCount + 1
    // Trigger count animation
    if (likeCountRef.value) {
      likeCountRef.value.classList.add('count-change')
      setTimeout(() => likeCountRef.value?.classList.remove('count-change'), 400)
    }
  } else {
    reactionCounts.value.helpful_active = !wasActive
    reactionCounts.value.helpful_count = wasActive ? currentCount - 1 : currentCount + 1
    // Trigger count animation
    if (helpfulCountRef.value) {
      helpfulCountRef.value.classList.add('count-change')
      setTimeout(() => helpfulCountRef.value?.classList.remove('count-change'), 400)
    }
  }

  // Send request to backend using Inertia
  router.post(route('frontend.blog.posts.react', { post: props.post.id }), 
    { type: type }, 
    {
      preserveScroll: true,
      preserveState: true,
      only: [], 
      onError: (errors) => {
        // Revert optimistic update on error
        if (type === 'like') {
          reactionCounts.value.like_active = wasActive
          reactionCounts.value.likes_count = currentCount
        } else {
          reactionCounts.value.helpful_active = wasActive
          reactionCounts.value.helpful_count = currentCount
        }
        console.error('Error toggling reaction:', errors)
      },
      onFinish: () => {
        reactionLoading.value = false
      }
    }
  )
}

const handleCommentAdded = (comment: any) => {
  console.log('New comment added:', comment)
}

onMounted(() => {
  generateTableOfContents()
})
</script>

<style scoped>
.prose {
  color: var(--color-iqra-neutral-700);
  line-height: 1.6;
}

.prose h2 {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--color-iqra-text-primary);
  margin-top: 2rem;
  margin-bottom: 1rem;
}

.prose h3 {
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-iqra-text-primary);
  margin-top: 1.5rem;
  margin-bottom: 0.75rem;
}

.prose h4 {
  font-size: 1.125rem;
  font-weight: 600;
  color: var(--color-iqra-text-primary);
  margin-top: 1rem;
  margin-bottom: 0.5rem;
}

.prose p {
  margin-bottom: 1rem;
}

.prose ul, .prose ol {
  margin-bottom: 1rem;
  padding-left: 1.5rem;
}

.prose li {
  margin-bottom: 0.5rem;
}

.prose blockquote {
  border-left: 4px solid #5f5fcd;
  padding-left: 1rem;
  font-style: italic;
  color: var(--color-iqra-text-secondary);
  margin: 1.5rem 0;
}

.prose code {
  background-color: var(--color-iqra-neutral-100);
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  font-size: 0.875rem;
}

.prose pre {
  background-color: var(--color-iqra-neutral-900);
  color: var(--color-iqra-neutral-100);
  padding: 1rem;
  border-radius: 0.5rem;
  overflow-x: auto;
  margin: 1.5rem 0;
}

.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Custom reaction button animations */
@keyframes heartBeat {
  0% { transform: scale(1); }
  50% { transform: scale(1.15); }
  100% { transform: scale(1); }
}

@keyframes thumbsUp {
  0% { transform: scale(1) rotate(0deg); }
  50% { transform: scale(1.1) rotate(-5deg); }
  100% { transform: scale(1) rotate(0deg); }
}

.reaction-btn-like:active .heart-icon {
  animation: heartBeat 0.3s ease-in-out;
}

.reaction-btn-helpful:active .thumbs-icon {
  animation: thumbsUp 0.3s ease-in-out;
}

/* Number count animation */
@keyframes numberPop {
  0% { transform: scale(1); }
  50% { transform: scale(1.2); }
  100% { transform: scale(1); }
}

.count-change {
  animation: numberPop 0.4s ease-in-out;
}
</style>