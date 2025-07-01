<template>
  <!-- When using as-child, render a div wrapper and apply styles to the child -->
  <div v-if="asChild" class="contents">
    <slot 
      :class="cn(buttonVariants({ variant, size }), $props.class)"
      :disabled="disabled"
    />
  </div>
  
  <!-- Normal button rendering -->
  <button
    v-else
    :class="cn(buttonVariants({ variant, size }), $props.class)"
    :disabled="disabled"
    v-bind="$attrs"
  >
    <slot />
  </button>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils'
import { cva } from 'class-variance-authority'

const buttonVariants = cva(
  'inline-flex items-center justify-center whitespace-nowrap rounded-lg text-sm font-medium transition-all duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50',
  {
    variants: {
      variant: {
        default: 'bg-primary text-primary-foreground shadow hover:bg-primary/90 active:scale-[0.98]',
        primary: 'bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white shadow-lg shadow-[#5f5fcd]/25 hover:shadow-xl hover:shadow-[#5f5fcd]/30 active:scale-[0.98] font-semibold',
        secondary: 'bg-secondary text-secondary-foreground shadow-sm hover:bg-secondary/80',
        destructive: 'bg-destructive text-destructive-foreground shadow-sm hover:bg-destructive/90',
        outline: 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground',
        ghost: 'hover:bg-accent hover:text-accent-foreground',
        link: 'text-primary underline-offset-4 hover:underline',
        success: 'bg-green-600 text-white shadow hover:bg-green-700 active:scale-[0.98]',
        warning: 'bg-orange-600 text-white shadow hover:bg-orange-700 active:scale-[0.98]',
        info: 'bg-blue-600 text-white shadow hover:bg-blue-700 active:scale-[0.98]',
      },
      size: {
        xs: 'h-7 rounded-md px-2 text-xs gap-1',
        sm: 'h-8 rounded-md px-3 text-xs gap-1.5',
        default: 'h-9 px-4 py-2 gap-2',
        lg: 'h-10 rounded-md px-8 gap-2',
        xl: 'h-11 rounded-md px-8 gap-2',
        icon: 'h-9 w-9',
        'icon-sm': 'h-8 w-8',
        'icon-xs': 'h-7 w-7',
      },
    },
    defaultVariants: {
      variant: 'default',
      size: 'default',
    },
  }
)

interface Props {
  variant?: 'default' | 'primary' | 'secondary' | 'destructive' | 'outline' | 'ghost' | 'link' | 'success' | 'warning' | 'info'
  size?: 'xs' | 'sm' | 'default' | 'lg' | 'xl' | 'icon' | 'icon-sm' | 'icon-xs'
  asChild?: boolean
  class?: string
  disabled?: boolean
}

defineProps<Props>()
</script>
