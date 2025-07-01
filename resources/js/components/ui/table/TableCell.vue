<template>
  <td 
    :class="cn(
      'p-4 align-middle text-sm',
      textAlign === 'center' && 'text-center',
      textAlign === 'right' && 'text-right',
      props.class
    )"
  >
    <slot />
  </td>
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