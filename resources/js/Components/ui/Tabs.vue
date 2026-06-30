<template>
  <div class="border-b border-gray-100 mb-4 overflow-x-auto scrollbar-none flex">
    <div class="flex gap-4">
      <button
        v-for="tab in tabs"
        :key="tab.value"
        type="button"
        @click="$emit('update:modelValue', tab.value)"
        class="pb-3 text-sm font-heading font-semibold transition-all duration-200 border-b-2 whitespace-nowrap cursor-pointer px-1 active:scale-95"
        :class="[
          modelValue === tab.value
            ? 'border-brand-vinto text-brand-vinto'
            : 'border-transparent text-gray-400 hover:text-brand-tinta'
        ]"
      >
        {{ tab.label }}
      </button>
    </div>
  </div>
</template>

<script setup>
defineProps({
  tabs: {
    type: Array,
    required: true // Array of { label: string, value: any }
  },
  modelValue: {
    type: [String, Number],
    required: true
  }
})

defineEmits(['update:modelValue'])
</script>
