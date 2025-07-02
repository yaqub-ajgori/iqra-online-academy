<template>
  <component
    :is="tag"
    :href="href"
    :type="type"
    :disabled="disabled"
    :class="buttonClasses"
    @click="handleClick"
  >
    <span v-if="loading">
    </span>
    <component v-if="icon && !loading" :is="icon" :class="iconClasses" />
    <span v-if="$slots.default">
      <slot />
    </span>
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  variant?: 'primary' | 'secondary' | 'accent' | 'outline' | 'ghost' | 'destructive'
  size?: 'sm' | 'md' | 'lg' | 'xl'
  tag?: 'button' | 'a' | 'router-link'
  href?: string
  type?: 'button' | 'submit' | 'reset'
  disabled?: boolean
  loading?: boolean
  icon?: any
  iconPosition?: 'left' | 'right'
  fullWidth?: boolean
  rounded?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  size: 'md',
  tag: 'button',
  type: 'button',
  disabled: false,
  loading: false,
  iconPosition: 'left',
  fullWidth: false,
  rounded: false
})

const emit = defineEmits<{
  click: [event: Event]
}>()

const handleClick = (event: Event) => {
  if (!props.disabled && !props.loading) {
    emit('click', event)
  }
}

const buttonClasses = computed(() => {
  const baseClasses = [
    'inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out',
    'focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#5f5fcd]',
    'disabled:opacity-50 disabled:cursor-not-allowed',
    'transform hover:scale-105 active:scale-95'
  ]

  // Size classes
  const sizeClasses = {
    sm: 'px-3 py-1.5 text-sm',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
    xl: 'px-8 py-4 text-lg'
  }

  // Variant classes
  const variantClasses = {
    primary: [
      'bg-gradient-to-r from-[#5f5fcd] to-[#2d5a27] text-white',
      'hover:shadow-islamic-lg hover:from-[#4a4aa6] hover:to-[#1f3e1b]',
      'border border-transparent'
    ],
    secondary: [
      'bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white',
      'hover:shadow-islamic-lg hover:from-[#1f3e1b] hover:to-[#4a4aa6]',
      'border border-transparent'
    ],
    accent: [
      'bg-[#d4a574] text-[#1f1f1f] border border-[#c49760]',
      'hover:bg-[#c49760] hover:shadow-lg'
    ],
    outline: [
      'border-2 border-[#5f5fcd] text-[#5f5fcd] bg-transparent',
      'hover:bg-[#5f5fcd] hover:text-white hover:shadow-islamic'
    ],
    ghost: [
      'text-[#5f5fcd] bg-transparent border border-transparent',
      'hover:bg-gray-100 hover:text-[#4a4aa6]'
    ],
    destructive: [
      'bg-red-600 text-white border border-red-600',
      'hover:bg-red-700 hover:border-red-700 hover:shadow-lg'
    ]
  }

  // Border radius
  const radiusClasses = props.rounded ? 'rounded-full' : 'rounded-lg'

  // Full width
  const widthClasses = props.fullWidth ? 'w-full' : ''

  return [
    ...baseClasses,
    sizeClasses[props.size],
    ...variantClasses[props.variant],
    radiusClasses,
    widthClasses
  ].filter(Boolean).join(' ')
})

const iconClasses = computed(() => {
  const baseIconClasses = ['flex-shrink-0']
  
  const sizeIconClasses = {
    sm: 'w-3 h-3',
    md: 'w-4 h-4',
    lg: 'w-5 h-5',
    xl: 'w-6 h-6'
  }

  const positionClasses = props.iconPosition === 'left' ? 'mr-2' : 'ml-2'

  return [
    ...baseIconClasses,
    sizeIconClasses[props.size],
    positionClasses
  ].join(' ')
})
</script>

<style scoped>
/* Islamic-inspired button animations */
@keyframes shimmer {
  0% {
    background-position: -200% 0;
  }
  100% {
    background-position: 200% 0;
  }
}

/* Shimmer effect for primary buttons on hover */
.group:hover .shimmer {
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255, 255, 255, 0.2),
    transparent
  );
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}

/* Islamic geometric pattern overlay for special buttons */
.pattern-overlay {
  position: relative;
  overflow: hidden;
}

.pattern-overlay::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-image: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
  background-size: 20px 20px;
  opacity: 0;
  transition: opacity 0.3s ease;
}

.pattern-overlay:hover::before {
  opacity: 1;
}
</style> 
