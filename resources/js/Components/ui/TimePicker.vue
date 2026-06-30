<template>
  <div>
    <div v-if="loading" class="grid grid-cols-4 gap-2">
      <div v-for="i in 12" :key="i" class="animate-pulse bg-gray-100 rounded-xl h-10 w-full"></div>
    </div>
    <div v-else-if="slots.length === 0" class="flex flex-col items-center justify-center p-6 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
      <p class="text-sm font-body text-gray-500 text-center">
        Tidak ada slot waktu tersedia untuk tanggal ini.
      </p>
    </div>
    <div v-else class="grid grid-cols-3 sm:grid-cols-4 gap-2">
      <button
        v-for="slot in slots"
        :key="slot.time || slot"
        type="button"
        @click="selectSlot(slot)"
        :disabled="isSlotDisabled(slot)"
        class="h-12 py-1 flex flex-col items-center justify-center text-sm font-heading font-semibold rounded-xl transition-all duration-200 border active:scale-95 cursor-pointer relative"
        :class="[
          isSlotSelected(slot)
            ? 'bg-brand-vinto text-white border-brand-vinto shadow-xs' 
            : (isSlotDisabled(slot)
                ? 'bg-gray-100 text-gray-400 border-gray-100 cursor-not-allowed opacity-60'
                : 'bg-white text-brand-tinta border-gray-200 hover:border-brand-vinto hover:bg-neutral-light')
        ]"
      >
        <span :class="[isSlotDisabled(slot) ? 'line-through text-gray-400' : '']">
          {{ slot.time || slot }}
        </span>
        <span v-if="isSlotDisabled(slot)" class="text-[9px] font-bold uppercase tracking-wider text-red-500 mt-0.5">
          Penuh
        </span>
      </button>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  slots: {
    type: Array,
    required: true
  },
  modelValue: {
    type: String,
    default: ''
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue'])

function isSlotDisabled(slot) {
  if (slot && typeof slot === 'object') {
    return !slot.is_available
  }
  return false
}

function isSlotSelected(slot) {
  const time = slot && typeof slot === 'object' ? slot.time : slot
  return props.modelValue === time
}

function selectSlot(slot) {
  if (isSlotDisabled(slot)) return
  const time = slot && typeof slot === 'object' ? slot.time : slot
  emit('update:modelValue', time)
}
</script>
