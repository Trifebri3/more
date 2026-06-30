<template>
  <button
    :type="type"
    :disabled="disabled || loading"
    class="relative flex items-center justify-center font-heading font-semibold rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-[0.97] disabled:opacity-50 disabled:active:scale-100 disabled:pointer-events-none select-none cursor-pointer"
    :class="[
      sizeClasses[size],
      variantClasses[variant],
      block ? 'w-full' : ''
    ]"
  >
    <span v-if="loading" class="absolute inset-0 flex items-center justify-center">
      <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
    </span>
    <span :class="{ 'opacity-0': loading }" class="flex items-center gap-2">
      <slot />
    </span>
  </button>
</template>

<script setup>
defineProps({
  type: {
    type: String,
    default: 'button'
  },
  variant: {
    type: String,
    default: 'primary' // primary, secondary, accent, ghost, danger
  },
  size: {
    type: String,
    default: 'md' // sm, md, lg
  },
  block: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const sizeClasses = {
  sm: 'px-4 py-2 text-sm h-10',
  md: 'px-6 py-3 text-base h-12', // min 44px touch target
  lg: 'px-8 py-4 text-lg h-14'
}

const variantClasses = {
  primary: 'bg-brand-vinto text-white hover:bg-primary-dark focus:ring-brand-vinto shadow-sm',
  secondary: 'bg-brand-pudar text-white hover:bg-secondary-dark focus:ring-brand-pudar shadow-sm',
  accent: 'bg-brand-natural text-brand-tinta hover:bg-neutral-dark focus:ring-brand-natural',
  ghost: 'bg-transparent border border-brand-natural text-brand-tinta hover:bg-neutral-light focus:ring-brand-natural',
  danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm'
}
</script>
