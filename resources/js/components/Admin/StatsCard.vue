<template>
  <Card class="overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-shadow duration-200">
    <CardContent class="p-6">
      <div class="flex items-center">
        <div class="flex-shrink-0">
          <div :class="iconClasses">
            <Icon :name="icon" class="h-6 w-6" />
          </div>
        </div>
        <div class="ml-5 w-0 flex-1">
          <dl>
            <dt class="text-sm font-medium text-gray-500 truncate mb-1">
              {{ title }}
            </dt>
            <dd>
              <div class="text-2xl font-bold text-gray-900">
                {{ value }}
              </div>
            </dd>
          </dl>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<script setup>
import { computed } from 'vue'
import { Card, CardContent } from '@/components/ui/card'
import Icon from '@/components/Icon.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  value: {
    type: [String, Number],
    required: true
  },
  icon: {
    type: String,
    required: true
  },
  color: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'green', 'purple', 'orange', 'red', 'yellow'].includes(value)
  }
})

const iconClasses = computed(() => {
  const baseClasses = 'rounded-lg p-3 shadow-sm'
  const colorClasses = {
    blue: 'bg-gradient-to-br from-[#5f5fcd] to-blue-600 text-white',
    green: 'bg-gradient-to-br from-[#2d5a27] to-green-600 text-white',
    purple: 'bg-gradient-to-br from-purple-500 to-purple-600 text-white',
    orange: 'bg-gradient-to-br from-orange-500 to-red-600 text-white',
    red: 'bg-gradient-to-br from-red-500 to-red-600 text-white',
    yellow: 'bg-gradient-to-br from-yellow-500 to-orange-500 text-white'
  }
  
  return [baseClasses, colorClasses[props.color]]
})
</script> 