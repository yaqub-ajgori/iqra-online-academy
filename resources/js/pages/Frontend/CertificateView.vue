<template>
  <FrontendLayout :title="`Certificate - ${certificate.certificate_number}`" :show-breadcrumb="false">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-green-50">
      <div class="container mx-auto px-4 py-8">
        <!-- Certificate Display -->
        <div class="max-w-4xl mx-auto">
          <!-- Digital Certificate -->
          <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8">
            <!-- Certificate Header -->
            <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-green-600 px-8 py-6">
              <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                  <span class="text-3xl font-bold text-blue-600">ই</span>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">ইকরা অনলাইন একাডেমি</h1>
                <p class="text-blue-100 text-lg">Iqra Online Academy</p>
              </div>
            </div>

            <!-- Certificate Body -->
            <div class="px-8 py-12 relative">
              <!-- Decorative elements -->
              <div class="absolute top-8 left-8 w-16 h-16 border-4 border-blue-200 rounded-full opacity-20"></div>
              <div class="absolute bottom-8 right-8 w-20 h-20 border-4 border-green-200 rounded-full opacity-20"></div>
              <div class="absolute top-1/2 left-4 transform -translate-y-1/2 w-24 h-1 bg-gradient-to-r from-blue-300 to-transparent opacity-30"></div>
              <div class="absolute top-1/2 right-4 transform -translate-y-1/2 w-24 h-1 bg-gradient-to-l from-green-300 to-transparent opacity-30"></div>

              <div class="text-center relative z-10">
                <!-- Certificate Title -->
                <div class="mb-8">
                  <h2 class="text-4xl font-bold text-gray-800 mb-4">সনদপত্র</h2>
                  <h3 class="text-2xl text-gray-600">Certificate of Completion</h3>
                </div>

                <!-- Student Name -->
                <div class="mb-8">
                  <p class="text-lg text-gray-600 mb-2">এই সনদপত্র প্রদান করা হলো</p>
                  <p class="text-lg text-gray-600 mb-2">This certificate is awarded to</p>
                  <h3 class="text-4xl font-bold text-blue-600 border-b-4 border-blue-200 inline-block pb-2">
                    {{ certificate.student_name }}
                  </h3>
                </div>

                <!-- Course Information -->
                <div class="mb-8">
                  <p class="text-lg text-gray-600 mb-4">সফলভাবে সম্পন্ন করার জন্য</p>
                  <p class="text-lg text-gray-600 mb-4">for successfully completing</p>
                  <div class="bg-gray-50 rounded-lg p-6 mb-4">
                    <h4 class="text-2xl font-bold text-gray-800 mb-2">{{ certificate.course_title }}</h4>
                    <p v-if="certificate.course_description" class="text-gray-600">
                      {{ certificate.course_description }}
                    </p>
                  </div>
                </div>

                <!-- Score (if available) -->
                <div v-if="certificate.final_score" class="mb-8">
                  <div class="inline-block bg-green-100 rounded-lg px-6 py-3">
                    <span class="text-sm text-gray-600">Final Score:</span>
                    <span class="text-2xl font-bold text-green-600 ml-2">{{ certificate.final_score }}%</span>
                  </div>
                </div>

                <!-- Instructors -->
                <div v-if="certificate.instructors && certificate.instructors.length > 0" class="mb-8">
                  <p class="text-gray-600 mb-2">শিক্ষক / Instructor(s):</p>
                  <div class="flex flex-wrap justify-center gap-2">
                    <span
                      v-for="instructor in certificate.instructors"
                      :key="instructor"
                      class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full font-medium"
                    >
                      {{ instructor }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Certificate Footer -->
            <div class="bg-gray-50 px-8 py-6">
              <div class="flex justify-between items-center text-sm">
                <div class="space-y-1">
                  <div><strong>Certificate No:</strong> {{ certificate.certificate_number }}</div>
                  <div><strong>Verification Code:</strong> {{ certificate.verification_code }}</div>
                  <div><strong>Issue Date:</strong> {{ certificate.issue_date }}</div>
                </div>
                <div class="text-right space-y-1">
                  <div><strong>Completion Date:</strong> {{ certificate.completion_date }}</div>
                  <div class="text-xs text-gray-500">
                    Verify online: {{ $page.url }}/certificates/verify
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="text-center space-y-4">
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <button
                @click="printCertificate"
                class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center justify-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Certificate
              </button>
              
              <button
                @click="shareCertificate"
                class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center justify-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                </svg>
                Share Certificate
              </button>
              
              <button
                @click="verifyAnother"
                class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center justify-center"
              >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Verify Another
              </button>
            </div>
            
            <p class="text-sm text-gray-500 max-w-md mx-auto">
              This is a digitally verified certificate. You can verify its authenticity using the verification code above.
            </p>
          </div>

          <!-- Share Modal -->
          <div v-if="showShareModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
              <h3 class="text-xl font-bold mb-4">Share Certificate</h3>
              <p class="text-gray-600 mb-4">Copy the certificate link to share:</p>
              <div class="flex items-center border rounded-lg mb-4">
                <input
                  ref="shareLink"
                  :value="shareUrl"
                  readonly
                  class="flex-1 px-3 py-2 text-sm bg-gray-50 border-0 focus:ring-0"
                />
                <button
                  @click="copyLink"
                  class="px-4 py-2 text-blue-600 hover:bg-blue-50 transition-colors"
                >
                  Copy
                </button>
              </div>
              <div class="flex justify-end">
                <button
                  @click="showShareModal = false"
                  class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50"
                >
                  Close
                </button>
              </div>
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
  certificate: Object,
})

const showShareModal = ref(false)
const shareUrl = ref(window.location.href)
const shareLink = ref(null)

const printCertificate = () => {
  window.print()
}

const shareCertificate = () => {
  showShareModal.value = true
}

const verifyAnother = () => {
  router.visit(route('certificates.verify'))
}

const copyLink = async () => {
  try {
    await navigator.clipboard.writeText(shareUrl.value)
    alert('Link copied to clipboard!')
  } catch (err) {
    // Fallback for older browsers
    shareLink.value?.select()
    document.execCommand('copy')
    alert('Link copied to clipboard!')
  }
}
</script>

<style>
@media print {
  body * {
    visibility: hidden;
  }
  
  .certificate-container,
  .certificate-container * {
    visibility: visible;
  }
  
  .certificate-container {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
  
  /* Hide navigation and other elements when printing */
  nav,
  .actions,
  button {
    display: none !important;
  }
}
</style>