<template>
  <div class="animate-pulse">
    <!-- Course Card Skeleton -->
    <div v-if="type === 'course-card'" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
      <div class="w-full h-48 bg-gray-200"></div>
      <div class="p-6">
        <div class="flex items-center gap-2 mb-4">
          <div class="h-4 bg-gray-200 rounded w-16"></div>
          <div class="h-4 bg-gray-200 rounded w-20"></div>
        </div>
        <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
        <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
        <div class="h-4 bg-gray-200 rounded w-2/3 mb-4"></div>
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center space-x-2">
            <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
            <div class="h-4 bg-gray-200 rounded w-24"></div>
          </div>
          <div class="h-4 bg-gray-200 rounded w-16"></div>
        </div>
        <div class="flex space-x-3">
          <div class="h-10 bg-gray-200 rounded flex-1"></div>
          <div class="h-10 bg-gray-200 rounded flex-1"></div>
        </div>
      </div>
    </div>

    <!-- Course Grid Skeleton -->
    <div v-else-if="type === 'course-grid'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <div v-for="i in count" :key="i" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="w-full h-48 bg-gray-200"></div>
        <div class="p-6">
          <div class="flex items-center gap-2 mb-4">
            <div class="h-4 bg-gray-200 rounded w-16"></div>
            <div class="h-4 bg-gray-200 rounded w-20"></div>
          </div>
          <div class="h-6 bg-gray-200 rounded w-3/4 mb-3"></div>
          <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-2/3 mb-4"></div>
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
              <div class="h-4 bg-gray-200 rounded w-24"></div>
            </div>
            <div class="h-4 bg-gray-200 rounded w-16"></div>
          </div>
          <div class="flex space-x-3">
            <div class="h-10 bg-gray-200 rounded flex-1"></div>
            <div class="h-10 bg-gray-200 rounded flex-1"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Course Details Skeleton -->
    <div v-else-if="type === 'course-details'" class="space-y-8">
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
        <div class="flex items-center gap-4 mb-6">
          <div class="h-6 bg-gray-200 rounded w-20"></div>
          <div class="h-6 bg-gray-200 rounded w-24"></div>
        </div>
        <div class="h-8 bg-gray-200 rounded w-3/4 mb-4"></div>
        <div class="h-5 bg-gray-200 rounded w-full mb-2"></div>
        <div class="h-5 bg-gray-200 rounded w-2/3 mb-6"></div>
        <div class="flex flex-wrap items-center gap-6">
          <div class="flex items-center space-x-2">
            <div class="w-5 h-5 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded w-20"></div>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-5 h-5 bg-gray-200 rounded"></div>
            <div class="h-4 bg-gray-200 rounded w-16"></div>
          </div>
          <div class="flex items-center space-x-2">
            <div class="flex space-x-1">
              <div v-for="i in 5" :key="i" class="w-4 h-4 bg-gray-200 rounded"></div>
            </div>
            <div class="h-4 bg-gray-200 rounded w-24"></div>
          </div>
        </div>
      </div>
    </div>

    <!-- Profile Skeleton -->
    <div v-else-if="type === 'profile'" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
      <div class="flex items-center space-x-6 mb-8">
        <div class="w-24 h-24 bg-gray-200 rounded-full"></div>
        <div class="flex-1">
          <div class="h-8 bg-gray-200 rounded w-48 mb-3"></div>
          <div class="h-5 bg-gray-200 rounded w-32 mb-2"></div>
          <div class="h-4 bg-gray-200 rounded w-64"></div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div v-for="i in 6" :key="i" class="space-y-2">
          <div class="h-4 bg-gray-200 rounded w-24"></div>
          <div class="h-12 bg-gray-200 rounded"></div>
        </div>
      </div>
    </div>

    <!-- Dashboard Stats Skeleton -->
    <div v-else-if="type === 'dashboard-stats'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="i in 4" :key="i" class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-4">
          <div class="h-6 bg-gray-200 rounded w-20"></div>
          <div class="w-12 h-12 bg-gray-200 rounded-lg"></div>
        </div>
        <div class="h-8 bg-gray-200 rounded w-16 mb-2"></div>
        <div class="h-4 bg-gray-200 rounded w-24"></div>
      </div>
    </div>

    <!-- Table Skeleton -->
    <div v-else-if="type === 'table'" class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
      <div class="p-6 border-b border-gray-200">
        <div class="h-6 bg-gray-200 rounded w-32"></div>
      </div>
      <div class="p-6">
        <div v-for="i in rows" :key="i" class="flex items-center space-x-4 py-4 border-b border-gray-100 last:border-b-0">
          <div class="h-4 bg-gray-200 rounded w-8"></div>
          <div class="h-4 bg-gray-200 rounded w-32"></div>
          <div class="h-4 bg-gray-200 rounded w-24"></div>
          <div class="h-4 bg-gray-200 rounded w-20"></div>
          <div class="h-4 bg-gray-200 rounded w-16"></div>
        </div>
      </div>
    </div>

    <!-- Generic Skeleton -->
    <div v-else class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
      <div class="space-y-4">
        <div v-for="i in count" :key="i" class="h-4 bg-gray-200 rounded" :style="{ width: `${Math.random() * 60 + 40}%` }"></div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  type?: 'course-card' | 'course-grid' | 'course-details' | 'profile' | 'dashboard-stats' | 'table' | 'generic'
  count?: number
  rows?: number
}

withDefaults(defineProps<Props>(), {
  type: 'generic',
  count: 3,
  rows: 5
})
</script>

<style scoped>
.animate-pulse {
  animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: .5;
  }
}
</style> 