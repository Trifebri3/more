<template>
  <div class="relative w-full mb-4">
    <select
      :id="id"
      :value="modelValue"
      @change="$emit('update:modelValue', $event.target.value)"
      :disabled="disabled"
      class="peer w-full px-4 pt-6 pb-2 border rounded-xl bg-white text-base text-brand-tinta font-body appearance-none focus:outline-none focus:ring-2 focus:ring-brand-vinto disabled:bg-gray-50 disabled:opacity-70 transition-all duration-200"
      :class="[
        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-brand-vinto'
      ]"
    >
      <option value="" disabled selected hidden v-if="placeholder">{{ placeholder }}</option>
      <slot />
    </select>

    <!-- Floating label -->
    <label
      :for="id"
      class="absolute left-4 top-2 text-xs text-gray-400 font-heading font-medium transition-all duration-200 pointer-events-none origin-[0_0] peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-brand-vinto"
      :class="{ 
        'text-red-500 peer-focus:text-red-500': error,
        'top-2 text-xs': modelValue || placeholder
      }"
    >
      {{ label }}
    </label>

    <!-- Dropdown chevron indicator -->
    <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </div>

    <!-- Error message text -->
    <p v-if="error" class="text-xs text-red-500 mt-1 pl-1 font-body">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    required: true
  },
  id: {
    type: String,
    default: () => 'select-' + Math.random().toString(36).substr(2, 9)
  },
  placeholder: {
    type: String,
    default: ''
  },
  disabled: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])
</script>
