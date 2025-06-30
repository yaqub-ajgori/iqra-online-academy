<template>
  <FrontendLayout title="ইকরা অনলাইন একাডেমি - ইসলামিক শিক্ষায় নতুন মাত্রা">
    <Head title="হোম" />

    <!-- Clean & Elegant Hero Section -->
    <section class="relative py-20 lg:py-28 overflow-hidden">
      <!-- Simplified Gradient Background -->
      <div class="absolute inset-0 bg-gradient-to-br from-[#5f5fcd]/6 via-white to-[#2d5a27]/4"></div>
      
      <!-- Subtle Pattern Overlay -->
      <div class="absolute inset-0 opacity-[0.015]">
        <div class="absolute inset-0 bg-[#5f5fcd]" style="mask-image: radial-gradient(circle at 1px 1px, black 0.5px, transparent 0); mask-size: 20px 20px;"></div>
      </div>

      <!-- Simple Decorative Elements -->
      <div class="absolute top-24 right-8 w-64 h-64 bg-gradient-to-br from-[#5f5fcd]/8 to-[#2d5a27]/6 rounded-full blur-3xl"></div>
      <div class="absolute bottom-24 left-8 w-48 h-48 bg-gradient-to-br from-[#d4a574]/10 to-[#5f5fcd]/8 rounded-full blur-3xl"></div>

      <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
          
          <!-- Clean Hero Content -->
          <div class="text-center lg:text-left">
            <!-- Trust Badge -->
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-[#5f5fcd]/10 border border-[#5f5fcd]/20 mb-8">
              <span class="text-[#5f5fcd] text-sm font-medium">🕌 বিশ্বস্ত ইসলামিক শিক্ষা</span>
            </div>

            <!-- Hero Title -->
            <h1 class="text-4xl lg:text-6xl font-bold leading-tight mb-6">
              <span class="text-gradient-islamic">ইকরা অনলাইন একাডেমি</span>
              <br>
              <span class="text-gray-800">তে স্বাগতম</span>
            </h1>

            <!-- Clear Value Proposition -->
            <p class="text-lg lg:text-xl text-gray-600 leading-relaxed mb-8 max-w-2xl">
              কুরআন, হাদিস ও ইসলামিক জ্ঞানচর্চায় আধুনিক প্রযুক্তির সমন্বয়ে গড়ে তুলুন আপনার আধ্যাত্মিক জীবন। 
              অভিজ্ঞ শিক্ষকদের সাথে শিখুন ঘরে বসেই।
            </p>

            <!-- Simple Stats -->
            <div class="grid grid-cols-3 gap-8 mb-10">
              <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-[#5f5fcd] mb-2">১০০+</div>
                <div class="text-sm text-gray-600">কোর্স</div>
              </div>
              <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-[#2d5a27] mb-2">৫০০০+</div>
                <div class="text-sm text-gray-600">শিক্ষার্থী</div>
              </div>
              <div class="text-center">
                <div class="text-3xl lg:text-4xl font-bold text-[#d4a574] mb-2">৯৫%</div>
                <div class="text-sm text-gray-600">সন্তুষ্টি</div>
              </div>
            </div>

            <!-- Clear CTAs -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start mb-8">
              <PrimaryButton 
                :href="route('frontend.courses.index')" 
                tag="a"
                size="lg"
                variant="primary"
                :icon="BookOpenIcon"
                class="shadow-lg hover:shadow-xl transition-all duration-300"
              >
                কোর্স দেখুন
              </PrimaryButton>
              
              <PrimaryButton 
                :href="route('frontend.about')" 
                tag="a"
                size="lg"
                variant="outline"
                :icon="InfoIcon"
                class="border-gray-200 hover:border-[#5f5fcd]/30 transition-all duration-300"
              >
                কোর্স পরিচিতি
              </PrimaryButton>
            </div>

            <!-- Social Proof -->
            <div class="flex flex-wrap items-center gap-6 justify-center lg:justify-start text-sm text-gray-600">
              <div class="flex items-center space-x-2">
                <div class="flex -space-x-1">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=24&h=24&fit=crop&crop=face" alt="">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=24&h=24&fit=crop&crop=face" alt="">
                  <img class="w-6 h-6 rounded-full border border-white" src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=24&h=24&fit=crop&crop=face" alt="">
                </div>
                <span>৫০০০+ সন্তুষ্ট শিক্ষার্থী</span>
              </div>
              
              <div class="flex items-center space-x-1">
                <div class="flex space-x-1 text-[#d4a574]">
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                  <StarIcon class="w-4 h-4 fill-current" />
                </div>
                <span>৪.৯ রেটিং</span>
              </div>
            </div>
          </div>

          <!-- Clean Hero Image -->
          <div class="relative">
            <!-- Main Image -->
            <div class="relative">
              <div class="aspect-[4/3] rounded-2xl overflow-hidden shadow-2xl">
                <img 
                  src="https://images.unsplash.com/photo-1542816417-0983c9c9ad53?w=600&h=450&fit=crop" 
                  alt="ইসলামিক শিক্ষা - কুরআন ও ইসলামিক ক্যালিগ্রাফি"
                  class="w-full h-full object-cover"
                />
                
                <!-- Islamic Pattern Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-[#5f5fcd]/10 via-transparent to-[#2d5a27]/5"></div>
              </div>
              
              <!-- Simple Floating Elements -->
              <div class="absolute -top-4 -left-4 w-20 h-20 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center shadow-lg">
                <BookOpenIcon class="w-8 h-8 text-white" />
              </div>
              
              <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] rounded-xl flex items-center justify-center shadow-lg">
                <AwardIcon class="w-6 h-6 text-white" />
              </div>
            </div>

            <!-- Achievement Cards -->
            <div class="absolute top-8 -right-6 bg-white rounded-lg p-3 shadow-lg border border-gray-100">
              <div class="text-center">
                <div class="text-lg font-bold text-[#2d5a27]">৪.৯★</div>
                <div class="text-xs text-gray-500">রেটিং</div>
              </div>
            </div>
            
            <div class="absolute bottom-8 -left-6 bg-white rounded-lg p-3 shadow-lg border border-gray-100">
              <div class="text-center">
                <div class="text-sm font-bold text-[#d4a574]">২৪/৭</div>
                <div class="text-xs text-gray-500">সাপোর্ট</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Courses Section -->
    <section class="py-20 bg-gray-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="জনপ্রিয় কোর্সসমূহ"
          subtitle="আমাদের সেরা ইসলামিক কোর্সগুলো দেখুন"
          description="কুরআন, হাদিস, ফিকহ এবং ইসলামিক জীবনযাত্রার উপর বিশেষজ্ঞ শিক্ষকদের তৈরি কোর্সগুলো এখনই শুরু করুন।"
          variant="gradient"
          size="lg"
        >
          <template #cta>
            <PrimaryButton 
              :href="route('frontend.courses.index')" 
              tag="a"
              variant="accent"
              :icon="ArrowRightIcon"
              icon-position="right"
            >
              সব কোর্স দেখুন
            </PrimaryButton>
          </template>
        </SectionHeader>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <CourseCard 
            v-for="course in featuredCourses" 
            :key="course.id"
            :course="course"
            @enroll="handleCourseEnroll"
            @favorite="handleCourseFavorite"
            @preview="handleCoursePreview"
          />
        </div>
      </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <SectionHeader 
          title="কেন আমাদের বেছে নেবেন?"
          subtitle="ইসলামিক শিক্ষায় আমাদের বিশেষত্ব"
          description="অভিজ্ঞ শিক্ষক, আধুনিক প্রযুক্তি এবং ইসলামিক মূল্যবোধের সমন্বয়ে আমরা প্রদান করি সর্বোচ্চ মানের শিক্ষা।"
          variant="default"
          size="lg"
        />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <!-- Feature 1 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <BookOpenIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">অভিজ্ঞ শিক্ষকমণ্ডলী</h3>
            <p class="text-gray-600 leading-relaxed">
              দেশের সেরা আলেম ও ইসলামিক স্কলারদের তত্ত্বাবধানে তৈরি আমাদের সব কোর্স।
            </p>
          </div>

          <!-- Feature 2 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#2d5a27] to-[#d4a574] rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <SmartphoneIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">ইন্টারঅ্যাক্টিভ শিক্ষা</h3>
            <p class="text-gray-600 leading-relaxed">
              অডিও, ইন্টারঅ্যাক্টিভ অনুশীলন, কুইজ এবং লাইভ সেশনের মাধ্যমে উপভোগ্য শিক্ষার পরিবেশ।
            </p>
          </div>

          <!-- Feature 3 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <AwardIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">সার্টিফিকেট প্রদান</h3>
            <p class="text-gray-600 leading-relaxed">
              কোর্স সম্পন্ন করলে পাবেন আন্তর্জাতিক মানের সার্টিফিকেট।
            </p>
          </div>

          <!-- Feature 4 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#5f5fcd]/80 to-[#2d5a27]/80 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <ClockIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">নিজের সময়ে শিখুন</h3>
            <p class="text-gray-600 leading-relaxed">
              ২৪/৭ অ্যাক্সেস এবং নিজের সুবিধামতো সময়ে শিক্ষা গ্রহণের সুবিধা।
            </p>
          </div>

          <!-- Feature 5 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#2d5a27]/80 to-[#d4a574]/80 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <UsersIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">কমিউনিটি সাপোর্ট</h3>
            <p class="text-gray-600 leading-relaxed">
              অন্যান্য শিক্ষার্থীদের সাথে আলোচনা এবং শিক্ষকদের সরাসরি গাইডেন্স।
            </p>
          </div>

          <!-- Feature 6 -->
          <div class="group text-center p-8 rounded-2xl border border-gray-200 hover:border-[#5f5fcd]/30 hover:shadow-islamic transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-br from-[#d4a574]/80 to-[#5f5fcd]/80 rounded-2xl flex items-center justify-center mb-6 mx-auto group-hover:scale-110 transition-transform duration-300">
              <SmartphoneIcon class="w-8 h-8 text-white" />
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-4">মোবাইল অ্যাপ</h3>
            <p class="text-gray-600 leading-relaxed">
              যেকোনো জায়গা থেকে মোবাইল অ্যাপের মাধ্যমে শিক্ষা গ্রহণ করুন।
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
      <!-- Background Pattern -->
      <div class="absolute inset-0 pattern-grid opacity-10"></div>
      
      <!-- Islamic Geometric Elements -->
      <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#5f5fcd]/20 to-transparent rounded-full transform -trangray-x-16 -trangray-y-16"></div>
      <div class="absolute bottom-0 right-0 w-40 h-40 bg-gradient-to-tl from-[#d4a574]/20 to-transparent rounded-full transform trangray-x-20 trangray-y-20"></div>

      <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
          <div class="inline-flex items-center px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6">
            <span class="text-white text-sm font-medium">🌟 আজই শুরু করুন</span>
          </div>
        </div>

        <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6">
          আপনার <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent">ইসলামিক জ্ঞানের</span> যাত্রা শুরু করুন
        </h2>

        <p class="text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
          আজই নিবন্ধন করুন এবং পেয়ে যান প্রথম মাসের জন্য ৫০% ছাড়। 
          সীমিত সময়ের জন্য এই বিশেষ অফার।
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
          <PrimaryButton 
            :href="route('register')" 
            tag="a"
            size="xl"
            variant="primary"
            :icon="UserPlusIcon"
          >
            ফ্রি রেজিস্ট্রেশন
          </PrimaryButton>
          <PrimaryButton 
            :href="route('frontend.courses.index')" 
            tag="a"
            size="xl"
            variant="outline"
            class="border-white/20 text-white hover:bg-white hover:text-gray-900"
          >
            কোর্স ব্রাউজ করুন
          </PrimaryButton>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
// HomePage component - Islamic LMS frontend
import { ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import SectionHeader from '@/components/Frontend/SectionHeader.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import CourseCard from '@/components/Frontend/CourseCard.vue'
import {
  BookOpenIcon,
  InfoIcon,
  ArrowRightIcon,
  AwardIcon,
  ClockIcon,
  UsersIcon,
  SmartphoneIcon,
  UserPlusIcon,
  StarIcon
} from 'lucide-vue-next'

// Mock data for featured courses
const featuredCourses = ref([
  {
    id: 1,
    title: 'কুরআন তিলাওয়াত ও তাজবীদ',
    description: 'সহীহ উচ্চারণে কুরআন তিলাওয়াত শিখুন। তাজবীদের নিয়মকানুন সহ বিস্তারিত আলোচনা।',
    image: 'https://images.unsplash.com/photo-1544113503-7ad5ac882d5d?w=400&h=250&fit=crop',
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
  }
])

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
</script>

<style scoped>
/* Float animation for hero elements */
@keyframes float {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-20px);
  }
}

.float-animation {
  animation: float 6s ease-in-out infinite;
}

/* Enhanced gradient text animation */
@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 4s ease-in-out infinite;
}

/* Parallax effect for background elements */
@keyframes parallaxFloat {
  0%, 100% {
    transform: translateY(0px) translateX(0px);
  }
  33% {
    transform: translateY(-10px) translateX(5px);
  }
  66% {
    transform: translateY(5px) translateX(-5px);
  }
}

/* Islamic geometric animation */
@keyframes geometricRotate {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Smooth scroll behavior */
html {
  scroll-behavior: smooth;
}

/* Enhanced focus states for accessibility */
.focus-islamic:focus {
  outline: 2px solid #5f5fcd;
  outline-offset: 2px;
  border-radius: 8px;
}
</style> 
