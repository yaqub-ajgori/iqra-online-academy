<template>
  <div class="progress-indicator">
    <!-- Linear Progress Bar -->
    <div v-if="type === 'linear'" class="w-full">
      <div class="relative h-2 bg-gray-200 rounded-full overflow-hidden">
        <div 
          class="h-full transition-all duration-300 ease-out rounded-full"
          :class="getProgressColor()"
          :style="{ width: `${percentage}%` }"
        ></div>
        
        <!-- Animated stripes for indeterminate progress -->
        <div 
          v-if="indeterminate"
          class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"
        ></div>
      </div>
      
      <div v-if="showLabel" class="flex justify-between items-center mt-2">
        <span class="text-sm text-gray-600">{{ label }}</span>
        <span class="text-sm font-medium text-gray-900">{{ percentage }}%</span>
      </div>
    </div>

    <!-- Circular Progress -->
    <div v-else-if="type === 'circular'" class="relative inline-block">
      <svg 
        class="transform -rotate-90" 
        :width="size" 
        :height="size"
      >
        <!-- Background circle -->
        <circle
          cx="50%"
          cy="50%"
          :r="radius"
          stroke="currentColor"
          stroke-width="4"
          fill="transparent"
          class="text-gray-200"
        />
        
        <!-- Progress circle -->
        <circle
          cx="50%"
          cy="50%"
          :r="radius"
          stroke="currentColor"
          stroke-width="4"
          fill="transparent"
          :stroke-dasharray="circumference"
          :stroke-dashoffset="strokeDashoffset"
          stroke-linecap="round"
          class="transition-all duration-300 ease-out"
          :class="getProgressColor()"
        />
      </svg>
      
      <!-- Center content -->
      <div 
        v-if="showLabel"
        class="absolute inset-0 flex items-center justify-center"
      >
        <div class="text-center">
          <div class="text-lg font-bold text-gray-900">{{ percentage }}%</div>
          <div class="text-xs text-gray-600">{{ label }}</div>
        </div>
      </div>
    </div>

    <!-- Dots Loading -->
    <div v-else-if="type === 'dots'" class="flex items-center justify-center space-x-1">
      <div 
        v-for="i in 3" 
        :key="i"
        class="w-2 h-2 rounded-full animate-bounce"
        :class="getProgressColor()"
        :style="{ animationDelay: `${(i - 1) * 0.1}s` }"
      ></div>
    </div>

    <!-- Spinner -->
    <div v-else class="flex items-center justify-center">
      <div 
        class="animate-spin rounded-full border-2 border-gray-200"
        :class="getProgressColor()"
        :style="{ width: `${size}px`, height: `${size}px` }"
      >
        <div class="w-full h-full rounded-full border-2 border-transparent border-t-current"></div>
      </div>
      
      <span v-if="showLabel" class="ml-3 text-sm text-gray-600">{{ label }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  type?: 'linear' | 'circular' | 'dots' | 'spinner'
  percentage?: number
  indeterminate?: boolean
  size?: number
  showLabel?: boolean
  label?: string
  variant?: 'primary' | 'success' | 'warning' | 'error' | 'info'
}

const props = withDefaults(defineProps<Props>(), {
  type: 'spinner',
  percentage: 0,
  indeterminate: false,
  size: 24,
  showLabel: false,
  label: 'Loading...',
  variant: 'primary'
})

// Computed properties for circular progress
const radius = computed(() => (props.size - 8) / 2)
const circumference = computed(() => 2 * Math.PI * radius.value)
const strokeDashoffset = computed(() => {
  if (props.indeterminate) return 0
  return circumference.value - (props.percentage / 100) * circumference.value
})

// Get progress color based on variant
const getProgressColor = () => {
  switch (props.variant) {
    case 'success':
      return 'text-green-500'
    case 'warning':
      return 'text-yellow-500'
    case 'error':
      return 'text-red-500'
    case 'info':
      return 'text-blue-500'
    default:
      return 'text-[#5f5fcd]'
  }
}
</script>

<style scoped>
@keyframes shimmer {
  0% {
    transform: translateX(-100%);
  }
  100% {
    transform: translateX(100%);
  }
}

.animate-shimmer {
  animation: shimmer 1.5s infinite;
}

.animate-bounce {
  animation: bounce 1.4s ease-in-out infinite both;
}

@keyframes bounce {
  0%, 80%, 100% {
    transform: scale(0);
  }
  40% {
    transform: scale(1);
  }
}
</style> 