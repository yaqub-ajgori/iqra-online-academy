<template>
    <div v-if="hasError" class="flex min-h-screen items-center justify-center bg-gray-50 px-4">
        <div class="w-full max-w-md text-center">
            <div class="mx-auto mb-6 flex h-24 w-24 items-center justify-center rounded-full bg-red-100">
                <AlertTriangleIcon class="h-12 w-12 text-red-500" />
            </div>

            <h1 class="mb-4 text-2xl font-bold text-gray-900">
                {{ title || 'কিছু সমস্যা হয়েছে' }}
            </h1>

            <p class="mb-8 leading-relaxed text-gray-600">
                {{ message || 'দুঃখিত, কিছু সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।' }}
            </p>

            <div class="space-y-4">
                <PrimaryButton @click="retry" variant="primary" size="lg" :icon="RefreshCwIcon" class="w-full"> আবার চেষ্টা করুন </PrimaryButton>

                <PrimaryButton @click="goHome" variant="outline" size="lg" :icon="HomeIcon" class="w-full"> হোমে ফিরে যান </PrimaryButton>
            </div>

            <div v-if="showDetails" class="mt-8 rounded-lg bg-gray-100 p-4 text-left">
                <h3 class="mb-2 font-semibold text-gray-900">Error Details:</h3>
                <pre class="text-xs whitespace-pre-wrap text-gray-600">{{ errorDetails }}</pre>
            </div>
        </div>
    </div>

    <slot v-else />
</template>

<script setup lang="ts">
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue';
import { router } from '@inertiajs/vue3';
import { AlertTriangleIcon, HomeIcon, RefreshCwIcon } from 'lucide-vue-next';
import { onErrorCaptured, ref } from 'vue';

interface Props {
    title?: string;
    message?: string;
    showDetails?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    title: 'কিছু সমস্যা হয়েছে',
    message: 'দুঃখিত, কিছু সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।',
    showDetails: false,
});

const hasError = ref(false);
const errorDetails = ref('');

onErrorCaptured((error, instance, info) => {
    hasError.value = true;
    errorDetails.value = `${error.message}\n\nComponent: ${instance?.$options?.name || 'Unknown'}\nInfo: ${info}`;

    // Log error for debugging
    console.error('Error caught by ErrorBoundary:', error);
    console.error('Component:', instance);
    console.error('Info:', info);

    return false; // Prevent error from propagating
});

const retry = () => {
    hasError.value = false;
    errorDetails.value = '';
    window.location.reload();
};

const goHome = () => {
    router.visit(route('frontend.home'));
};
</script>
