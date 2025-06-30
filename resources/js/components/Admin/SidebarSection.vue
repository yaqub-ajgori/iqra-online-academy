<template>
  <div class="space-y-2">
    <h3 class="px-3 text-xs font-bold text-gray-500 uppercase tracking-widest mb-2 select-none">
      {{ title }}
    </h3>
    <div class="space-y-1">
      <slot />
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true
  }
})
</script> 