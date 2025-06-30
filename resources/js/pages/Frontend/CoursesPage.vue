<template>
  <FrontendLayout title="কোর্সসমূহ - ইকরা অনলাইন একাডেমি">
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

    <!-- Filters and Search -->
    <section class="py-12 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-islamic p-6">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            <!-- Search -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">কোর্স খুঁজুন</label>
              <div class="relative">
                <SearchIcon class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="কোর্সের নাম বা বিষয় লিখুন..."
                  class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                />
              </div>
            </div>

            <!-- Category Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">বিভাগ</label>
              <select 
                v-model="selectedCategory"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
              >
                <option value="">সব বিভাগ</option>
                <option value="কুরআন">কুরআন</option>
                <option value="হাদিস">হাদিস</option>
                <option value="ফিকহ">ফিকহ</option>
                <option value="আকিদা">আকিদা</option>
                <option value="সীরাত">সীরাত</option>
                <option value="আরবি">আরবি ভাষা</option>
              </select>
            </div>

            <!-- Level Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">লেভেল</label>
              <select 
                v-model="selectedLevel"
                class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
              >
                <option value="">সব লেভেল</option>
                <option value="beginner">শুরুর স্তর</option>
                <option value="intermediate">মধ্যম স্তর</option>
                <option value="advanced">উন্নত স্তর</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Course Grid -->
    <section class="py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Results Summary -->
        <div class="flex justify-between items-center mb-8">
          <p class="text-gray-600">
            <span class="font-semibold text-gray-900">{{ filteredCourses.length }}</span> টি কোর্স পাওয়া গেছে
          </p>
          
          <!-- Sort Options -->
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">সাজান:</span>
            <select 
              v-model="sortBy"
              class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
            >
              <option value="popular">জনপ্রিয়তা</option>
              <option value="newest">নতুন</option>
              <option value="price_low">দাম (কম থেকে বেশি)</option>
              <option value="price_high">দাম (বেশি থেকে কম)</option>
              <option value="rating">রেটিং</option>
            </select>
          </div>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <CourseCard 
            v-for="course in filteredCourses" 
            :key="course.id"
            :course="course"
            @enroll="handleCourseEnroll"
            @favorite="handleCourseFavorite"
            @preview="handleCoursePreview"
          />
        </div>

        <!-- Empty State -->
        <div v-if="filteredCourses.length === 0" class="text-center py-16">
          <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <SearchIcon class="w-10 h-10 text-gray-400" />
          </div>
          <h3 class="text-xl font-semibold text-gray-900 mb-4">কোনো কোর্স পাওয়া যায়নি</h3>
          <p class="text-gray-600 mb-6">অন্য কিওয়ার্ড বা ফিল্টার দিয়ে চেষ্টা করুন</p>
          <PrimaryButton @click="clearFilters" variant="outline">
            ফিল্টার রিসেট করুন
          </PrimaryButton>
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
    </section>

    <!-- Newsletter CTA -->
    <section class="py-16 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27]">
      <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-4">নতুন কোর্সের আপডেট পান</h2>
        <p class="text-white/90 mb-8">নতুন কোর্স এবং বিশেষ অফারের খবর সবার আগে পেতে সাবস্ক্রাইব করুন</p>
        
        <div class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
          <input 
            type="email" 
            placeholder="আপনার ইমেইল ঠিকানা"
            class="flex-1 px-4 py-3 rounded-lg border-0 focus:ring-2 focus:ring-white"
          />
          <PrimaryButton variant="accent" size="lg">
            সাবস্ক্রাইব করুন
          </PrimaryButton>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import { BookOpenIcon, SearchIcon } from 'lucide-vue-next'

// Search and filter state
const searchQuery = ref('')
const selectedCategory = ref('')
const selectedLevel = ref('')
const sortBy = ref('popular')
const loadingMore = ref(false)
const hasMoreCourses = ref(true)

// All courses data
const allCourses = ref([
  {
    id: 1,
    title: 'কুরআন তিলাওয়াত ও তাজবীদ',
    description: 'সহীহ উচ্চারণে কুরআন তিলাওয়াত শিখুন। তাজবীদের নিয়মকানুন সহ বিস্তারিত আলোচনা।',
    image: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=400&h=250&fit=crop',
    category: 'কুরআন',
    level: 'beginner' as const,
    price: 0,
    duration: '২ মাস',
    students_count: 1250,
    rating: 4.9,
    instructor: {
      name: 'উস্তাদ মোহাম্মদ রহমান',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  },
  {
    id: 2,
    title: 'হাদিস ও সুন্নাহর আলোকে জীবনযাত্রা',
    description: 'রাসূল (সা.) এর সুন্নাহ অনুসরণ করে আদর্শ জীবনযাত্রার নির্দেশনা।',
    image: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=400&h=250&fit=crop',
    category: 'হাদিস',
    level: 'intermediate' as const,
    price: 1500,
    duration: '৩ মাস',
    students_count: 890,
    rating: 4.8,
    instructor: {
      name: 'উস্তাদ আবদুল কারিম',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  },
  {
    id: 3,
    title: 'ইসলামিক ফিকহ - দৈনন্দিন মাসায়েল',
    description: 'দৈনন্দিন জীবনের ইসলামিক সমাধান। ইবাদত, মুআমালাত এবং সামাজিক বিষয়াবলী।',
    image: 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=400&h=250&fit=crop',
    category: 'ফিকহ',
    level: 'advanced' as const,
    price: 2500,
    duration: '৪ মাস',
    students_count: 650,
    rating: 4.7,
    instructor: {
      name: 'উস্তাদ মোহাম্মদ হাসান',
      avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  },
  {
    id: 4,
    title: 'ইসলামিক আকিদা ও বিশ্বাস',
    description: 'তাওহিদ, রিসালাত ও আখিরাতের বিস্তারিত আলোচনা। ইমানের মূলভিত্তি সম্পর্কে জানুন।',
    image: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop',
    category: 'আকিদা',
    level: 'beginner' as const,
    price: 1200,
    duration: '২.৫ মাস',
    students_count: 540,
    rating: 4.6,
    instructor: {
      name: 'উস্তাদ আহমাদ আলী',
      avatar: 'https://images.unsplash.com/photo-1568602471122-7832951cc4c5?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  },
  {
    id: 5,
    title: 'রাসূলের (সা.) জীবনী - সীরাতুন্নবী',
    description: 'মহানবী (সা.) এর পূর্ণ জীবনী। জন্ম থেকে ওফাত পর্যন্ত সকল ঘটনার বিশ্লেষণ।',
    image: 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=400&h=250&fit=crop',
    category: 'সীরাত',
    level: 'intermediate' as const,
    price: 1800,
    duration: '৪ মাস',
    students_count: 720,
    rating: 4.9,
    instructor: {
      name: 'উস্তাদ ইবরাহিম খলিল',
      avatar: 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  },
  {
    id: 6,
    title: 'আরবি ভাষা শিক্ষা - প্রাথমিক স্তর',
    description: 'কুরআন ও হাদিস বোঝার জন্য প্রয়োজনীয় আরবি ব্যাকরণ ও শব্দভাণ্ডার।',
    image: 'https://images.unsplash.com/photo-1520637836862-4d197d17c32a?w=400&h=250&fit=crop',
    category: 'আরবি',
    level: 'beginner' as const,
    price: 2000,
    duration: '৬ মাস',
    students_count: 980,
    rating: 4.5,
    instructor: {
      name: 'উস্তাদ খালেদ হোসেন',
      avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=40&h=40&fit=crop&crop=face'
    },
    has_preview: true
  }
])

// Computed properties for filtering
const filteredCourses = computed(() => {
  let courses = allCourses.value

  // Search filter
  if (searchQuery.value) {
    courses = courses.filter(course => 
      course.title.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.description.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      course.category.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  // Category filter
  if (selectedCategory.value) {
    courses = courses.filter(course => course.category === selectedCategory.value)
  }

  // Level filter
  if (selectedLevel.value) {
    courses = courses.filter(course => course.level === selectedLevel.value)
  }

  // Sort
  courses = [...courses].sort((a, b) => {
    switch (sortBy.value) {
      case 'newest':
        return b.id - a.id
      case 'price_low':
        return a.price - b.price
      case 'price_high':
        return b.price - a.price
      case 'rating':
        return b.rating - a.rating
      default: // popular
        return b.students_count - a.students_count
    }
  })

  return courses
})

// Methods
const handleCourseEnroll = (course: any) => {
  console.log('Enrolling in course:', course.title)
  // Handle course enrollment logic here
}

const handleCourseFavorite = (course: any, isFavorite: boolean) => {
  console.log('Course favorite toggled:', course.title, isFavorite)
  // Handle favorite toggle logic here
}

const handleCoursePreview = (course: any) => {
  console.log('Opening course preview:', course.title)
  // Handle course preview logic here
}

const clearFilters = () => {
  searchQuery.value = ''
  selectedCategory.value = ''
  selectedLevel.value = ''
  sortBy.value = 'popular'
}

const loadMoreCourses = () => {
  loadingMore.value = true
  // Simulate loading more courses
  setTimeout(() => {
    loadingMore.value = false
    hasMoreCourses.value = false // No more courses to load for demo
  }, 1000)
}
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
