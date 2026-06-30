<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="show" class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-xs" @click="closeOnBackdrop && close()"></div>

        <!-- Modal Box -->
        <Transition name="slide-up">
          <div
            v-if="show"
            class="relative w-full sm:max-w-lg bg-white rounded-t-3xl sm:rounded-2xl shadow-xl overflow-hidden z-10 max-h-[85vh] sm:max-h-[90vh] flex flex-col transition-all duration-300"
          >
            <!-- Drag bar for mobile bottom-sheet -->
            <div class="h-1.5 w-12 bg-gray-200 rounded-full mx-auto my-3 block sm:hidden"></div>

            <!-- Header -->
            <div class="flex items-center justify-between px-6 pb-4 pt-2 sm:pt-4 border-b border-gray-100">
              <h3 class="text-lg font-heading font-semibold text-brand-tinta">{{ title }}</h3>
              <button @click="close" class="p-1 text-gray-400 hover:text-brand-vinto cursor-pointer rounded-full hover:bg-gray-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-y-auto p-6 font-body text-brand-tinta">
              <slot />
            </div>

            <!-- Footer Action Area -->
            <div v-if="$slots.footer" class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">
              <slot name="footer" />
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import { watch } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    required: true
  },
  title: {
    type: String,
    default: ''
  },
  closeOnBackdrop: {
    type: Boolean,
    default: true
  }
})

const emit = defineEmits(['close'])

function close() {
  emit('close')
}

// Lock body scrolling when modal is open
watch(() => props.show, (newVal) => {
  if (newVal) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.overflow = ''
  }
}, { immediate: true })
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.slide-up-enter-active {
  transition: transform 0.3s ease-out;
}
.slide-up-leave-active {
  transition: transform 0.25s ease-in;
}
@media (max-width: 639px) {
  .slide-up-enter-from, .slide-up-leave-to {
    transform: translateY(100%);
  }
}
@media (min-width: 640px) {
  .slide-up-enter-from, .slide-up-leave-to {
    transform: scale(0.95);
    opacity: 0;
  }
}
</style>
