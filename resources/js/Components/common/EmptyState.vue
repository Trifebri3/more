<template>
  <div class="flex flex-col items-center justify-center text-center py-12 px-6">
    <!-- Icon or Illustration placeholder -->
    <div class="w-20 h-20 bg-brand-natural/20 text-brand-vinto rounded-full flex items-center justify-center mb-4">
      <slot name="icon">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
      </slot>
    </div>

    <!-- Title and message -->
    <h3 class="text-lg font-heading font-bold text-brand-tinta mb-1">
      {{ title }}
    </h3>
    <p class="text-sm font-body text-gray-500 max-w-xs mb-6">
      {{ message }}
    </p>

    <!-- Call to action button -->
    <div v-if="actionText">
      <BaseButton @click="$emit('action')" variant="primary">
        {{ actionText }}
      </BaseButton>
    </div>
  </div>
</template>

<script setup>
import BaseButton from '../ui/BaseButton.vue'

defineProps({
  title: {
    type: String,
    default: 'Data Kosong'
  },
  message: {
    type: String,
    default: 'Belum ada data yang ditambahkan di sini.'
  },
  actionText: {
    type: String,
    default: ''
  }
})

defineEmits(['action'])
</script>
