<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]"
      enter-from-class="opacity-0 translate-y-6 scale-95 rotate-1"
      enter-to-class="opacity-100 translate-y-0 scale-100 rotate-0"
      leave-active-class="transition-all duration-400 ease-[cubic-bezier(0.25,0.46,0.45,0.94)]"
      leave-from-class="opacity-100 translate-y-0 scale-100 rotate-0"
      leave-to-class="opacity-0 translate-y-6 scale-95 -rotate-1"
    >
      <div
        v-if="visible"
        :class="[
          'fixed z-50 flex items-center gap-3 p-4 rounded-xl shadow-2xl backdrop-blur-md border max-w-md min-w-80',
          'transform transition-all duration-300 ease-out cursor-pointer group',
          'hover:scale-105 hover:shadow-3xl hover:-translate-y-1',
          'ring-1 ring-white/10',
          positionClasses,
          variantClasses
        ]"
        role="alert"
        :aria-live="toast.type === 'error' ? 'assertive' : 'polite'"
        @mouseenter="pauseTimer"
        @mouseleave="resumeTimer"
      >
        <!-- Icon -->
        <div class="flex-shrink-0 transform transition-transform duration-300 group-hover:scale-110 group-hover:rotate-12">
          <component 
            :is="iconComponent" 
            :class="['w-5 h-5 drop-shadow-lg', iconColorClass]"
          />
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0 transform transition-transform duration-300 group-hover:translate-x-1">
          <h4 v-if="toast.title" class="font-semibold text-sm mb-1 drop-shadow-sm">
            {{ toast.title }}
          </h4>
          <p class="text-sm opacity-90 drop-shadow-sm">
            {{ toast.message }}
          </p>
        </div>

        <!-- Action Button -->
        <button
          v-if="toast.action"
          @click="handleAction"
          class="flex-shrink-0 px-3 py-1 text-xs font-medium rounded-md bg-white/20 hover:bg-white/30 transition-colors"
        >
          {{ toast.action.label }}
        </button>

        <!-- Close Button -->
        <button
          @click="dismiss"
          class="flex-shrink-0 p-1 rounded-full hover:bg-white/20 transition-all duration-200 hover:scale-110 hover:rotate-90 opacity-70 hover:opacity-100"
          :aria-label="'বন্ধ করুন'"
        >
          <XIcon class="w-4 h-4 drop-shadow-sm" />
        </button>

        <!-- Progress Bar -->
        <div
          v-if="showProgress"
          class="absolute bottom-0 left-0 h-1 bg-gradient-to-r from-white/40 to-white/60 rounded-full transition-all duration-100 ease-linear shadow-lg"
          :style="{ width: `${progress}%` }"
        >
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { computed, onMounted, onUnmounted, ref, watch } from 'vue'
import {
  CheckCircleIcon,
  XCircleIcon,
  AlertTriangleIcon,
  InfoIcon,
  XIcon
} from 'lucide-vue-next'

export interface ToastAction {
  label: string
  handler: () => void
}

export interface Toast {
  id: string
  type: 'success' | 'error' | 'warning' | 'info'
  title?: string
  message: string
  duration?: number
  persistent?: boolean
  action?: ToastAction
  position?: 'top-right' | 'top-left' | 'bottom-right' | 'bottom-left' | 'top-center' | 'bottom-center'
}

interface Props {
  toast: Toast
  onDismiss: (id: string) => void
}

const props = defineProps<Props>()

const visible = ref(false)
const progress = ref(100)
const timer = ref<number | null>(null)
const progressTimer = ref<number | null>(null)

// Computed properties
const iconComponent = computed(() => {
  const icons = {
    success: CheckCircleIcon,
    error: XCircleIcon,
    warning: AlertTriangleIcon,
    info: InfoIcon
  }
  return icons[props.toast.type]
})

const iconColorClass = computed(() => {
  const colors = {
    success: 'text-green-400',
    error: 'text-red-400',
    warning: 'text-yellow-400',
    info: 'text-blue-400'
  }
  return colors[props.toast.type]
})

const variantClasses = computed(() => {
  const variants = {
    success: 'bg-gradient-to-br from-green-900/95 to-green-800/90 border-green-600/40 text-green-50 shadow-green-900/20',
    error: 'bg-gradient-to-br from-red-900/95 to-red-800/90 border-red-600/40 text-red-50 shadow-red-900/20',
    warning: 'bg-gradient-to-br from-yellow-900/95 to-yellow-800/90 border-yellow-600/40 text-yellow-50 shadow-yellow-900/20',
    info: 'bg-gradient-to-br from-blue-900/95 to-blue-800/90 border-blue-600/40 text-blue-50 shadow-blue-900/20'
  }
  return variants[props.toast.type]
})

const positionClasses = computed(() => {
  const positions = {
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'top-center': 'top-4 left-1/2 transform -translate-x-1/2',
    'bottom-center': 'bottom-4 left-1/2 transform -translate-x-1/2'
  }
  return positions[props.toast.position || 'bottom-right']
})

const showProgress = computed(() => {
  return !props.toast.persistent && props.toast.duration && props.toast.duration > 0
})

// Methods
const dismiss = () => {
  visible.value = false
  clearTimers()
  setTimeout(() => {
    props.onDismiss(props.toast.id)
  }, 200)
}

const handleAction = () => {
  if (props.toast.action?.handler) {
    props.toast.action.handler()
  }
  dismiss()
}

const clearTimers = () => {
  if (timer.value) {
    clearTimeout(timer.value)
    timer.value = null
  }
  if (progressTimer.value) {
    clearInterval(progressTimer.value)
    progressTimer.value = null
  }
}

const startTimer = () => {
  if (props.toast.persistent) return

  const duration = props.toast.duration || 5000
  
  // Auto dismiss timer
  timer.value = setTimeout(() => {
    dismiss()
  }, duration)

  // Progress bar timer
  if (showProgress.value) {
    const interval = 50
    const steps = duration / interval
    let currentStep = 0

    progressTimer.value = setInterval(() => {
      currentStep++
      progress.value = Math.max(0, 100 - (currentStep / steps) * 100)
      
      if (currentStep >= steps) {
        clearInterval(progressTimer.value!)
        progressTimer.value = null
      }
    }, interval)
  }
}

const pauseTimer = () => {
  clearTimers()
}

const resumeTimer = () => {
  startTimer()
}

// Lifecycle
onMounted(() => {
  visible.value = true
  startTimer()
})

onUnmounted(() => {
  clearTimers()
})

// Watch for visibility changes
watch(visible, (newVal) => {
  if (!newVal) {
    clearTimers()
  }
})
</script>

<style scoped>
/* Additional styles for backdrop blur support */
@supports (backdrop-filter: blur(8px)) {
  .backdrop-blur-sm {
    backdrop-filter: blur(8px);
  }
}
</style> 