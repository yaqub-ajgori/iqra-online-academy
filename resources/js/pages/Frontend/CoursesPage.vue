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
        <!-- Loading State -->
        <div v-if="loading" class="text-center py-16">
          <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#5f5fcd]/10 text-[#5f5fcd] mb-4">
            <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-[#5f5fcd] mr-2"></div>
            কোর্স লোড হচ্ছে...
          </div>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="text-center py-16">
          <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <AlertCircleIcon class="w-10 h-10 text-red-500" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">কোর্স লোড করতে সমস্যা হয়েছে</h3>
          <p class="text-gray-600 mb-6">{{ error }}</p>
          <PrimaryButton @click="loadCourses" variant="outline">
            আবার চেষ্টা করুন
          </PrimaryButton>
        </div>

        <!-- Results -->
        <div v-else>
          <!-- Results Summary -->
          <div class="mb-8">
            <p class="text-gray-600">
              <span class="font-semibold text-gray-900">{{ filteredCourses.length }}</span> টি কোর্স পাওয়া গেছে
            </p>
          </div>

          <!-- Course Grid -->
          <div v-if="filteredCourses.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <CourseCard 
              v-for="course in filteredCourses"
              :key="course.id"
              :course="course"
              @enroll="handleCourseEnroll"
              @favorite="handleCourseFavorite"
            />
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
              <BookOpenIcon class="w-10 h-10 text-gray-400" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">কোনো কোর্স পাওয়া যায়নি</h3>
            <p class="text-gray-600 mb-6">দুঃখিত, বর্তমানে কোনো কোর্স উপলব্ধ নেই</p>
          </div>

          <!-- Load More -->
          <div v-if="hasMoreCourses && filteredCourses.length > 0" class="text-center mt-12">
            <PrimaryButton 
              @click="loadMoreCourses"
              :loading="loadingMore"
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
import { ref, computed, onMounted, watch } from 'vue'
import { router, usePage, Head } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import { BookOpenIcon, AlertCircleIcon } from 'lucide-vue-next'

// State
const loading = ref(false)
const loadingMore = ref(false)
const error = ref('')
const hasMoreCourses = ref(true)

// Data
const courses = ref<Course[]>([])
const currentPage = ref(1)

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
  created_at?: string // for sorting by newest
}

// Computed properties
const filteredCourses = computed(() => {
  return courses.value
})

// Methods
const loadCourses = async () => {
  loading.value = true
  error.value = ''
  
  try {
    await router.get(route('frontend.courses.index'), {
      page: currentPage.value
    }, {
      preserveScroll: true,
      only: ['courses']
    })
    // Data will be updated via Inertia page props
  } catch (err) {
    error.value = 'কোর্স লোড করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।'
    console.error('Error loading courses:', err)
  } finally {
    loading.value = false
  }
}

const loadMoreCourses = async () => {
  loadingMore.value = true
  currentPage.value++
  
  try {
    await router.get(route('frontend.courses.index'), {
      page: currentPage.value
    }, {
      preserveState: true,
      preserveScroll: true,
      only: ['courses']
    })
    // Data will be updated via Inertia page props
  } catch (err) {
    console.error('Error loading more courses:', err)
  } finally {
    loadingMore.value = false
  }
}

const handleCourseEnroll = (course: Course) => {
  // Redirect to payment page for enrollment
  router.visit(route('frontend.payment.checkout', { course: course.slug }))
}

const handleCourseFavorite = (course: Course, isFavorite: boolean) => {
  // Handle favorite toggle logic here
}



// Watch for page props changes
const page = usePage()
watch(() => page.props.courses, (newCourses) => {
  if (newCourses && typeof newCourses === 'object' && 'data' in newCourses) {
    courses.value = (newCourses as any).data || []
  }
}, { immediate: true })

// Initialize on mount
onMounted(() => {
  // Always load courses on initial page load to ensure we have data
  if (courses.value.length === 0) {
    loadCourses()
  }
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
