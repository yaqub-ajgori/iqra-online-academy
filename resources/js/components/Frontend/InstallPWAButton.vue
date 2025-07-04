<template>
  <button
    v-if="showInstall"
    @click="installApp"
    class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white font-semibold shadow-lg flex items-center space-x-2 animate-bounce block md:hidden"
    style="box-shadow: 0 4px 24px #5f5fcd33;"
  >
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-4 4V4" />
    </svg>
    <span>অ্যাপ হিসেবে ইন্সটল করুন</span>
  </button>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const showInstall = ref(false)
let deferredPrompt: any = null

function handleBeforeInstallPrompt(e: Event) {
  e.preventDefault()
  deferredPrompt = e
  showInstall.value = true
}

function handleAppInstalled() {
  showInstall.value = false
  deferredPrompt = null
}

function installApp() {
  if (deferredPrompt) {
    deferredPrompt.prompt()
    deferredPrompt.userChoice.then(() => {
      showInstall.value = false
      deferredPrompt = null
    })
  }
}

onMounted(() => {
  window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
  window.addEventListener('appinstalled', handleAppInstalled)

  // Hide button if already installed (standalone mode)
  if (window.matchMedia('(display-mode: standalone)').matches) {
    showInstall.value = false
  }

  // Listen for display mode changes (user uninstalls and revisits)
  window.matchMedia('(display-mode: standalone)').addEventListener('change', (e) => {
    if (!e.matches) {
      // Not in standalone, allow install again if prompt is available
      showInstall.value = !!deferredPrompt
    }
  })
})

onUnmounted(() => {
  window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt)
  window.removeEventListener('appinstalled', handleAppInstalled)
})
</script> 