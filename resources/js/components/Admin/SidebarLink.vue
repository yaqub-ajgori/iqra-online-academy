<template>
  <Link 
    :href="href"
    :class="linkClasses"
    @click="$emit('click')"
  >
    <Icon :name="icon" class="mr-3 h-5 w-5 flex-shrink-0 transition-colors duration-200" />
    <span class="font-medium truncate">
      <slot />
    </span>
  </Link>
</template>

<script setup>
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  href: {
    type: String,
    required: true
  },
  active: {
    type: Boolean,
    default: false
  },
  icon: {
    type: String,
    required: true
  }
})

defineEmits(['click'])

const linkClasses = computed(() => [
  'group flex items-center px-4 py-3 mx-3 text-sm rounded-xl transition-all duration-200 ease-in-out',
  props.active
    ? 'bg-gradient-to-r from-[#2d5a27] to-[#5f5fcd] text-white shadow-lg shadow-[#5f5fcd]/25 font-semibold'
    : 'text-gray-700 hover:bg-gradient-to-r hover:from-[#5f5fcd]/5 hover:to-[#2d5a27]/5 hover:text-gray-900 font-medium hover:shadow-sm'
])
</script> 