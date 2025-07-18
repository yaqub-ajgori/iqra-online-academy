<template>
    <div class="animate-pulse">
        <!-- Course Card Skeleton -->
        <div v-if="type === 'course-card'" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg">
            <div class="h-48 w-full bg-gray-200"></div>
            <div class="p-6">
                <div class="mb-4 flex items-center gap-2">
                    <div class="h-4 w-16 rounded bg-gray-200"></div>
                    <div class="h-4 w-20 rounded bg-gray-200"></div>
                </div>
                <div class="mb-3 h-6 w-3/4 rounded bg-gray-200"></div>
                <div class="mb-2 h-4 w-full rounded bg-gray-200"></div>
                <div class="mb-4 h-4 w-2/3 rounded bg-gray-200"></div>
                <div class="mb-4 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                        <div class="h-4 w-24 rounded bg-gray-200"></div>
                    </div>
                    <div class="h-4 w-16 rounded bg-gray-200"></div>
                </div>
                <div class="flex space-x-3">
                    <div class="h-10 flex-1 rounded bg-gray-200"></div>
                    <div class="h-10 flex-1 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>

        <!-- Course Grid Skeleton -->
        <div v-else-if="type === 'course-grid'" class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <div v-for="i in count" :key="i" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg">
                <div class="h-48 w-full bg-gray-200"></div>
                <div class="p-6">
                    <div class="mb-4 flex items-center gap-2">
                        <div class="h-4 w-16 rounded bg-gray-200"></div>
                        <div class="h-4 w-20 rounded bg-gray-200"></div>
                    </div>
                    <div class="mb-3 h-6 w-3/4 rounded bg-gray-200"></div>
                    <div class="mb-2 h-4 w-full rounded bg-gray-200"></div>
                    <div class="mb-4 h-4 w-2/3 rounded bg-gray-200"></div>
                    <div class="mb-4 flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="h-8 w-8 rounded-full bg-gray-200"></div>
                            <div class="h-4 w-24 rounded bg-gray-200"></div>
                        </div>
                        <div class="h-4 w-16 rounded bg-gray-200"></div>
                    </div>
                    <div class="flex space-x-3">
                        <div class="h-10 flex-1 rounded bg-gray-200"></div>
                        <div class="h-10 flex-1 rounded bg-gray-200"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Course Details Skeleton -->
        <div v-else-if="type === 'course-details'" class="space-y-8">
            <div class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
                <div class="mb-6 flex items-center gap-4">
                    <div class="h-6 w-20 rounded bg-gray-200"></div>
                    <div class="h-6 w-24 rounded bg-gray-200"></div>
                </div>
                <div class="mb-4 h-8 w-3/4 rounded bg-gray-200"></div>
                <div class="mb-2 h-5 w-full rounded bg-gray-200"></div>
                <div class="mb-6 h-5 w-2/3 rounded bg-gray-200"></div>
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center space-x-2">
                        <div class="h-5 w-5 rounded bg-gray-200"></div>
                        <div class="h-4 w-20 rounded bg-gray-200"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-5 w-5 rounded bg-gray-200"></div>
                        <div class="h-4 w-16 rounded bg-gray-200"></div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div v-for="i in 5" :key="i" class="h-4 w-4 rounded bg-gray-200"></div>
                        </div>
                        <div class="h-4 w-24 rounded bg-gray-200"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Skeleton -->
        <div v-else-if="type === 'profile'" class="rounded-2xl border border-gray-100 bg-white p-8 shadow-lg">
            <div class="mb-8 flex items-center space-x-6">
                <div class="h-24 w-24 rounded-full bg-gray-200"></div>
                <div class="flex-1">
                    <div class="mb-3 h-8 w-48 rounded bg-gray-200"></div>
                    <div class="mb-2 h-5 w-32 rounded bg-gray-200"></div>
                    <div class="h-4 w-64 rounded bg-gray-200"></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div v-for="i in 6" :key="i" class="space-y-2">
                    <div class="h-4 w-24 rounded bg-gray-200"></div>
                    <div class="h-12 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>

        <!-- Dashboard Stats Skeleton -->
        <div v-else-if="type === 'dashboard-stats'" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <div v-for="i in 4" :key="i" class="rounded-2xl border border-gray-100 bg-white p-6 shadow-lg">
                <div class="mb-4 flex items-center justify-between">
                    <div class="h-6 w-20 rounded bg-gray-200"></div>
                    <div class="h-12 w-12 rounded-lg bg-gray-200"></div>
                </div>
                <div class="mb-2 h-8 w-16 rounded bg-gray-200"></div>
                <div class="h-4 w-24 rounded bg-gray-200"></div>
            </div>
        </div>

        <!-- Table Skeleton -->
        <div v-else-if="type === 'table'" class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-lg">
            <div class="border-b border-gray-200 p-6">
                <div class="h-6 w-32 rounded bg-gray-200"></div>
            </div>
            <div class="p-6">
                <div v-for="i in rows" :key="i" class="flex items-center space-x-4 border-b border-gray-100 py-4 last:border-b-0">
                    <div class="h-4 w-8 rounded bg-gray-200"></div>
                    <div class="h-4 w-32 rounded bg-gray-200"></div>
                    <div class="h-4 w-24 rounded bg-gray-200"></div>
                    <div class="h-4 w-20 rounded bg-gray-200"></div>
                    <div class="h-4 w-16 rounded bg-gray-200"></div>
                </div>
            </div>
        </div>

        <!-- Generic Skeleton -->
        <div v-else class="rounded-2xl border border-gray-100 bg-white p-6 shadow-lg">
            <div class="space-y-4">
                <div v-for="i in count" :key="i" class="h-4 rounded bg-gray-200" :style="{ width: `${Math.random() * 60 + 40}%` }"></div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
interface Props {
    type?: 'course-card' | 'course-grid' | 'course-details' | 'profile' | 'dashboard-stats' | 'table' | 'generic';
    count?: number;
    rows?: number;
}

withDefaults(defineProps<Props>(), {
    type: 'generic',
    count: 3,
    rows: 5,
});
</script>

<style scoped>
.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>
