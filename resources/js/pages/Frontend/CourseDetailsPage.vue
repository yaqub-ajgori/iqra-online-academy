<template>
  <FrontendLayout title="কোর্সের বিস্তারিত - ইকরা অনলাইন একাডেমি">
    <Head :title="course.title" />

    <!-- Course Hero Section -->
    <section class="relative py-16 bg-gradient-to-br from-[#5f5fcd]/10 via-white to-[#2d5a27]/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
          
          <!-- Course Content -->
          <div class="lg:col-span-2">
            <!-- Breadcrumb -->
            <nav class="mb-8">
              <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><Link :href="route('frontend.home')" class="hover:text-[#5f5fcd]">হোম</Link></li>
                <li><ChevronRightIcon class="w-4 h-4 text-gray-400" /></li>
                <li><Link :href="route('frontend.courses.index')" class="hover:text-[#5f5fcd]">কোর্স</Link></li>
                <li><ChevronRightIcon class="w-4 h-4 text-gray-400" /></li>
                <li class="text-gray-900">{{ course.title }}</li>
              </ol>
            </nav>

            <!-- Course Header -->
            <div class="mb-8">
              <div class="flex items-center gap-4 mb-4">
                <span class="px-3 py-1 bg-[#5f5fcd]/10 text-[#5f5fcd] rounded-full text-sm font-medium">
                  {{ course.category }}
                </span>
                <span class="px-3 py-1 bg-[#2d5a27]/10 text-[#2d5a27] rounded-full text-sm font-medium">
                  {{ getLevelText(course.level) }}
                </span>
              </div>
              
              <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
                {{ course.title }}
              </h1>
              
              <p class="text-lg text-gray-600 leading-relaxed mb-6">
                {{ course.description }}
              </p>

              <!-- Course Stats -->
              <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                  <UsersIcon class="w-5 h-5 text-[#5f5fcd]" />
                  <span>{{ course.students_count }}+ শিক্ষার্থী</span>
                </div>
                <div class="flex items-center space-x-2">
                  <ClockIcon class="w-5 h-5 text-[#2d5a27]" />
                  <span>{{ course.duration }}</span>
                </div>
                <div class="flex items-center space-x-2">
                  <div class="flex space-x-1 text-[#d4a574]">
                    <StarIcon class="w-4 h-4 fill-current" />
                    <StarIcon class="w-4 h-4 fill-current" />
                    <StarIcon class="w-4 h-4 fill-current" />
                    <StarIcon class="w-4 h-4 fill-current" />
                    <StarIcon class="w-4 h-4 fill-current" />
                  </div>
                  <span>{{ course.rating }} ({{ course.reviews_count }} রিভিউ)</span>
                </div>
              </div>
            </div>

            <!-- Course Video Preview -->
            <div class="mb-12">
              <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl relative">
                <!-- Video iframe without autoplay -->
                <iframe 
                  :src="course.preview_video_url" 
                  class="w-full h-full" 
                  frameborder="0" 
                  allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                  allowfullscreen
                ></iframe>
              </div>
            </div>

            <!-- Course Content Tabs -->
            <div class="mb-12">
              <div class="border-b border-gray-200 mb-8">
                <nav class="flex space-x-8">
                  <button 
                    v-for="tab in tabs" 
                    :key="tab.id"
                    @click="activeTab = tab.id"
                    :class="[
                      'py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200',
                      activeTab === tab.id 
                        ? 'border-[#5f5fcd] text-[#5f5fcd]' 
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                    ]"
                  >
                    {{ tab.name }}
                  </button>
                </nav>
              </div>

              <!-- Tab Content -->
              <div v-if="activeTab === 'overview'" class="prose prose-slate max-w-none">
                <h3>কোর্স সম্পর্কে</h3>
                <p>{{ course.full_description }}</p>
                
                <h4>আপনি যা শিখবেন:</h4>
                <ul>
                  <li v-for="outcome in course.learning_outcomes" :key="outcome">{{ outcome }}</li>
                </ul>

                <h4>কোর্সের প্রয়োজনীয়তা:</h4>
                <ul>
                  <li v-for="requirement in course.requirements" :key="requirement">{{ requirement }}</li>
                </ul>
              </div>

              <div v-if="activeTab === 'curriculum'" class="space-y-4">
                <div v-for="module in course.modules" :key="module.id" class="border border-gray-200 rounded-lg overflow-hidden">
                  <button 
                    @click="toggleModule(module.id)"
                    class="w-full px-6 py-4 text-left bg-gray-50 hover:bg-gray-100 transition-colors duration-200 flex items-center justify-between"
                  >
                    <div>
                      <h4 class="font-semibold text-gray-900">{{ module.title }}</h4>
                      <p class="text-sm text-gray-600">{{ module.lessons_count }} টি পাঠ • {{ module.duration }}</p>
                    </div>
                    <ChevronDownIcon 
                      :class="[
                        'w-5 h-5 text-gray-500 transition-transform duration-200',
                        expandedModules.includes(module.id) ? 'rotate-180' : ''
                      ]" 
                    />
                  </button>
                  
                  <div v-show="expandedModules.includes(module.id)" class="px-6 py-4 border-t border-gray-200">
                    <div v-for="lesson in module.lessons" :key="lesson.id" class="flex items-center justify-between py-2">
                      <div class="flex items-center space-x-3">
                        <ImageIcon class="w-5 h-5 text-[#5f5fcd]" />
                        <span class="text-gray-700">{{ lesson.title }}</span>
                      </div>
                      <span class="text-sm text-gray-500">{{ lesson.duration }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="activeTab === 'instructor'" class="bg-gray-50 rounded-2xl p-8">
                <div class="flex items-start space-x-6">
                  <img 
                    :src="course.instructor.avatar" 
                    :alt="course.instructor.name"
                    class="w-24 h-24 rounded-full object-cover shadow-lg"
                  />
                  <div class="flex-1">
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ course.instructor.name }}</h3>
                    <p class="text-lg text-[#5f5fcd] mb-4">{{ course.instructor.title }}</p>
                    <p class="text-gray-600 leading-relaxed mb-6">{{ course.instructor.bio }}</p>
                    
                    <div class="grid grid-cols-3 gap-6 text-center">
                      <div>
                        <div class="text-2xl font-bold text-[#5f5fcd]">{{ course.instructor.courses_count }}</div>
                        <div class="text-sm text-gray-600">কোর্স</div>
                      </div>
                      <div>
                        <div class="text-2xl font-bold text-[#2d5a27]">{{ course.instructor.students_count }}</div>
                        <div class="text-sm text-gray-600">শিক্ষার্থী</div>
                      </div>
                      <div>
                        <div class="text-2xl font-bold text-[#d4a574]">{{ course.instructor.rating }}</div>
                        <div class="text-sm text-gray-600">রেটিং</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div v-if="activeTab === 'reviews'" class="space-y-6">
                <div v-for="review in course.reviews" :key="review.id" class="border-b border-gray-200 pb-6">
                  <div class="flex items-start space-x-4">
                    <img 
                      :src="review.student.avatar" 
                      :alt="review.student.name"
                      class="w-12 h-12 rounded-full object-cover"
                    />
                    <div class="flex-1">
                      <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-900">{{ review.student.name }}</h4>
                        <div class="flex items-center space-x-1">
                          <div class="flex space-x-1 text-[#d4a574]">
                            <StarIcon v-for="i in review.rating" :key="i" class="w-4 h-4 fill-current" />
                          </div>
                          <span class="text-sm text-gray-500 ml-2">{{ review.date }}</span>
                        </div>
                      </div>
                      <p class="text-gray-600 leading-relaxed">{{ review.comment }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Enrollment Card -->
          <div class="lg:col-span-1">
            <div class="sticky top-8">
              <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
                
                <!-- Price Section -->
                <div class="p-8 text-center border-b border-gray-100">
                  <div v-if="course.price === 0" class="text-3xl font-bold text-[#2d5a27] mb-2">
                    ফ্রি
                  </div>
                  <div v-else class="text-3xl font-bold text-gray-900 mb-2">
                    ৳{{ course.price.toLocaleString() }}
                  </div>
                  <p class="text-sm text-gray-600">লাইফটাইম অ্যাক্সেস</p>
                </div>

                <!-- Enrollment Actions -->
                <div class="p-8 space-y-4">
                  <PrimaryButton 
                    v-if="!isEnrolled"
                    @click="handleEnroll"
                    size="lg"
                    variant="primary"
                    :icon="BookOpenIcon"
                    class="w-full justify-center"
                  >
                    {{ course.price === 0 ? 'ফ্রি এনরোল করুন' : 'এখনই কিনুন' }}
                  </PrimaryButton>

                  <PrimaryButton 
                    v-else
                    @click="handleContinue"
                    size="lg"
                    variant="accent"
                    :icon="ImageIcon"
                    class="w-full justify-center"
                  >
                    কোর্স চালিয়ে যান
                  </PrimaryButton>


                </div>

                <!-- Course Features -->
                <div class="p-8 bg-gray-50 border-t border-gray-100">
                  <h4 class="font-semibold text-gray-900 mb-4">এই কোর্সে যা পাবেন:</h4>
                  <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>{{ course.total_lessons }} টি লেসন</span>
                    </li>
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>{{ course.total_duration }} ঘণ্টা কন্টেন্ট</span>
                    </li>
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>ডাউনলোডযোগ্য রিসোর্স</span>
                    </li>
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>সার্টিফিকেট অফ কমপ্লিশন</span>
                    </li>
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>লাইফটাইম অ্যাক্সেস</span>
                    </li>
                    <li class="flex items-center space-x-3">
                      <CheckIcon class="w-4 h-4 text-[#2d5a27]" />
                      <span>কমিউনিটি সাপোর্ট</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Related Courses -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="সম্পর্কিত কোর্সসমূহ"
          subtitle="আরও জানুন ইসলামিক শিক্ষা নিয়ে"
          description="এই কোর্সের সাথে সম্পর্কিত আরও কিছু কোর্স যা আপনার জ্ঞান বৃদ্ধিতে সহায়ক হবে।"
          variant="default"
          size="md"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <CourseCard 
            v-for="relatedCourse in relatedCourses" 
            :key="relatedCourse.id"
            :course="relatedCourse"
            @enroll="handleCourseEnroll"
            @favorite="handleCourseFavorite"
            @preview="handleCoursePreview"
          />
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Link, Head, router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import SectionHeader from '@/components/Frontend/SectionHeader.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import {
  BookOpenIcon,
  UsersIcon,
  ClockIcon,
  StarIcon,
  ChevronRightIcon,
  ChevronDownIcon,
  ImageIcon,

  CheckIcon
} from 'lucide-vue-next'

// Mock course data - in real app this would come from props
const course = ref({
  id: 1,
  title: 'কুরআন তিলাওয়াত ও তাজবীদ',
  slug: 'quran-tilawat-tajwid',
  description: 'সহীহ উচ্চারণে কুরআন তিলাওয়াত শিখুন। তাজবীদের নিয়মকানুন সহ বিস্তারিত আলোচনা।',
  full_description: 'এই কোর্সে আপনি কুরআন তিলাওয়াতের সঠিক পদ্ধতি এবং তাজবীদের সমস্ত নিয়ম-কানুন শিখবেন। অভিজ্ঞ শিক্ষকদের তত্ত্বাবধানে ধাপে ধাপে আরবি হরফের উচ্চারণ, তাজবীদের বিধি-বিধান এবং সুন্দর তিলাওয়াতের কৌশল আয়ত্ত করুন।',
  image: 'https://images.unsplash.com/photo-1544113503-7ad5ac882d5d?w=800&h=450&fit=crop',
  category: 'কুরআন',
  level: 'beginner' as const,
  price: 1500,
  duration: '২ মাস',
  students_count: 1250,
  rating: 4.9,
  reviews_count: 98,
  preview_video_url: 'https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1',
  total_lessons: 45,
  total_duration: 12,
  learning_outcomes: [
    'সঠিক উচ্চারণে কুরআন তিলাওয়াত করতে পারবেন',
    'তাজবীদের সমস্ত নিয়ম-কানুন জানবেন',
    'আরবি হরফের সঠিক উচ্চারণ শিখবেন',
    'কুরআনের অর্থ বুঝে তিলাওয়াত করতে পারবেন'
  ],
  requirements: [
    'আরবি পড়তে পারার প্রাথমিক দক্ষতা',
    'ইন্টারনেট সংযোগ এবং স্মার্টফোন/কম্পিউটার',
    'প্রতিদিন অন্তত ৩০ মিনিট অনুশীলনের সময়'
  ],
  instructor: {
    name: 'উস্তাদ মোহাম্মদ রহমান',
    title: 'কুরআন ও তাজবীদ বিশেষজ্ঞ',
    avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=120&h=120&fit=crop&crop=face',
    bio: 'আল-আযহার বিশ্ববিদ্যালয় থেকে কুরআন ও তাজবীদে স্নাতকোত্তর। ১৫ বছরের অভিজ্ঞতা সহ দেশের একজন প্রখ্যাত কারী ও তাজবীদ শিক্ষক।',
    courses_count: 12,
    students_count: 5420,
    rating: 4.9
  },
  modules: [
    {
      id: 1,
      title: 'আরবি হরফ ও উচ্চারণ',
      lessons_count: 8,
      duration: '২ ঘণ্টা',
      lessons: [
        { id: 1, title: 'আরবি বর্ণমালা পরিচিতি', duration: '১৫ মিনিট' },
        { id: 2, title: 'হরকত ও তানভীন', duration: '২০ মিনিট' },
        { id: 3, title: 'যের, যবর, পেশ', duration: '১৮ মিনিট' }
      ]
    },
    {
      id: 2,
      title: 'তাজবীদের মৌলিক নিয়ম',
      lessons_count: 12,
      duration: '৩.৫ ঘণ্টা',
      lessons: [
        { id: 4, title: 'তাজবীদ পরিচিতি', duration: '২৫ মিনিট' },
        { id: 5, title: 'মদ্দের প্রকারভেদ', duration: '৩০ মিনিট' },
        { id: 6, title: 'গুন্নাহর নিয়ম', duration: '২২ মিনিট' }
      ]
    }
  ],
  reviews: [
    {
      id: 1,
      student: {
        name: 'আবু বকর সিদ্দিক',
        avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=48&h=48&fit=crop&crop=face'
      },
      rating: 5,
      date: '২ সপ্তাহ আগে',
      comment: 'অসাধারণ কোর্স। উস্তাদের শেখানোর পদ্ধতি খুবই সহজ এবং কার্যকর। কুরআন তিলাওয়াতে অনেক উন্নতি হয়েছে।'
    },
    {
      id: 2,
      student: {
        name: 'ফাতিমা খাতুন',
        avatar: 'https://images.unsplash.com/photo-1494790108755-2616b0867b26?w=48&h=48&fit=crop&crop=face'
      },
      rating: 5,
      date: '১ মাস আগে',
      comment: 'তাজবীদের নিয়মগুলো এত সুন্দরভাবে বুঝিয়েছেন যে মনে রাখা খুবই সহজ হয়েছে। ধন্যবাদ উস্তাদকে।'
    }
  ]
})

const relatedCourses = ref([
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
  }
])

// State
const activeTab = ref('overview')
const expandedModules = ref<number[]>([])
const isEnrolled = ref(false)

// Tabs
const tabs = [
  { id: 'overview', name: 'কোর্স সম্পর্কে' },
  { id: 'curriculum', name: 'কারিকুলাম' },
  { id: 'instructor', name: 'শিক্ষক' },
  { id: 'reviews', name: 'রিভিউ' }
]

// Methods
const getLevelText = (level: string) => {
  const levels = {
    beginner: 'শুরুর স্তর',
    intermediate: 'মধ্যম স্তর',
    advanced: 'উন্নত স্তর'
  }
  return levels[level as keyof typeof levels] || level
}

const toggleModule = (moduleId: number) => {
  if (expandedModules.value.includes(moduleId)) {
    expandedModules.value = expandedModules.value.filter(id => id !== moduleId)
  } else {
    expandedModules.value.push(moduleId)
  }
}

const handleEnroll = () => {
  if (course.value.price === 0) {
    console.log('Free enrollment')
    isEnrolled.value = true
  } else {
    console.log('Redirecting to payment')
    // Redirect to payment page with course information
    router.visit(route('frontend.payment.checkout', { course: course.value.slug }))
  }
}

const handleContinue = () => {
  console.log('Continuing course')
  // In real app, redirect to learning route
}



const handleCourseEnroll = (courseData: any) => {
  console.log('Enrolling in related course:', courseData.title)
}

const handleCourseFavorite = (courseData: any, favorite: boolean) => {
  console.log('Related course favorite toggled:', courseData.title, favorite)
}

const handleCoursePreview = (courseData: any) => {
  console.log('Opening related course preview:', courseData.title)
}
</script>

<style scoped>
.prose {
  color: #6b7280;
}

.prose h3 {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 1rem;
  margin-top: 2rem;
}

.prose h4 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #111827;
  margin-bottom: 0.75rem;
  margin-top: 1.5rem;
}

.prose p {
  margin-bottom: 1rem;
  line-height: 1.625;
}

.prose ul {
  margin-bottom: 1.5rem;
}

.prose li {
  display: flex;
  align-items: flex-start;
  margin-bottom: 0.5rem;
}

.prose li::before {
  content: '•';
  color: #5f5fcd;
  font-weight: bold;
  margin-right: 0.5rem;
  margin-top: 0.25rem;
}
</style>
