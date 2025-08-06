<template>
  <FrontendLayout title="My Certificates">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
      <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
          <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-600 to-green-600 rounded-full mb-6">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <h1 class="text-4xl font-bold text-gray-800 mb-4">আমার সার্টিফিকেট</h1>
          <p class="text-gray-600 text-lg">আপনার অর্জিত সকল সার্টিফিকেট এখানে দেখুন</p>
        </div>

        <!-- Certificates -->
        <div v-if="certificates.length > 0" class="grid gap-6">
          <div
            v-for="certificate in certificates"
            :key="certificate.id"
            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300"
          >
            <div class="relative">
              <!-- Certificate Header -->
              <div class="bg-gradient-to-r from-blue-600 to-green-600 px-6 py-4">
                <div class="flex justify-between items-center">
                  <div>
                    <h2 class="text-xl font-bold text-white mb-1">{{ certificate.course_title }}</h2>
                    <p class="text-blue-100">সার্টিফিকেট নং: {{ certificate.certificate_number }}</p>
                  </div>
                  <div class="text-right">
                    <div class="bg-white bg-opacity-20 rounded-lg px-3 py-1">
                      <div class="text-white font-semibold">{{ certificate.final_score }}%</div>
                      <div class="text-blue-100 text-sm">স্কোর</div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Certificate Body -->
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                  <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ certificate.completion_date }}</div>
                    <div class="text-sm text-gray-500">সম্পন্নের তারিখ</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-gray-800">{{ certificate.issue_date }}</div>
                    <div class="text-sm text-gray-500">ইস্যুর তারিখ</div>
                  </div>
                  <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600 font-mono">{{ certificate.verification_code }}</div>
                    <div class="text-sm text-gray-500">ভেরিফিকেশন কোড</div>
                  </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-3">
                  <button
                    @click="downloadCertificate(certificate)"
                    class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    ডাউনলোড করুন
                  </button>
                  
                  <button
                    @click="viewCertificate(certificate)"
                    class="flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    দেখুন
                  </button>
                  
                  <button
                    @click="shareCertificate(certificate)"
                    class="flex items-center justify-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    শেয়ার করুন
                  </button>
                  
                  <button
                    @click="verifyCertificate(certificate)"
                    class="flex items-center justify-center px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors"
                  >
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    যাচাই করুন
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12">
          <div class="inline-flex items-center justify-center w-24 h-24 bg-gray-100 rounded-full mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
          </div>
          <h2 class="text-2xl font-bold text-gray-600 mb-4">এখনো কোনো সার্টিফিকেট নেই</h2>
          <p class="text-gray-500 mb-8 max-w-md mx-auto">
            কোর্স সম্পন্ন করুন এবং কুইজে পাস করুন সার্টিফিকেট পাওয়ার জন্য
          </p>
          <button
            @click="goToCourses"
            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
          >
            কোর্স দেখুন
          </button>
        </div>

        <!-- Share Modal -->
        <div v-if="showShareModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
          <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">সার্টিফিকেট শেয়ার করুন</h3>
            <p class="text-gray-600 mb-4">
              আপনার সার্টিফিকেটের লিংক কপি করুন:
            </p>
            <div class="flex items-center border rounded-lg mb-4">
              <input
                ref="shareLink"
                :value="shareUrl"
                readonly
                class="flex-1 px-3 py-2 text-sm bg-gray-50 border-0 focus:ring-0"
              />
              <button
                @click="copyShareLink"
                class="px-4 py-2 text-blue-600 hover:bg-blue-50 transition-colors"
              >
                কপি
              </button>
            </div>
            <div class="flex justify-end space-x-3">
              <button
                @click="showShareModal = false"
                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
              >
                বন্ধ করুন
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </FrontendLayout>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import FrontendLayout from '@/layouts/FrontendLayout.vue'

const props = defineProps({
  certificates: Array,
})

const showShareModal = ref(false)
const shareUrl = ref('')
const shareLink = ref(null)

const downloadCertificate = (certificate) => {
  window.open(certificate.download_url, '_blank')
}

const viewCertificate = (certificate) => {
  window.open(route('certificates.view', certificate.verification_code), '_blank')
}

const shareCertificate = (certificate) => {
  shareUrl.value = route('certificates.view', certificate.verification_code)
  showShareModal.value = true
}

const verifyCertificate = (certificate) => {
  router.visit(route('certificates.verify') + '?code=' + certificate.verification_code)
}

const goToCourses = () => {
  router.visit(route('frontend.courses.index'))
}

const copyShareLink = async () => {
  try {
    await navigator.clipboard.writeText(shareUrl.value)
    // You could add a toast notification here
    alert('লিংক কপি হয়েছে!')
  } catch (err) {
    // Fallback for older browsers
    shareLink.value?.select()
    document.execCommand('copy')
    alert('লিংক কপি হয়েছে!')
  }
}
</script>