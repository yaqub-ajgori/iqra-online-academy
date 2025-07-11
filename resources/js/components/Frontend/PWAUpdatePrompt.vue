<template>
  <div
    v-if="updateAvailable"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 z-50 block"
  >
    <div class="bg-white rounded-xl shadow-2xl border border-gray-100 p-4 max-w-sm animate-slide-down">
      <div class="flex items-start space-x-3">
        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V8m0 8V8" />
          </svg>
        </div>
        <div class="flex-1 min-w-0">
          <h4 class="text-sm font-semibold text-gray-900 mb-1">নতুন আপডেট এসেছে!</h4>
          <p class="text-xs text-gray-600 mb-3">আরও ভালো অভিজ্ঞতার জন্য আপডেট করুন</p>
          <div class="flex space-x-2">
            <button
              @click="updateApp"
              :disabled="updating"
              class="flex-1 px-3 py-2 bg-gradient-to-r from-green-500 to-emerald-600 text-white text-xs font-medium rounded-lg hover:shadow-lg transition-all duration-200 disabled:opacity-50"
            >
              {{ updating ? 'আপডেট হচ্ছে...' : 'আপডেট করুন' }}
            </button>
            <button
              @click="dismissUpdate"
              class="px-3 py-2 text-gray-500 text-xs hover:text-gray-700 transition-colors"
            >
              পরে
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const updateAvailable = ref(false)
const updating = ref(false)
let updateSW: ((reloadPage?: boolean) => Promise<void>) | null = null

function handleSWUpdate(event: any) {
  if (event.detail) {
    updateSW = event.detail.updateSW
    updateAvailable.value = true
  }
}

function handleSWOfflineReady() {
  // App is ready to work offline
  console.log('App ready to work offline')
}

async function updateApp() {
  if (updateSW) {
    updating.value = true
    try {
      await updateSW(true) // This will reload the page with the new version
    } catch (error) {
      console.error('Error updating app:', error)
      updating.value = false
    }
  }
}

function dismissUpdate() {
  updateAvailable.value = false
}

onMounted(() => {
  // Listen for service worker updates
  document.addEventListener('swUpdate', handleSWUpdate)
  document.addEventListener('swOfflineReady', handleSWOfflineReady)
})

onUnmounted(() => {
  document.removeEventListener('swUpdate', handleSWUpdate)
  document.removeEventListener('swOfflineReady', handleSWOfflineReady)
})
</script>

<style scoped>
.animate-slide-down {
  animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
  from {
    opacity: 0;
    transform: translateX(-50%) translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
  }
}
</style>