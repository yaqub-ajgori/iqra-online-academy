<template>
  <div>
    <!-- Enhanced Install Prompt -->
    <div v-if="showInstall && !isIOS" class="fixed bottom-6 right-6 z-50 block md:hidden">
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 max-w-sm animate-slide-up">
        <div class="flex items-start space-x-3">
          <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-4 4V4" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 mb-1">অ্যাপ ইন্সটল করুন</h4>
            <p class="text-xs text-gray-600 mb-3">দ্রুত অ্যাক্সেস এবং অফলাইন সুবিধার জন্য</p>
            <div class="flex space-x-2">
              <button
                @click="installApp"
                class="flex-1 px-3 py-2 bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white text-xs font-medium rounded-lg hover:shadow-lg transition-all duration-200"
              >
                ইন্সটল
              </button>
              <button
                @click="dismiss"
                class="px-3 py-2 text-gray-500 text-xs hover:text-gray-700 transition-colors"
              >
                পরে
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Enhanced iOS Install Guide -->
    <div v-if="showInstall && isIOS" class="fixed bottom-6 right-6 z-50 block md:hidden">
      <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-4 max-w-sm animate-slide-up">
        <div class="flex items-start space-x-3">
          <div class="w-12 h-12 bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27] rounded-xl flex items-center justify-center flex-shrink-0">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
            </svg>
          </div>
          <div class="flex-1 min-w-0">
            <h4 class="text-sm font-semibold text-gray-900 mb-1">অ্যাপ ইন্সটল করুন</h4>
            <div class="text-xs text-gray-600 mb-3">
              <p class="mb-2">সাফারিতে শেয়ার বাটনে ট্যাপ করুন 👇</p>
              <p class="font-medium">"হোম স্ক্রিনে যোগ করুন" বেছে নিন</p>
            </div>
            <button
              @click="dismiss"
              class="w-full px-3 py-2 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors"
            >
              বুঝেছি
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const showInstall = ref(false)
const isIOS = ref(false)
let deferredPrompt: any = null
const sessionKey = 'pwa-install-dismissed'

function isInStandaloneMode() {
  return window.matchMedia('(display-mode: standalone)').matches || (window.navigator as any).standalone === true
}

function checkIOS() {
  const ua = window.navigator.userAgent
  return /iPhone|iPad|iPod/.test(ua) && !window.matchMedia('(display-mode: standalone)').matches
}

function handleBeforeInstallPrompt(e: Event) {
  e.preventDefault()
  deferredPrompt = e
  if (!sessionStorage.getItem(sessionKey) && !isInStandaloneMode()) {
    showInstall.value = true
  }
}

function handleAppInstalled() {
  showInstall.value = false
  deferredPrompt = null
  sessionStorage.removeItem(sessionKey)
}

function installApp() {
  if (deferredPrompt) {
    deferredPrompt.prompt()
    deferredPrompt.userChoice.then(() => {
      showInstall.value = false
      deferredPrompt = null
      sessionStorage.setItem(sessionKey, '1')
    })
  }
}

function dismiss() {
  showInstall.value = false
  sessionStorage.setItem(sessionKey, '1')
}

onMounted(() => {
  isIOS.value = checkIOS()

  // Delay showing install prompt for better UX (after user has browsed a bit)
  setTimeout(() => {
    // iOS: show guide if not installed and not dismissed
    if (isIOS.value && !isInStandaloneMode() && !sessionStorage.getItem(sessionKey)) {
      showInstall.value = true
    }
  }, 3000) // Show after 3 seconds

  // Android/Chrome: handle install prompt
  window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
  window.addEventListener('appinstalled', handleAppInstalled)

  // Hide if already installed
  if (isInStandaloneMode()) {
    showInstall.value = false
  }

  // Listen for display mode changes (user uninstalls and revisits)
  window.matchMedia('(display-mode: standalone)').addEventListener('change', (e) => {
    if (!e.matches && !sessionStorage.getItem(sessionKey)) {
      // Not in standalone, allow install again if prompt is available or on iOS
      showInstall.value = isIOS.value || !!deferredPrompt
    } else {
      showInstall.value = false
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
  window.removeEventListener('appinstalled', handleAppInstalled)
})
</script>

<style scoped>
/* Enhanced animation styles */
.animate-slide-up {
  animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style> 