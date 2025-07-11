<template>
  <div ref="element">
    <slot v-if="isVisible" />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { useIntersectionObserver } from '@vueuse/core'

interface Props {
  once?: boolean
  rootMargin?: string
  threshold?: number | number[]
}

const props = withDefaults(defineProps<Props>(), {
  once: true,
  rootMargin: '50px',
  threshold: 0.1
})

const element = ref<HTMLElement>()
const isVisible = ref(false)

let stopObserver: (() => void) | undefined

onMounted(() => {
  if (element.value) {
    const { stop } = useIntersectionObserver(
      element,
      ([{ isIntersecting }]) => {
        if (isIntersecting) {
          isVisible.value = true
          if (props.once) {
            stop()
          }
        } else if (!props.once) {
          isVisible.value = false
        }
      },
      {
        rootMargin: props.rootMargin,
        threshold: props.threshold
      }
    )
    
    stopObserver = stop
  }
})

onUnmounted(() => {
  if (stopObserver) {
    stopObserver()
  }
})
</script>