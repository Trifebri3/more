<template>
  <div class="flex items-center justify-between py-4 border-t border-gray-100">
    <!-- Mobile: Prev/Next -->
    <div class="flex justify-between flex-1 sm:hidden">
      <button
        @click="prevPage"
        :disabled="currentPage === 1"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-brand-tinta bg-white border border-gray-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none"
      >
        Kembali
      </button>
      <button
        @click="nextPage"
        :disabled="currentPage === totalPages"
        class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium rounded-xl text-brand-tinta bg-white border border-gray-200 active:scale-95 disabled:opacity-50 disabled:pointer-events-none"
      >
        Lanjut
      </button>
    </div>

    <!-- Desktop: Page numbers and totals -->
    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-500 font-body">
          Menampilkan halaman <span class="font-semibold text-brand-tinta">{{ currentPage }}</span> dari <span class="font-semibold text-brand-tinta">{{ totalPages }}</span>
        </p>
      </div>
      <div>
        <nav class="relative z-0 inline-flex -space-x-px rounded-xl shadow-xs" aria-label="Pagination">
          <button
            @click="prevPage"
            :disabled="currentPage === 1"
            class="relative inline-flex items-center px-3 py-2 rounded-l-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
          >
            <span class="sr-only">Previous</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
          
          <button
            v-for="page in totalPages"
            :key="page"
            @click="setPage(page)"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-all duration-200"
            :class="[
              currentPage === page
                ? 'z-10 bg-brand-vinto border-brand-vinto text-white'
                : 'bg-white border-gray-200 text-gray-500 hover:bg-gray-50'
            ]"
          >
            {{ page }}
          </button>

          <button
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="relative inline-flex items-center px-3 py-2 rounded-r-xl border border-gray-200 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
          >
            <span class="sr-only">Next</span>
            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
              <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  currentPage: {
    type: Number,
    required: true
  },
  totalPages: {
    type: Number,
    required: true
  }
})

const emit = defineEmits(['change'])

function setPage(page) {
  emit('change', page)
}

function prevPage() {
  if (props.currentPage > 1) {
    emit('change', props.currentPage - 1)
  }
}

function nextPage() {
  if (props.currentPage < props.totalPages) {
    emit('change', props.currentPage + 1)
  }
}
</script>
