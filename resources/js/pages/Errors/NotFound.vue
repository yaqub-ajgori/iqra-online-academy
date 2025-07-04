<template>
  <FrontendLayout>
    <Head title="পৃষ্ঠা পাওয়া যায়নি - ইকরা অনলাইন একাডেমি" />
    
    <!-- 404 Error Section -->
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
              ৪০৪
            </h1>
            <!-- Islamic Decorative Elements around 404 -->
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
            <span class="text-red-700 font-medium">পৃষ্ঠা পাওয়া যায়নি</span>
          </div>
          
          <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4 leading-tight">
            দুঃখিত, আপনি যে পৃষ্ঠাটি খুঁজছেন 
            <span class="text-gradient-islamic bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] bg-clip-text text-transparent">
              পাওয়া যায়নি
            </span>
          </h2>
          
          <p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
            আপনি যে পৃষ্ঠাটি খুঁজছেন তা মুছে ফেলা হয়েছে, নাম পরিবর্তন করা হয়েছে, 
            অথবা সাময়িকভাবে উপলব্ধ নেই। দয়া করে নিচের বিকল্পগুলি ব্যবহার করুন।
          </p>
        </div>

        <!-- Search Box -->
        <div class="mb-8 max-w-md mx-auto">
          <div class="relative">
            <input 
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              type="text" 
              placeholder="কোর্স বা বিষয় অনুসন্ধান করুন..."
              class="w-full px-4 py-3 pl-12 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#5f5fcd] focus:border-[#5f5fcd] transition-all duration-200 bg-white/80 backdrop-blur-sm"
            />
            <SearchIcon class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
            <button 
              @click="handleSearch"
              class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-[#5f5fcd] text-white p-2 rounded-lg hover:bg-[#4a4ab8] transition-colors duration-200"
            >
              <ArrowRightIcon class="w-4 h-4" />
            </button>
          </div>
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
            :href="route('frontend.courses.index')" 
            tag="a"
            size="lg"
            variant="outline"
            :icon="BookOpenIcon"
            class="hover:scale-105 transition-transform duration-300 min-w-[200px]"
          >
            কোর্স ব্রাউজ করুন
          </PrimaryButton>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  AlertTriangleIcon,
  HomeIcon,
  BookOpenIcon,
  SearchIcon,
  ArrowRightIcon,
  InfoIcon,
  MessageCircleIcon,
  UserIcon,
  UserPlusIcon
} from 'lucide-vue-next'

// State
const searchQuery = ref('')

// Methods
const handleSearch = () => {
  if (searchQuery.value.trim()) {
    // Redirect to courses page with search query
    router.visit(route('frontend.courses.index'), {
      data: { search: searchQuery.value.trim() }
    })
  }
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

/* Enhanced focus states for accessibility */
input:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(95, 95, 205, 0.1);
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