<template>
  <div>
    <!-- Android/Chrome Install Button -->
    <button
      v-if="showInstall && !isIOS"
      @click="installApp"
      class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-full bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white font-semibold shadow-lg flex items-center space-x-2 animate-bounce block md:hidden"
      style="box-shadow: 0 4px 24px #5f5fcd33;"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-4 4V4" />
      </svg>
      <span>অ্যাপ হিসেবে ইন্সটল করুন</span>
    </button>

    <!-- iOS Install Guide -->
    <div
      v-if="showInstall && isIOS"
      class="fixed bottom-6 right-6 z-50 px-5 py-3 rounded-xl bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white font-semibold shadow-lg flex items-center space-x-2 block md:hidden animate-bounce"
      style="box-shadow: 0 4px 24px #5f5fcd33;"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-4 4V4" />
      </svg>
      <span>iOS-এ ইন্সটল করতে শেয়ার বাটনে ট্যাপ করে "Home Screen-এ যোগ করুন" বেছে নিন</span>
      <button @click="dismiss" class="ml-2 text-xs bg-white/20 rounded px-2 py-1">বন্ধ করুন</button>
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

  // iOS: show guide if not installed and not dismissed
  if (isIOS.value && !isInStandaloneMode() && !sessionStorage.getItem(sessionKey)) {
    showInstall.value = true
  }

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