<template>
  <div class="relative w-full mb-4">
    <!-- Input element -->
    <input
      :id="id"
      :type="type === 'password' && showPassword ? 'text' : type"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      :placeholder="placeholder || ' '"
      :disabled="disabled"
      class="peer w-full px-4 pt-6 pb-2 border rounded-xl text-base text-brand-tinta font-body placeholder-transparent focus:outline-none focus:ring-2 focus:ring-brand-vinto disabled:bg-gray-50 disabled:opacity-70 transition-all duration-200"
      :class="[
        error ? 'border-red-500 focus:border-red-500 focus:ring-red-500' : 'border-gray-200 focus:border-brand-vinto'
      ]"
    />

    <!-- Floating label -->
    <label
      :for="id"
      class="absolute left-4 top-2 text-xs text-gray-400 font-heading font-medium transition-all duration-200 pointer-events-none origin-[0_0] peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-brand-vinto"
      :class="{ 'text-red-500 peer-focus:text-red-500': error }"
    >
      {{ label }}
    </label>

    <!-- Password visibility toggle -->
    <button
      v-if="type === 'password'"
      type="button"
      @click="showPassword = !showPassword"
      class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-brand-vinto p-1 cursor-pointer focus:outline-none select-none"
    >
      <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
      </svg>
      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
    </button>

    <!-- Error message text -->
    <p v-if="error" class="text-xs text-red-500 mt-1 pl-1 font-body">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'text'
  },
  id: {
    type: String,
    default: () => 'input-' + Math.random().toString(36).substr(2, 9)
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

const showPassword = ref(false)
</script>
