<template>
  <FrontendLayout title="কোর্সসমূহ - ইকরা অনলাইন একাডেমি">
    <Head title="কোর্সসমূহ - ইকরা অনলাইন একাডেমি">
      <meta name="description" content="ইকরা অনলাইন একাডেমির কোর্সসমূহ - কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিস্তৃত কোর্স সংগ্রহ। অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা গ্রহণ করুন।" />
      <meta name="keywords" content="ইসলামিক কোর্স, কুরআন কোর্স, হাদিস কোর্স, ফিকহ কোর্স, অনলাইন শিক্ষা, ইকরা কোর্স" />
    </Head>
    <!-- Page Header -->
    <section class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 py-20 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 pattern-dots opacity-10"></div>
      
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="text-center">
          <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
            <BookOpenIcon class="w-4 h-4 text-white mr-2" />
            <span class="text-white text-sm font-medium">আমাদের কোর্সসমূহ</span>
          </div>
          
          <h1 class="text-4xl lg:text-6xl font-bold text-white mb-6">
            ইসলামিক <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">শিক্ষার</span> জগত
          </h1>
          
          <p class="text-xl text-gray-300 max-w-3xl mx-auto leading-relaxed">
            কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিস্তৃত কোর্স সংগ্রহ। 
            অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে আধুনিক পদ্ধতিতে ইসলামিক শিক্ষা গ্রহণ করুন।
          </p>
        </div>
      </div>
    </section>



    <!-- Course Grid -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results -->
        <div>
          <!-- Results Summary -->
          <div class="mb-8">
            <p class="text-gray-600">
              <span class="font-semibold text-gray-900">{{ totalCourses }}</span> টি কোর্স পাওয়া গেছে
            </p>
          </div>

          <!-- Course Grid -->
          <div v-if="allCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <CourseCard 
              v-for="course in allCourses"
              :key="course.id"
              :course="course"
              @enroll="handleCourseEnroll"
            />
          </div>

          <!-- Empty State -->
          <div v-else-if="allCourses.length === 0" class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <BookOpenIcon class="w-10 h-10 text-gray-400" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">কোনো কোর্স পাওয়া যায়নি</h3>
            <p class="text-gray-600 mb-6">দুঃখিত, বর্তমানে কোনো কোর্স উপলব্ধ নেই</p>
          </div>

          <!-- Infinite Scroll Trigger -->
          <div v-if="hasMoreCourses && allCourses.length > 0" class="mt-12">
            <!-- Loading indicator -->
            <div v-if="isLoading" class="text-center py-8">
              <div class="inline-flex items-center space-x-2 text-gray-600">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-[#5f5fcd]"></div>
                <span>আরো কোর্স লোড হচ্ছে...</span>
              </div>
            </div>
            <!-- Invisible trigger element -->
            <div ref="loadMoreTrigger" class="h-20 w-full"></div>
          </div>
          
          <!-- Manual load more button (fallback) -->
          <div v-else-if="hasMoreCourses && allCourses.length > 0" class="text-center mt-12">
            <PrimaryButton 
              @click="loadMoreCourses"
              :loading="isLoading"
              size="lg"
              variant="outline"
            >
              আরো কোর্স দেখুন
            </PrimaryButton>
          </div>
        </div>
      </div>
    </section>


  </FrontendLayout>
</template>

<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import { useIntersectionObserver } from '@vueuse/core'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import { BookOpenIcon, AlertCircleIcon } from 'lucide-vue-next'

// Props
interface Props {
  courses: {
    data: Course[]
    links: any[]
    next_page_url: string | null
    prev_page_url: string | null
    current_page: number
    last_page: number
    total: number
  }
  search?: string
  stats?: {
    total_courses: number
    total_categories: number
  }
}

const props = defineProps<Props>()

// Type definitions
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
  instructor?: {
    name: string
    avatar?: string
  }
  isNew?: boolean
  enrolled?: boolean
  progress?: number
  created_at?: string
}

// State for infinite scroll
const allCourses = ref<Course[]>([])
const isLoading = ref(false)
const loadMoreTrigger = ref<HTMLElement>()

// Computed properties
const hasMoreCourses = computed(() => props.courses?.next_page_url !== null)
const totalCourses = computed(() => props.courses?.total || 0)

// Initialize courses from props
if (props.courses?.data) {
  allCourses.value = [...props.courses.data]
}

// Setup intersection observer for infinite scroll
const { stop } = useIntersectionObserver(
  loadMoreTrigger,
  ([{ isIntersecting }]) => {
    if (isIntersecting && hasMoreCourses.value && !isLoading.value) {
      loadMoreCourses()
    }
  },
  {
    threshold: 0.1,
    rootMargin: '100px'
  }
)

// Methods
const loadMoreCourses = () => {
  if (!props.courses?.next_page_url || isLoading.value) return
  
  isLoading.value = true
  
  router.get(props.courses.next_page_url, 
    { merge: true }, 
    {
      preserveState: true,
      preserveScroll: true,
      onSuccess: (page) => {
        // Append new courses to existing list
        const newCourses = page.props.courses?.data || []
        allCourses.value = [...allCourses.value, ...newCourses]
      },
      onFinish: () => {
        isLoading.value = false
      }
    }
  )
}

const handleCourseEnroll = (course: Course) => {
  router.visit(route('frontend.payment.checkout', { course: course.slug }))
}

// Cleanup intersection observer on unmount
onUnmounted(() => {
  stop()
})
</script>

<style scoped>
/* Custom styles for the courses page */
.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 4s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}
</style> 
