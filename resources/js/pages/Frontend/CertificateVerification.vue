<template>
  <FrontendLayout title="সার্টিফিকেট যাচাই - ইকরা অনলাইন একাডেমি">
    <div class="min-h-screen bg-gradient-to-br from-[#5f5fcd]/5 via-white to-[#2d5a27]/5">
      <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
          <!-- Header -->
          <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-full mb-4 shadow-islamic">
              <svg class="w-8 h-8 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">সার্টিফিকেট যাচাই</h1>
            <p class="text-gray-600">আপনার সার্টিফিকেটের সত্যতা যাচাই করুন</p>
          </div>

          <!-- Verification Form -->
          <div class="bg-white rounded-2xl shadow-islamic p-8 mb-8 border border-gray-100">
            <form @submit.prevent="verifyCode" class="space-y-6">
              <div>
                <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-2">
                  ভেরিফিকেশন কোড
                </label>
                <input
                  id="verification_code"
                  v-model="form.verification_code"
                  type="text"
                  maxlength="12"
                  placeholder="12-digit verification code"
                  class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#5f5fcd] focus:border-[#5f5fcd] text-center text-lg tracking-widest uppercase transition-all duration-200"
                  :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors.verification_code }"
                  required
                />
                <div v-if="form.errors.verification_code" class="mt-2 text-sm text-red-600">
                  {{ form.errors.verification_code }}
                </div>
                <p class="mt-2 text-sm text-gray-500">
                  ভেরিফিকেশন কোড সার্টিফিকেটের নিচের দিকে পাবেন
                </p>
              </div>

              <button
                type="submit"
                :disabled="form.processing || !form.verification_code"
                class="w-full px-6 py-3 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white rounded-xl hover:shadow-islamic-lg disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 font-medium"
              >
                <span v-if="form.processing">যাচাই করা হচ্ছে...</span>
                <span v-else>যাচাই করুন</span>
              </button>
            </form>
          </div>

          <!-- Verification Result -->
          <div v-if="certificate" class="bg-white rounded-2xl shadow-islamic p-8 border border-gray-100">
            <div class="text-center mb-6">
              <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-[#2d5a27]/10 to-[#2d5a27]/20 rounded-full mb-4 shadow-islamic">
                <svg class="w-8 h-8 text-[#2d5a27]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
              </div>
              <h2 class="text-2xl font-bold text-[#2d5a27] mb-2">বৈধ সার্টিফিকেট</h2>
              <p class="text-gray-600">এই সার্টিফিকেটটি সত্য এবং বৈধ</p>
            </div>

            <!-- Certificate Details -->
            <div class="border-t pt-6">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-1">সার্টিফিকেট নম্বর</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.certificate_number }}</p>
                </div>

                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-1">শিক্ষার্থীর নাম</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.student_name }}</p>
                </div>

                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-1">কোর্সের নাম</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.course_title }}</p>
                </div>

                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-1">সম্পন্নের তারিখ</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.completion_date }}</p>
                </div>

                <div>
                  <h3 class="text-sm font-medium text-gray-500 mb-1">ইস্যুর তারিখ</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.issue_date }}</p>
                </div>

                <div v-if="certificate.final_score">
                  <h3 class="text-sm font-medium text-gray-500 mb-1">স্কোর</h3>
                  <p class="text-lg font-semibold text-gray-800">{{ certificate.final_score }}%</p>
                </div>
              </div>

              <div v-if="certificate.instructors && certificate.instructors.length > 0" class="mt-6">
                <h3 class="text-sm font-medium text-gray-500 mb-2">শিক্ষক</h3>
                <div class="flex flex-wrap gap-2">
                  <span
                    v-for="instructor in certificate.instructors"
                    :key="instructor"
                    class="px-3 py-1 bg-gradient-to-r from-[#5f5fcd]/10 to-[#d4a574]/10 text-[#5f5fcd] rounded-full text-sm font-medium border border-[#5f5fcd]/20"
                  >
                    {{ instructor }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Actions -->
            <div class="border-t pt-6 mt-6">
              <div class="flex justify-center space-x-4">
                <button
                  @click="viewFullCertificate"
                  class="px-6 py-3 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white rounded-xl hover:shadow-islamic-lg transition-all duration-200 font-medium"
                >
                  পূর্ণ সার্টিফিকেট দেখুন
                </button>
                <button
                  @click="reset"
                  class="px-6 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-xl hover:shadow-lg transition-all duration-200 font-medium"
                >
                  নতুন যাচাই
                </button>
              </div>
            </div>
          </div>

          <!-- Instructions -->
          <div class="bg-gradient-to-r from-[#5f5fcd]/5 to-[#d4a574]/5 rounded-2xl p-6 mt-8 border border-[#5f5fcd]/10">
            <h3 class="text-lg font-semibold text-[#5f5fcd] mb-3">কীভাবে যাচাই করবেন?</h3>
            <ul class="text-gray-700 space-y-2">
              <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-[#d4a574] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                আপনার সার্টিফিকেটের নিচের দিকে 12-সংখ্যার ভেরিফিকেশন কোড খুঁজুন
              </li>
              <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-[#d4a574] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                উপরের ফর্মে কোডটি লিখুন
              </li>
              <li class="flex items-start">
                <span class="inline-block w-2 h-2 bg-[#d4a574] rounded-full mt-2 mr-3 flex-shrink-0"></span>
                "যাচাই করুন" বাটনে ক্লিক করুন
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </FrontendLayout>
</template>

<script setup>
import { reactive } from 'vue'
import { router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

const props = defineProps({
  certificate: Object,
  errors: Object,
})

const form = reactive({
  verification_code: '',
  processing: false,
  errors: props.errors || {},
})

const verifyCode = () => {
  form.processing = true
  form.errors = {}

  router.post(route('certificates.check'), {
    verification_code: form.verification_code
  }, {
    onSuccess: () => {
      form.processing = false
    },
    onError: (errors) => {
      form.errors = errors
      form.processing = false
    }
  })
}

const viewFullCertificate = () => {
  if (props.certificate) {
    window.open(route('certificates.view', form.verification_code), '_blank')
  }
}

const reset = () => {
  form.verification_code = ''
  form.errors = {}
  router.visit(route('certificates.verify'))
}
</script>