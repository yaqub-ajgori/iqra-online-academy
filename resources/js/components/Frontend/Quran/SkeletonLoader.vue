<template>
    <!-- Surah Header Skeleton -->
    <div v-if="type === 'surah-header'" class="animate-pulse rounded-xl border border-gray-100 bg-white p-8 text-center shadow-sm">
        <div class="space-y-4">
            <!-- Bismillah Skeleton -->
            <div class="mx-auto h-8 w-96 rounded bg-gray-200"></div>

            <!-- Surah Name Skeleton -->
            <div class="space-y-2">
                <div class="mx-auto h-10 w-64 rounded bg-gray-200"></div>
                <div class="mx-auto h-6 w-48 rounded bg-gray-200"></div>
                <div class="mx-auto h-4 w-32 rounded bg-gray-200"></div>
            </div>

            <!-- Audio Controls Skeleton -->
            <div class="flex flex-col items-center justify-center gap-4 pt-4 sm:flex-row">
                <div class="h-12 w-48 rounded-lg bg-gray-200"></div>
                <div class="h-10 w-32 rounded-lg bg-gray-200"></div>
            </div>
        </div>
    </div>

    <!-- Ayah Card Skeleton -->
    <div v-else-if="type === 'ayah-card'" class="animate-pulse rounded-xl border border-gray-100 bg-white p-6 shadow-sm">
        <div class="space-y-4">
            <!-- Ayah Number -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                    <div class="h-4 w-20 rounded bg-gray-200"></div>
                </div>
                <div class="flex space-x-2">
                    <div class="h-8 w-8 rounded-lg bg-gray-200"></div>
                    <div class="h-8 w-8 rounded-lg bg-gray-200"></div>
                    <div class="h-8 w-8 rounded-lg bg-gray-200"></div>
                </div>
            </div>

            <!-- Arabic Text -->
            <div class="rounded-xl bg-gray-100 p-6">
                <div class="space-y-3">
                    <div class="h-8 w-full rounded bg-gray-200"></div>
                    <div class="h-8 w-5/6 rounded bg-gray-200"></div>
                    <div class="h-8 w-4/5 rounded bg-gray-200"></div>
                </div>
            </div>

            <!-- Translation -->
            <div class="space-y-2">
                <div class="h-4 w-full rounded bg-gray-200"></div>
                <div class="h-4 w-4/5 rounded bg-gray-200"></div>
            </div>

            <!-- Reference -->
            <div class="text-center">
                <div class="mx-auto h-6 w-32 rounded-full bg-gray-200"></div>
            </div>
        </div>
    </div>

    <!-- Surah List Skeleton -->
    <div v-else-if="type === 'surah-list'" class="space-y-1">
        <div v-for="i in count" :key="i" class="w-full animate-pulse rounded-lg bg-white p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                    <div class="space-y-2">
                        <div class="h-4 w-32 rounded bg-gray-200"></div>
                        <div class="h-3 w-24 rounded bg-gray-200"></div>
                    </div>
                </div>
                <div class="h-6 w-16 rounded bg-gray-200"></div>
            </div>
        </div>
    </div>

    <!-- Audio Control Skeleton -->
    <div v-else-if="type === 'audio-control'" class="flex animate-pulse items-center space-x-2">
        <div class="h-10 w-10 rounded-full bg-gray-200"></div>
        <div class="flex-1 space-y-1">
            <div class="h-2 w-full rounded bg-gray-200"></div>
            <div class="flex justify-between text-xs">
                <div class="h-3 w-8 rounded bg-gray-200"></div>
                <div class="h-3 w-8 rounded bg-gray-200"></div>
            </div>
        </div>
        <div class="h-8 w-8 rounded bg-gray-200"></div>
    </div>

    <!-- Reciter Loading Skeleton -->
    <div v-else-if="type === 'reciter-loading'" class="flex animate-pulse items-center justify-center py-2">
        <div class="mr-2 h-4 w-4 rounded-full bg-gray-200"></div>
        <div class="h-3 w-16 rounded bg-gray-200"></div>
    </div>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        required: true,
        validator: (value) => ['surah-header', 'ayah-card', 'surah-list', 'audio-control', 'reciter-loading'].includes(value),
    },
    count: {
        type: Number,
        default: 5,
    },
});
</script>

<style scoped>
@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
