<template>
  <div v-if="hasError" class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
      <div class="w-24 h-24 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <AlertTriangleIcon class="w-12 h-12 text-red-500" />
      </div>
      
      <h1 class="text-2xl font-bold text-gray-900 mb-4">
        {{ title || 'কিছু সমস্যা হয়েছে' }}
      </h1>
      
      <p class="text-gray-600 mb-8 leading-relaxed">
        {{ message || 'দুঃখিত, কিছু সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।' }}
      </p>
      
      <div class="space-y-4">
        <PrimaryButton 
          @click="retry"
          variant="primary"
          size="lg"
          :icon="RefreshCwIcon"
          class="w-full"
        >
          আবার চেষ্টা করুন
        </PrimaryButton>
        
        <PrimaryButton 
          @click="goHome"
          variant="outline"
          size="lg"
          :icon="HomeIcon"
          class="w-full"
        >
          হোমে ফিরে যান
        </PrimaryButton>
      </div>
      
      <div v-if="showDetails" class="mt-8 p-4 bg-gray-100 rounded-lg text-left">
        <h3 class="font-semibold text-gray-900 mb-2">Error Details:</h3>
        <pre class="text-xs text-gray-600 whitespace-pre-wrap">{{ errorDetails }}</pre>
      </div>
    </div>
  </div>
  
  <slot v-else />
</template>

<script setup lang="ts">
import { ref, onErrorCaptured } from 'vue'
import { router } from '@inertiajs/vue3'
import PrimaryButton from '@/components/Frontend/PrimaryButton.vue'
import { AlertTriangleIcon, RefreshCwIcon, HomeIcon } from 'lucide-vue-next'

interface Props {
  title?: string
  message?: string
  showDetails?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  title: 'কিছু সমস্যা হয়েছে',
  message: 'দুঃখিত, কিছু সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।',
  showDetails: false
})

const hasError = ref(false)
const errorDetails = ref('')

onErrorCaptured((error, instance, info) => {
  hasError.value = true
  errorDetails.value = `${error.message}\n\nComponent: ${instance?.$options?.name || 'Unknown'}\nInfo: ${info}`
  
  // Log error for debugging
  console.error('Error caught by ErrorBoundary:', error)
  console.error('Component:', instance)
  console.error('Info:', info)
  
  return false // Prevent error from propagating
})

const retry = () => {
  hasError.value = false
  errorDetails.value = ''
  window.location.reload()
}

const goHome = () => {
  router.visit(route('frontend.home'))
}
</script> 