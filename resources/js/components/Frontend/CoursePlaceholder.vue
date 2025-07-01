<template>
  <div 
    class="w-full h-full bg-gradient-to-br from-[#5f5fcd]/10 to-[#2d5a27]/10 rounded-lg flex items-center justify-center border-2 border-dashed border-[#5f5fcd]/30"
    :class="containerClass"
  >
    <div class="text-center p-4">
      <!-- Islamic-themed SVG Icon -->
      <svg 
        class="w-12 h-12 mx-auto mb-3 text-[#5f5fcd]/60" 
        fill="currentColor" 
        viewBox="0 0 24 24"
      >
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        <path d="M12 2v20"/>
        <path d="M2 7l10 5 10-5"/>
        <path d="M2 12l10 5 10-5"/>
        <path d="M2 17l10 5 10-5"/>
      </svg>
      
      <!-- Course Icon -->
      <div class="w-8 h-8 bg-[#5f5fcd]/20 rounded-full flex items-center justify-center mx-auto mb-2">
        <svg class="w-4 h-4 text-[#5f5fcd]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
        </svg>
      </div>
      
      <!-- Text -->
      <p class="text-xs text-[#5f5fcd]/70 font-medium">
        {{ text || 'Course Image' }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
interface Props {
  text?: string
  containerClass?: string
}

defineProps<Props>()
</script> 