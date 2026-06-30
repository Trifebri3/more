<template>
  <div v-if="skeleton" class="animate-pulse bg-gray-200 rounded-xl" :style="customStyle"></div>
  <div v-else class="flex items-center justify-center py-6">
    <div class="animate-spin rounded-full h-8 w-8 border-4 border-brand-natural border-t-brand-vinto"></div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  skeleton: {
    type: Boolean,
    default: false
  },
  width: {
    type: String,
    default: '100%'
  },
  height: {
    type: String,
    default: '16px'
  },
  radius: {
    type: String,
    default: '12px'
  }
})

const customStyle = computed(() => {
  return {
    width: props.width,
    height: props.height,
    borderRadius: props.radius
  }
})
</script>
