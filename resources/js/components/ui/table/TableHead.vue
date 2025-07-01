<template>
  <th 
    :class="cn(
      'h-10 px-6 py-3 text-left align-middle font-medium text-muted-foreground text-xs uppercase tracking-wider',
      textAlign === 'center' && 'text-center',
      textAlign === 'right' && 'text-right',
      props.class
    )"
  >
    <slot />
  </th>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils'

interface Props {
  class?: string
  textAlign?: 'left' | 'center' | 'right'
}

const props = withDefaults(defineProps<Props>(), {
  textAlign: 'left'
})
</script> 