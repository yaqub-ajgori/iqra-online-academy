<template>
  <FrontendLayout title="দান করুন - ইকরা অনলাইন একাডেমি">
    <Head title="দান করুন - ইকরা অনলাইন একাডেমি" />
    <section class="py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
      <div class="absolute inset-0 pattern-grid opacity-10"></div>
      <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-[#5f5fcd]/20 to-transparent rounded-full transform -translate-x-16 -translate-y-16 animate-float"></div>
      <div class="absolute bottom-0 right-0 w-40 h-40 bg-gradient-to-tl from-[#d4a574]/20 to-transparent rounded-full transform translate-x-20 translate-y-20 animate-float-delayed"></div>
      <div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="mb-10">
          <div class="inline-flex items-center px-6 py-3 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 mb-6 hover:scale-105 transition-transform duration-300">
            <HeartIcon class="w-5 h-5 text-[#d4a574] mr-2" />
            <span class="text-white text-sm font-medium">দান করুন, সওয়াবের অংশীদার হোন</span>
          </div>
          <h2 class="text-3xl lg:text-5xl font-bold text-white mb-6 leading-tight">
            ইসলামী শিক্ষার প্রসারে <span class="text-gradient-islamic bg-gradient-to-r from-[#d4a574] to-[#5f5fcd] bg-clip-text text-transparent animate-gradient-shift">আপনার দান</span> গুরুত্বপূর্ণ
          </h2>
          <p class="text-lg lg:text-xl text-gray-300 mb-8 leading-relaxed max-w-2xl mx-auto">
            ইকরা অনলাইন একাডেমির মাধ্যমে হাজারো শিক্ষার্থী ইসলামী শিক্ষা গ্রহণ করছে। আপনার দান সরাসরি শিক্ষার্থীদের বৃত্তি, পাঠ্যপুস্তক ও প্রযুক্তি সহায়তায় ব্যবহৃত হবে।
          </p>
        </div>
        <!-- Placeholder for donation form/button -->
        <div class="flex flex-col items-center gap-6">
          <PrimaryButton size="xl" variant="primary" :icon="HeartIcon" class="min-w-[200px]">
            দান করুন
          </PrimaryButton>
          <div class="text-sm text-gray-400 mt-4">
            <span>আল্লাহ্‌ আপনার দান কবুল করুন এবং দ্বীনি শিক্ষার প্রসারে আপনাকে অংশীদার করুন।</span>
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import { HeartIcon } from 'lucide-vue-next'
</script> 