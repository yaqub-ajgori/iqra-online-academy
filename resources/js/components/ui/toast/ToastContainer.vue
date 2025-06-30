<template>
  <div class="toast-container">
    <!-- Group toasts by position -->
    <template v-for="position in positions" :key="position">
      <div
        v-if="getToastsByPosition(position).length > 0"
        :class="[
          'fixed z-50 flex flex-col gap-2 pointer-events-none',
          getPositionClasses(position)
        ]"
      >
        <Toast
          v-for="toast in getToastsByPosition(position)"
          :key="toast.id"
          :toast="toast"
          :onDismiss="removeToast"
          class="pointer-events-auto"
        />
      </div>
    </template>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Toast, { type Toast as ToastType } from './Toast.vue'

interface Props {
  toasts: ToastType[]
  onRemove: (id: string) => void
}

const props = defineProps<Props>()

const positions = [
  'top-right',
  'top-left', 
  'bottom-right',
  'bottom-left',
  'top-center',
  'bottom-center'
] as const

const getToastsByPosition = (position: string) => {
  return props.toasts.filter(toast => (toast.position || 'bottom-right') === position)
}

const getPositionClasses = (position: string) => {
  const classes = {
    'top-right': 'top-4 right-4',
    'top-left': 'top-4 left-4',
    'bottom-right': 'bottom-4 right-4',
    'bottom-left': 'bottom-4 left-4',
    'top-center': 'top-4 left-1/2 transform -translate-x-1/2',
    'bottom-center': 'bottom-4 left-1/2 transform -translate-x-1/2'
  }
  return classes[position as keyof typeof classes] || classes['bottom-right']
}

const removeToast = (id: string) => {
  props.onRemove(id)
}
</script>

<style scoped>
.toast-container {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  pointer-events: none;
  z-index: 9999;
}
</style> 