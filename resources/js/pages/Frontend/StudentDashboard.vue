<template>
  <FrontendLayout title="ছাত্র ড্যাশবোর্ড - ইকরা অনলাইন একাডেমি">
    <Head title="ছাত্র ড্যাশবোর্ড" />

    <!-- Dashboard Header -->
    <section class="py-12 bg-gradient-to-br from-[#5f5fcd]/10 via-white to-[#2d5a27]/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <div class="w-24 h-24 bg-[#5f5fcd]/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <UserIcon class="w-12 h-12 text-[#5f5fcd]" />
          </div>
          <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
            আস্সালামু আলাইকুম, {{ studentName }}!
          </h1>
          <p class="text-lg text-gray-600 mb-8">
            আপনার শিক্ষার যাত্রায় স্বাগতম। আল্লাহ আপনার জ্ঞান বৃদ্ধি করুন।
          </p>
          
          <!-- Quick Stats -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
              <div class="text-3xl font-bold text-[#5f5fcd] mb-2">{{ enrolledCourses.length }}</div>
              <div class="text-gray-600">এনরোল করা কোর্স</div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
              <div class="text-3xl font-bold text-[#2d5a27] mb-2">{{ completedLessons }}</div>
              <div class="text-gray-600">সম্পন্ন পাঠ</div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-lg border border-gray-100">
              <div class="text-3xl font-bold text-[#d4a574] mb-2">{{ studyHours }}</div>
              <div class="text-gray-600">অধ্যয়ন ঘন্টা</div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Dashboard Content -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
          
          <!-- Sidebar Navigation -->
          <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 sticky top-8">
              <h3 class="text-xl font-bold text-gray-900 mb-6">ড্যাশবোর্ড মেনু</h3>
              
              <nav class="space-y-2">
                <button 
                  v-for="item in navigationItems" 
                  :key="item.id"
                  @click="activeSection = item.id"
                  :class="[
                    'w-full flex items-center space-x-3 px-4 py-3 rounded-lg text-left transition-colors duration-200',
                    activeSection === item.id 
                      ? 'bg-[#5f5fcd] text-white' 
                      : 'text-gray-700 hover:bg-gray-100'
                  ]"
                >
                  <component :is="item.icon" class="w-5 h-5" />
                  <span>{{ item.name }}</span>
                </button>
              </nav>

              <!-- Islamic Quote -->
              <div class="mt-8 p-4 bg-[#2d5a27]/5 rounded-lg border-l-4 border-[#2d5a27]">
                <p class="text-sm text-gray-600 italic mb-2">
                  "যে ব্যক্তি জ্ঞানের সন্ধানে বের হয়, সে আল্লাহর পথে থাকে।"
                </p>
                <p class="text-xs text-gray-500">- হাদিস শরীফ</p>
              </div>
            </div>
          </div>

          <!-- Main Content -->
          <div class="lg:col-span-3">
            
            <!-- Enrolled Courses Section -->
            <div v-if="activeSection === 'courses'" class="space-y-8">
              <div class="flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">আমার কোর্সসমূহ</h2>
                <PrimaryButton
                  @click="goToCourses"
                  variant="outline"
                  size="md"
                  :icon="PlusIcon"
                >
                  নতুন কোর্স খুঁজুন
                </PrimaryButton>
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                  v-for="course in enrolledCourses" 
                  :key="course.id"
                  class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300"
                >
                  <img 
                    :src="course.image" 
                    :alt="course.title"
                    class="w-full h-48 object-cover"
                  />
                  <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ course.title }}</h3>
                    <p class="text-gray-600 mb-4">{{ course.instructor }}</p>
                    
                    <!-- Progress Bar -->
                    <div class="mb-4">
                      <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>অগ্রগতি</span>
                        <span>{{ course.progress }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div 
                          class="bg-[#2d5a27] h-2 rounded-full transition-all duration-300"
                          :style="{ width: course.progress + '%' }"
                        ></div>
                      </div>
                    </div>

                    <div class="flex space-x-3">
                      <PrimaryButton
                        @click="goToLearning(course.slug)"
                        variant="primary"
                        size="sm"
                        :icon="PlayIcon"
                        class="flex-1 justify-center"
                      >
                        {{ course.progress === 0 ? 'শুরু করুন' : 'চালিয়ে যান' }}
                      </PrimaryButton>
                      <PrimaryButton
                        variant="outline"
                        size="sm"
                        :icon="BookOpenIcon"
                        class="flex-1 justify-center"
                      >
                        বিস্তারিত
                      </PrimaryButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Profile Section -->
            <div v-if="activeSection === 'profile'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">প্রোফাইল সেটিংস</h2>
              
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">পূর্ণ নাম</label>
                    <input
                      v-model="profileData.name"
                      type="text"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ইমেইল</label>
                    <input
                      v-model="profileData.email"
                      type="email"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">ফোন নম্বর</label>
                    <input
                      v-model="profileData.phone"
                      type="tel"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    />
                  </div>
                  
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">বয়স</label>
                    <input
                      v-model="profileData.age"
                      type="number"
                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                    />
                  </div>
                </div>
                
                <div class="mt-6">
                  <label class="block text-sm font-medium text-gray-700 mb-2">ঠিকানা</label>
                  <textarea
                    v-model="profileData.address"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#5f5fcd] focus:border-transparent"
                  ></textarea>
                </div>

                <div class="mt-8 flex space-x-4">
                  <PrimaryButton
                    @click="updateProfile"
                    variant="primary"
                    size="md"
                    :icon="SaveIcon"
                  >
                    প্রোফাইল আপডেট করুন
                  </PrimaryButton>
                  <PrimaryButton
                    @click="goToSettings"
                    variant="outline"
                    size="md"
                    :icon="SettingsIcon"
                  >
                    অতিরিক্ত সেটিংস
                  </PrimaryButton>
                </div>
              </div>
            </div>

            <!-- Certificates Section -->
            <div v-if="activeSection === 'certificates'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">সার্টিফিকেট</h2>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div 
                  v-for="certificate in certificates" 
                  :key="certificate.id"
                  class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6"
                >
                  <div class="flex items-center space-x-4 mb-4">
                    <div class="w-16 h-16 bg-[#d4a574]/10 rounded-full flex items-center justify-center">
                      <AwardIcon class="w-8 h-8 text-[#d4a574]" />
                    </div>
                    <div>
                      <h3 class="text-lg font-semibold text-gray-900">{{ certificate.course }}</h3>
                      <p class="text-gray-600">{{ certificate.date }}</p>
                    </div>
                  </div>
                  
                  <PrimaryButton
                    variant="outline"
                    size="sm"
                    :icon="DownloadIcon"
                    class="w-full justify-center"
                  >
                    সার্টিফিকেট ডাউনলোড
                  </PrimaryButton>
                </div>
              </div>
            </div>

            <!-- Progress Section -->
            <div v-if="activeSection === 'progress'" class="space-y-8">
              <h2 class="text-2xl font-bold text-gray-900">অগ্রগতি রিপোর্ট</h2>
              
              <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                  <div class="text-center">
                    <div class="text-4xl font-bold text-[#5f5fcd] mb-2">85%</div>
                    <div class="text-gray-600">সামগ্রিক অগ্রগতি</div>
                  </div>
                  <div class="text-center">
                    <div class="text-4xl font-bold text-[#2d5a27] mb-2">24</div>
                    <div class="text-gray-600">এই সপ্তাহে পাঠ</div>
                  </div>
                  <div class="text-center">
                    <div class="text-4xl font-bold text-[#d4a574] mb-2">12</div>
                    <div class="text-gray-600">ধারাবাহিক দিন</div>
                  </div>
                </div>

                <!-- Recent Activity -->
                <h3 class="text-lg font-semibold text-gray-900 mb-4">সাম্প্রতিক কার্যক্রম</h3>
                <div class="space-y-4">
                  <div v-for="activity in recentActivities" :key="activity.id" class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-[#5f5fcd]/10 rounded-full flex items-center justify-center">
                      <CheckIcon class="w-5 h-5 text-[#5f5fcd]" />
                    </div>
                    <div class="flex-1">
                      <p class="font-medium text-gray-900">{{ activity.title }}</p>
                      <p class="text-sm text-gray-600">{{ activity.course }}</p>
                    </div>
                    <div class="text-sm text-gray-500">{{ activity.date }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  UserIcon,
  BookOpenIcon,
  PlayIcon,
  PlusIcon,
  SaveIcon,
  SettingsIcon,
  AwardIcon,
  DownloadIcon,
  CheckIcon,
  TrendingUpIcon
} from 'lucide-vue-next'

// Mock student data
const studentName = ref('আবু বকর সিদ্দিক')
const completedLessons = ref(45)
const studyHours = ref(28)

// Active section
const activeSection = ref('courses')

// Navigation items
const navigationItems = [
  { id: 'courses', name: 'আমার কোর্স', icon: BookOpenIcon },
  { id: 'profile', name: 'প্রোফাইল', icon: UserIcon },
  { id: 'progress', name: 'অগ্রগতি', icon: TrendingUpIcon },
  { id: 'certificates', name: 'সার্টিফিকেট', icon: AwardIcon }
]

// Mock enrolled courses
const enrolledCourses = ref([
  {
    id: 1,
    title: 'কুরআন তিলাওয়াত ও তাজবীদ',
    slug: 'quran-tilawat-tajwid',
    instructor: 'উস্তাদ মোহাম্মদ রহমান',
    image: 'https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=400&h=250&fit=crop',
    progress: 75
  },
  {
    id: 2,
    title: 'হাদিস ও সুন্নাহর আলোকে জীবনযাত্রা',
    slug: 'hadith-sunnah-jibon',
    instructor: 'উস্তাদ আবদুল কারিম',
    image: 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=400&h=250&fit=crop',
    progress: 30
  }
])

// Profile data
const profileData = ref({
  name: 'আবু বকর সিদ্দিক',
  email: 'abubakr@example.com',
  phone: '01712345678',
  age: 28,
  address: 'ঢাকা, বাংলাদেশ'
})

// Mock certificates
const certificates = ref([
  {
    id: 1,
    course: 'কুরআন তিলাওয়াত ও তাজবীদ',
    date: '১৫ রমজান, ১৪৪৫'
  }
])

// Recent activities
const recentActivities = ref([
  {
    id: 1,
    title: 'তাজবীদের মৌলিক নিয়ম সম্পন্ন',
    course: 'কুরআন তিলাওয়াত ও তাজবীদ',
    date: '২ ঘন্টা আগে'
  },
  {
    id: 2,
    title: 'হাদিসের পরিচিতি পাঠ শুরু',
    course: 'হাদিস ও সুন্নাহর আলোকে জীবনযাত্রা',
    date: 'গতকাল'
  }
])

// Methods
const goToLearning = (courseSlug: string) => {
  router.visit(route('frontend.learn', { course: courseSlug }))
}

const goToCourses = () => {
  router.visit(route('frontend.courses.index'))
}

const goToSettings = () => {
  router.visit(route('settings.profile'))
}

const updateProfile = () => {
  // Simulate profile update
  alert('প্রোফাইল সফলভাবে আপডেট হয়েছে!')
}
</script> 