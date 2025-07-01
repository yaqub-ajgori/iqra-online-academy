<template>
  <FrontendLayout>
    <Head :title="`${status} - ${title} - ইকরা অনলাইন একাডেমি`" />
    
    <!-- Error Section -->
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
      <!-- Islamic Background Pattern -->
      <div class="absolute inset-0 pattern-grid opacity-5"></div>
      
      <!-- Floating Decorative Elements -->
      <div class="absolute top-20 left-20 w-32 h-32 bg-gradient-to-br from-[#5f5fcd]/10 to-transparent rounded-full animate-float"></div>
      <div class="absolute bottom-20 right-20 w-40 h-40 bg-gradient-to-tl from-[#d4a574]/10 to-transparent rounded-full animate-float-delayed"></div>
      <div class="absolute top-1/2 left-1/4 w-24 h-24 bg-gradient-to-br from-[#2d5a27]/10 to-transparent rounded-full animate-float"></div>
      
      <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Error Number with Islamic Design -->
        <div class="mb-8">
          <div class="relative inline-block">
            <h1 class="text-8xl lg:text-9xl font-bold text-gradient-islamic bg-gradient-to-r from-[#5f5fcd] via-[#2d5a27] to-[#d4a574] bg-clip-text text-transparent animate-gradient-shift">
              {{ status }}
            </h1>
            <!-- Islamic Decorative Elements around error number -->
            <div class="absolute -top-4 -left-4 w-8 h-8 bg-gradient-to-br from-[#5f5fcd]/30 to-[#2d5a27]/30 transform rotate-45 rounded-lg"></div>
            <div class="absolute -top-4 -right-4 w-8 h-8 bg-gradient-to-br from-[#d4a574]/30 to-[#5f5fcd]/30 transform -rotate-45 rounded-lg"></div>
            <div class="absolute -bottom-4 -left-4 w-8 h-8 bg-gradient-to-br from-[#2d5a27]/30 to-[#d4a574]/30 transform -rotate-45 rounded-lg"></div>
            <div class="absolute -bottom-4 -right-4 w-8 h-8 bg-gradient-to-br from-[#5f5fcd]/30 to-[#2d5a27]/30 transform rotate-45 rounded-lg"></div>
          </div>
        </div>

        <!-- Error Message -->
        <div class="mb-8">
          <div class="inline-flex items-center px-6 py-3 rounded-full bg-red-50 border border-red-200 mb-6">
            <AlertTriangleIcon class="w-5 h-5 text-red-500 mr-2" />
            <span class="text-red-700 font-medium">{{ errorTitle }}</span>
          </div>
          
          <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
            {{ errorMessage }}
          </h2>
          
          <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
            {{ errorDescription }}
          </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
          <PrimaryButton 
            :href="route('frontend.home')" 
            tag="a"
            size="lg"
            variant="primary"
            :icon="HomeIcon"
            class="hover:scale-105 transition-transform duration-300 min-w-[200px]"
          >
            হোমপেজে ফিরে যান
          </PrimaryButton>
          
          <PrimaryButton 
            @click="reloadPage"
            size="lg"
            variant="outline"
            :icon="RefreshCwIcon"
            class="hover:scale-105 transition-transform duration-300 min-w-[200px]"
          >
            পৃষ্ঠা রিফ্রেশ করুন
          </PrimaryButton>
        </div>

        <!-- Quick Links -->
        <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-8 border border-gray-200/50 shadow-islamic">
          <h3 class="text-xl font-semibold text-gray-900 mb-6">দ্রুত লিংক</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <Link 
              :href="route('frontend.courses.index')"
              class="group p-4 rounded-xl bg-gradient-to-br from-[#5f5fcd]/5 to-[#2d5a27]/5 border border-[#5f5fcd]/10 hover:from-[#5f5fcd]/10 hover:to-[#2d5a27]/10 hover:border-[#5f5fcd]/20 transition-all duration-300 hover:scale-105"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <BookOpenIcon class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-semibold text-gray-900 group-hover:text-[#5f5fcd] transition-colors duration-200">কোর্স ব্রাউজ করুন</p>
                  <p class="text-sm text-gray-600">সমস্ত কোর্স দেখুন</p>
                </div>
              </div>
            </Link>

            <Link 
              :href="route('frontend.about')"
              class="group p-4 rounded-xl bg-gradient-to-br from-[#d4a574]/5 to-[#5f5fcd]/5 border border-[#d4a574]/10 hover:from-[#d4a574]/10 hover:to-[#5f5fcd]/10 hover:border-[#d4a574]/20 transition-all duration-300 hover:scale-105"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#d4a574] to-[#5f5fcd] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <InfoIcon class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-semibold text-gray-900 group-hover:text-[#d4a574] transition-colors duration-200">আমাদের সম্পর্কে</p>
                  <p class="text-sm text-gray-600">আমাদের মিশন ও লক্ষ্য</p>
                </div>
              </div>
            </Link>

            <Link 
              :href="route('frontend.contact')"
              class="group p-4 rounded-xl bg-gradient-to-br from-[#2d5a27]/5 to-[#d4a574]/5 border border-[#2d5a27]/10 hover:from-[#2d5a27]/10 hover:to-[#d4a574]/10 hover:border-[#2d5a27]/20 transition-all duration-300 hover:scale-105"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#2d5a27] to-[#d4a574] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <MessageCircleIcon class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-semibold text-gray-900 group-hover:text-[#2d5a27] transition-colors duration-200">যোগাযোগ করুন</p>
                  <p class="text-sm text-gray-600">সাহায্য ও সহায়তা</p>
                </div>
              </div>
            </Link>

            <Link 
              :href="route('login')"
              class="group p-4 rounded-xl bg-gradient-to-br from-[#5f5fcd]/5 to-[#d4a574]/5 border border-[#5f5fcd]/10 hover:from-[#5f5fcd]/10 hover:to-[#d4a574]/10 hover:border-[#5f5fcd]/20 transition-all duration-300 hover:scale-105"
            >
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#5f5fcd] to-[#d4a574] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <UserIcon class="w-5 h-5 text-white" />
                </div>
                <div>
                  <p class="font-semibold text-gray-900 group-hover:text-[#5f5fcd] transition-colors duration-200">লগইন করুন</p>
                  <p class="text-sm text-gray-600">আপনার অ্যাকাউন্টে প্রবেশ</p>
                </div>
              </div>
            </Link>
          </div>
        </div>

        <!-- Help Section -->
        <div class="mt-8 text-center">
          <p class="text-gray-500 text-sm">
            সমস্যা হচ্ছে? 
            <Link :href="route('frontend.contact')" class="text-[#5f5fcd] hover:text-[#4a4ab8] font-medium transition-colors duration-200">
              আমাদের সাথে যোগাযোগ করুন
            </Link>
          </p>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  AlertTriangleIcon,
  HomeIcon,
  RefreshCwIcon,
  BookOpenIcon,
  InfoIcon,
  MessageCircleIcon,
  UserIcon
} from 'lucide-vue-next'

// Props
interface Props {
  status: number
  title?: string
  message?: string
  description?: string
}

const props = withDefaults(defineProps<Props>(), {
  title: 'সার্ভার ত্রুটি',
  message: 'কিছু ভুল হয়েছে',
  description: 'দুঃখিত, সার্ভারে একটি সমস্যা হয়েছে। দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন।'
})

// Computed properties
const errorTitle = computed(() => {
  const titles: Record<number, string> = {
    500: 'সার্ভার ত্রুটি',
    503: 'সার্ভিস অপ্রাপ্য',
    419: 'সেশন মেয়াদ শেষ',
    429: 'অতিরিক্ত অনুরোধ'
  }
  return titles[props.status] || props.title
})

const errorMessage = computed(() => {
  const messages: Record<number, string> = {
    500: 'সার্ভারে একটি অভ্যন্তরীণ ত্রুটি হয়েছে',
    503: 'সার্ভিস সাময়িকভাবে অপ্রাপ্য',
    419: 'আপনার সেশন মেয়াদ শেষ হয়ে গেছে',
    429: 'অতিরিক্ত অনুরোধ পাঠানো হয়েছে'
  }
  return messages[props.status] || props.message
})

const errorDescription = computed(() => {
  const descriptions: Record<number, string> = {
    500: 'সার্ভারে একটি সমস্যা হয়েছে। আমাদের টেকনিক্যাল টিম সমস্যাটি সমাধানের চেষ্টা করছে।',
    503: 'সার্ভিস রক্ষণাবেক্ষণের জন্য সাময়িকভাবে বন্ধ রয়েছে। দয়া করে কিছুক্ষণ পরে আবার চেষ্টা করুন।',
    419: 'আপনার লগইন সেশন মেয়াদ শেষ হয়ে গেছে। দয়া করে আবার লগইন করুন।',
    429: 'অতিরিক্ত অনুরোধ পাঠানো হয়েছে। দয়া করে কিছুক্ষণ অপেক্ষা করে আবার চেষ্টা করুন।'
  }
  return descriptions[props.status] || props.description
})

// Methods
const reloadPage = () => {
  window.location.reload()
}
</script>

<style scoped>
/* Enhanced float animations */
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

/* Enhanced hover effects */
.group:hover {
  transform: translateY(-2px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
  background: #5f5fcd;
  border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
  background: #4a4ab8;
}
</style> 