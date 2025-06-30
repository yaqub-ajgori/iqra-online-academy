<template>
  <div :class="containerClasses">
    <!-- Decorative Pattern -->
    <div v-if="showPattern" class="flex justify-center mb-4">
      <div class="flex items-center space-x-2">
        <div class="w-8 h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent rounded-full"></div>
        <div class="w-3 h-3 bg-[#5f5fcd] rounded-full"></div>
        <div class="w-8 h-1 bg-gradient-to-r from-transparent via-[#d4a574] to-transparent rounded-full"></div>
      </div>
    </div>

    <!-- Badge/Category -->
    <div v-if="badge" class="flex justify-center mb-3">
      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-[#5f5fcd]/10 to-[#2d5a27]/10 text-[#5f5fcd] border border-[#5f5fcd]/20">
        {{ badge }}
      </span>
    </div>

    <!-- Title -->
    <div class="text-center mb-4">
      <component 
        :is="titleTag" 
        :class="titleClasses"
      >
        {{ title }}
      </component>
      
      <!-- Subtitle -->
      <p v-if="subtitle" :class="subtitleClasses">
        {{ subtitle }}
      </p>
    </div>

    <!-- Islamic Divider -->
    <div v-if="showDivider" class="flex justify-center mb-6">
      <div class="relative">
        <div class="divider-islamic w-24"></div>
                  <div class="absolute top-0 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
          <div class="w-2 h-2 bg-[#d4a574] rounded-full"></div>
        </div>
      </div>
    </div>

    <!-- Description -->
    <div v-if="description" class="text-center">
      <p :class="descriptionClasses">
        {{ description }}
      </p>
    </div>

    <!-- CTA Section -->
    <div v-if="$slots.cta" class="flex justify-center mt-6">
      <slot name="cta" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  title: string
  subtitle?: string
  description?: string
  badge?: string
  size?: 'sm' | 'md' | 'lg' | 'xl'
  titleTag?: 'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6'
  alignment?: 'left' | 'center' | 'right'
  variant?: 'default' | 'gradient' | 'accent'
  showPattern?: boolean
  showDivider?: boolean
  spacing?: 'sm' | 'md' | 'lg' | 'xl'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  titleTag: 'h2',
  alignment: 'center',
  variant: 'default',
  showPattern: true,
  showDivider: true,
  spacing: 'md'
})

const containerClasses = computed(() => {
  const baseClasses = ['section-header']
  
  const alignmentClasses = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-right'
  }

  const spacingClasses = {
    sm: 'mb-8',
    md: 'mb-12',
    lg: 'mb-16',
    xl: 'mb-20'
  }

  return [
    ...baseClasses,
    alignmentClasses[props.alignment],
    spacingClasses[props.spacing]
  ].join(' ')
})

const titleClasses = computed(() => {
  const baseClasses = ['font-bold leading-tight tracking-tight']

  const sizeClasses = {
    sm: 'text-xl lg:text-2xl',
    md: 'text-2xl lg:text-3xl',
    lg: 'text-3xl lg:text-4xl',
    xl: 'text-4xl lg:text-5xl'
  }

  const variantClasses = {
    default: 'text-gray-900',
    gradient: 'text-gradient-islamic',
    accent: 'text-[#5f5fcd]'
  }

  return [
    ...baseClasses,
    sizeClasses[props.size],
    variantClasses[props.variant]
  ].join(' ')
})

const subtitleClasses = computed(() => {
  const baseClasses = ['mt-2 font-medium']

  const sizeClasses = {
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl'
  }

  return [
    ...baseClasses,
    sizeClasses[props.size],
    'text-[#d4a574]'
  ].join(' ')
})

const descriptionClasses = computed(() => {
  const baseClasses = ['text-gray-600 leading-relaxed max-w-2xl mx-auto']

  const sizeClasses = {
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl'
  }

  return [
    ...baseClasses,
    sizeClasses[props.size]
  ].join(' ')
})
</script>

<style scoped>
/* Islamic-inspired animations for section headers */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes decorativeFloat {
  0%, 100% {
    transform: translateY(0px);
  }
  50% {
    transform: translateY(-5px);
  }
}

.section-header {
  animation: fadeInUp 0.6s ease-out;
}

/* Decorative elements animation */
.section-header .w-3 {
  animation: decorativeFloat 2s ease-in-out infinite;
}

/* Islamic geometric pattern for background */
.pattern-bg {
  position: relative;
}

.pattern-bg::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: 
    radial-gradient(circle at 25% 25%, rgba(95, 95, 205, 0.05) 2px, transparent 2px),
    radial-gradient(circle at 75% 75%, rgba(45, 90, 39, 0.05) 2px, transparent 2px);
  background-size: 40px 40px;
  pointer-events: none;
}

/* Text gradient animation */
.text-gradient-islamic {
  background-size: 200% 200%;
  animation: gradientShift 3s ease-in-out infinite;
}

@keyframes gradientShift {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

/* Enhanced divider styling */
.divider-islamic {
  position: relative;
}

.divider-islamic::after {
  content: '';
  position: absolute;
  top: 50%;
  left: -10px;
  right: -10px;
  height: 1px;
  background: linear-gradient(90deg, transparent 0%, rgba(212, 165, 116, 0.3) 10%, rgba(95, 95, 205, 0.3) 50%, rgba(212, 165, 116, 0.3) 90%, transparent 100%);
  transform: translateY(-50%);
}
</style> 
