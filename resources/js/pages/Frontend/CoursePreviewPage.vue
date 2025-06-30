<template>
  <FrontendLayout title="কোর্স প্রিভিউ - ইকরা অনলাইন একাডেমি">
    <Head title="কোর্স প্রিভিউ" />

    <!-- Preview Header -->
    <section class="py-12 bg-gradient-to-br from-[#5f5fcd]/10 via-white to-[#2d5a27]/5">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
          <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
            কোর্স প্রিভিউ
          </h1>
          <p class="text-lg text-gray-600 mb-8">
            কোর্সটি কেমন তা দেখে নিন
          </p>

          <!-- Back Button -->
          <PrimaryButton
            @click="goBack"
            variant="outline"
            size="md"
            :icon="ArrowLeftIcon"
          >
            পূর্ববর্তী পাতায় ফিরে যান
          </PrimaryButton>
        </div>
      </div>
    </section>

    <!-- Preview Content -->
    <section class="py-16 bg-white">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-100 rounded-2xl p-8 text-center">
          <div class="w-24 h-24 bg-[#5f5fcd]/10 rounded-full flex items-center justify-center mx-auto mb-6">
            <PlayIcon class="w-12 h-12 text-[#5f5fcd]" />
          </div>
          
          <h2 class="text-2xl font-bold text-gray-900 mb-4">
            প্রিভিউ ভিডিও
          </h2>
          
          <p class="text-gray-600 mb-8">
            এখানে কোর্সের প্রিভিউ ভিডিও দেখানো হবে
          </p>

          <!-- Action Buttons -->
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <PrimaryButton
              @click="goToCourseDetails"
              variant="primary"
              size="lg"
              :icon="BookOpenIcon"
            >
              কোর্স বিস্তারিত দেখুন
            </PrimaryButton>
            
            <PrimaryButton
              @click="goToEnrollment"
              variant="accent"
              size="lg"
              :icon="CreditCardIcon"
            >
              এখনই এনরোল করুন
            </PrimaryButton>
          </div>
        </div>
      </div>
    </section>
  </FrontendLayout>
</template>

<script setup lang="ts">
import { router, Head } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import {
  ArrowLeftIcon,
  PlayIcon,
  BookOpenIcon,
  CreditCardIcon
} from 'lucide-vue-next'

// Methods for navigation
const goBack = () => {
  window.history.back()
}

const goToCourseDetails = () => {
  router.visit(route('frontend.courses.show', { course: 'quran-tilawat-tajwid' }))
}

const goToEnrollment = () => {
  router.visit(route('frontend.payment.checkout', { course: 'quran-tilawat-tajwid' }))
}
</script> 