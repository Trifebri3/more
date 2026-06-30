<template>
  <div class="space-y-8">
    <div class="max-w-xl space-y-2">
      <h1 class="text-3xl font-heading font-black">Menu Layanan</h1>
      <p class="text-sm text-gray-500 font-body">Silakan telusuri ragam perawatan rambut dan kulit kepala premium yang kami sediakan.</p>
    </div>

    <!-- Tabs for category -->
    <Tabs v-model="serviceStore.selectedCategorySlug" :tabs="categoryTabs" />

    <!-- Search Bar -->
    <div class="relative max-w-md w-full mb-6">
      <input
        v-model="serviceStore.searchQuery"
        placeholder="Cari layanan (misal: Haircut, Tinting...)"
        class="w-full px-4 py-3 pl-10 border border-gray-200 rounded-xl bg-white text-base text-brand-tinta focus:outline-none focus:ring-2 focus:ring-brand-vinto"
      />
      <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
      </div>
    </div>

    <!-- Services Grid -->
    <div v-if="serviceStore.loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <BaseLoader skeleton height="160px" v-for="i in 4" :key="i" />
    </div>

    <div v-else-if="serviceStore.filteredServices.length === 0" class="py-12 text-center">
      <p class="text-gray-500 font-body">Layanan tidak ditemukan.</p>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <BaseCard v-for="service in serviceStore.filteredServices" :key="service.id" class="flex hover:shadow-md transition-all duration-300">
        <div class="flex-1 space-y-4">
          <div>
            <span class="text-[10px] font-heading font-bold text-brand-vinto uppercase tracking-wider bg-brand-vinto/10 px-2.5 py-1 rounded-full">
              {{ service.category?.name }}
            </span>
            <h3 class="text-base font-heading font-bold text-brand-tinta mt-2">{{ service.name }}</h3>
            <p class="text-xs text-gray-400 font-body mt-0.5">{{ service.duration }} menit</p>
            <p class="text-xs text-gray-500 font-body leading-relaxed mt-2 line-clamp-2">{{ service.description }}</p>
          </div>
          <div class="flex items-center justify-between pt-2 border-t border-gray-50">
            <span class="text-base font-heading font-black text-brand-tinta">{{ formatPrice(service.price) }}</span>
            <BaseButton @click="bookService(service)" variant="primary" size="sm">
              Pesan Layanan
            </BaseButton>
          </div>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useServiceStore } from '../stores/service'
import { useBookingStore } from '../stores/booking'
import Tabs from '../Components/ui/Tabs.vue'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseCard from '../Components/ui/BaseCard.vue'
import BaseLoader from '../Components/ui/BaseLoader.vue'
import { formatPrice } from '../utils/formatter'

const serviceStore = useServiceStore()
const bookingStore = useBookingStore()
const router = useRouter()

onMounted(async () => {
  await serviceStore.fetchServices()
})

const categoryTabs = computed(() => {
  const defaultTab = [{ label: 'Semua Layanan', value: 'all' }]
  const mapped = serviceStore.categories.map(cat => ({
    label: cat.name,
    value: cat.slug
  }))
  return [...defaultTab, ...mapped]
})

function bookService(service) {
  bookingStore.reset()
  bookingStore.bookingData.service_ids.push(service.id)
  bookingStore.selectedServices.push(service)
  bookingStore.currentStep = 1 // start from outlet selection
  router.push('/booking')
}
</script>
