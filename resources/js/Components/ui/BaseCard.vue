<template>
  <div
    class="bg-white border border-gray-100 rounded-2xl shadow-sm transition-all duration-200 overflow-hidden"
    :class="[
      paddingClasses[padding],
      clickable ? 'cursor-pointer active:scale-[0.98] select-none hover:shadow-md' : ''
    ]"
  >
    <slot />
  </div>
</template>

<script setup>
defineProps({
  padding: {
    type: String,
    default: 'md' // none, sm, md, lg
  },
  clickable: {
    type: Boolean,
    default: false
  }
})

const paddingClasses = {
  none: '',
  sm: 'p-3',
  md: 'p-5',
  lg: 'p-6'
}
</script>
