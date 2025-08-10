<template>
    <div>
        <!-- Enhanced Install Prompt for Android -->
        <div v-if="showInstall && !isIOS" class="fixed bottom-0 left-0 right-0 z-50 block">
            <div class="animate-slide-up bg-white p-4 shadow-2xl">
                <div class="mx-auto max-w-sm">
                    <div class="flex items-start space-x-3">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m0 0l-4-4m4 4l4-4m-4 4V4" />
                            </svg>
                        </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="mb-1 text-sm font-semibold text-gray-900">অ্যাপ ইন্সটল করুন</h4>
                        <p class="mb-3 text-xs text-gray-600">দ্রুত অ্যাক্সেস এবং অফলাইন সুবিধার জন্য</p>
                        <div class="flex space-x-2">
                            <button
                                @click="installApp"
                                class="flex-1 rounded-lg bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] px-3 py-2 text-xs font-medium text-white transition-all duration-200 hover:shadow-lg"
                            >
                                ইন্সটল
                            </button>
                            <button @click="dismiss" class="px-3 py-2 text-xs text-gray-500 transition-colors hover:text-gray-700">পরে</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced iOS Install Guide -->
        <div v-if="showInstall && isIOS" class="fixed bottom-0 left-0 right-0 z-50 block">
            <div class="animate-slide-up bg-white p-4 shadow-2xl">
                <div class="mx-auto max-w-sm">
                    <div class="flex items-start space-x-3">
                        <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-[#5f5fcd] to-[#2d5a27]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
                            />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h4 class="mb-1 text-sm font-semibold text-gray-900">অ্যাপ ইন্সটল করুন</h4>
                        <div class="mb-3 text-xs text-gray-600">
                            <p class="mb-2">সাফারিতে শেয়ার বাটনে ট্যাপ করুন 👇</p>
                            <p class="font-medium">"হোম স্ক্রিনে যোগ করুন" বেছে নিন</p>
                        </div>
                        <button
                            @click="dismiss"
                            class="w-full rounded-lg bg-gray-100 px-3 py-2 text-xs font-medium text-gray-700 transition-colors hover:bg-gray-200"
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
import { onMounted, onUnmounted, ref } from 'vue';

const showInstall = ref(false);
const isIOS = ref(false);
let deferredPrompt: any = null;
const sessionKey = 'pwa-install-dismissed';

function isInStandaloneMode() {
    return window.matchMedia('(display-mode: standalone)').matches || 
           window.matchMedia('(display-mode: fullscreen)').matches || 
           (window.navigator as any).standalone === true;
}

function checkIOS() {
    const ua = window.navigator.userAgent;
    const isSafari = /^((?!chrome|android).)*safari/i.test(ua);
    return /iPhone|iPad|iPod/.test(ua) && isSafari && !window.matchMedia('(display-mode: standalone)').matches;
}

function handleBeforeInstallPrompt(e: Event) {
    e.preventDefault();
    deferredPrompt = e;
    
    // Wait a short moment before showing the prompt
    setTimeout(() => {
        if (!sessionStorage.getItem(sessionKey)) {
            console.log('PWA install prompt available');
            showInstall.value = true;
        }
    }, 1000);
}

function handleAppInstalled() {
    showInstall.value = false;
    deferredPrompt = null;
    sessionStorage.removeItem(sessionKey);
}

function installApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(() => {
            showInstall.value = false;
            deferredPrompt = null;
            sessionStorage.setItem(sessionKey, '1');
        });
    }
}

function dismiss() {
    showInstall.value = false;
    sessionStorage.setItem(sessionKey, '1');
}

onMounted(() => {
    isIOS.value = checkIOS();

    // Show install prompt immediately for iOS devices
    if (isIOS.value && !isInStandaloneMode() && !sessionStorage.getItem(sessionKey)) {
        showInstall.value = true;
    }

    // Android/Chrome: handle install prompt
    console.log('PWA: Initializing install prompt handler');
    window.addEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    window.addEventListener('appinstalled', handleAppInstalled);

    // Check if already installed
    const isInstalled = isInStandaloneMode();
    console.log('PWA: Is installed?', isInstalled);
    console.log('PWA: Is iOS?', isIOS.value);
    
    if (isInstalled) {
        showInstall.value = false;
    }

    // Listen for display mode changes (user uninstalls and revisits)
    window.matchMedia('(display-mode: standalone)').addEventListener('change', (e) => {
        if (!e.matches && !sessionStorage.getItem(sessionKey)) {
            // Not in standalone, allow install again if prompt is available or on iOS
            showInstall.value = isIOS.value || !!deferredPrompt;
        } else {
            showInstall.value = false;
        }
    });
});

onUnmounted(() => {
    window.removeEventListener('beforeinstallprompt', handleBeforeInstallPrompt);
    window.removeEventListener('appinstalled', handleAppInstalled);
});
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
