<template>
    <div v-if="updateAvailable" class="fixed top-4 left-1/2 z-50 block -translate-x-1/2 transform">
        <div class="animate-slide-down max-w-sm rounded-xl border border-gray-100 bg-white p-4 shadow-2xl">
            <div class="flex items-start space-x-3">
                <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V8m0 8V8"
                        />
                    </svg>
                </div>
                <div class="min-w-0 flex-1">
                    <h4 class="mb-1 text-sm font-semibold text-gray-900">নতুন আপডেট এসেছে!</h4>
                    <p class="mb-3 text-xs text-gray-600">আরও ভালো অভিজ্ঞতার জন্য আপডেট করুন</p>
                    <div class="flex space-x-2">
                        <button
                            @click="updateApp"
                            :disabled="updating"
                            class="flex-1 rounded-lg bg-gradient-to-r from-green-500 to-emerald-600 px-3 py-2 text-xs font-medium text-white transition-all duration-200 hover:shadow-lg disabled:opacity-50"
                        >
                            {{ updating ? 'আপডেট হচ্ছে...' : 'আপডেট করুন' }}
                        </button>
                        <button @click="dismissUpdate" class="px-3 py-2 text-xs text-gray-500 transition-colors hover:text-gray-700">পরে</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

const updateAvailable = ref(false);
const updating = ref(false);
let updateSW: ((reloadPage?: boolean) => Promise<void>) | null = null;

function handleSWUpdate(event: any) {
    if (event.detail) {
        updateSW = event.detail.updateSW;
        updateAvailable.value = true;
    }
}

function handleSWOfflineReady() {
    // App is ready to work offline
    console.log('App ready to work offline');
}

async function updateApp() {
    if (updateSW) {
        updating.value = true;
        try {
            await updateSW(true); // This will reload the page with the new version
        } catch (error) {
            console.error('Error updating app:', error);
            updating.value = false;
        }
    }
}

function dismissUpdate() {
    updateAvailable.value = false;
}

onMounted(() => {
    // Listen for service worker updates
    document.addEventListener('swUpdate', handleSWUpdate);
    document.addEventListener('swOfflineReady', handleSWOfflineReady);
});

onUnmounted(() => {
    document.removeEventListener('swUpdate', handleSWUpdate);
    document.removeEventListener('swOfflineReady', handleSWOfflineReady);
});
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
