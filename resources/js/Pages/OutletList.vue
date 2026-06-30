<template>
  <div class="space-y-8">
    <div class="max-w-xl space-y-2">
      <h1 class="text-3xl font-heading font-black">Lokasi Outlet Kami</h1>
      <p class="text-sm text-gray-500 font-body">Temukan outlet MORE terdekat dan nikmati pengalaman define session premium.</p>
    </div>

    <!-- Geolocation Trigger button -->
    <div class="flex items-center gap-2">
      <BaseButton @click="detectLocation" variant="ghost" size="sm" :loading="geoLoading">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        Deteksi Lokasi Terdekat
      </BaseButton>
    </div>

    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <BaseLoader skeleton height="220px" v-for="i in 2" :key="i" />
    </div>
    
    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <BaseCard v-for="outlet in outletStore.outlets" :key="outlet.id" padding="none" class="flex flex-col sm:flex-row h-full hover:shadow-md transition-all duration-300">
        <router-link :to="'/outlets/' + outlet.id" class="w-full sm:w-1/3 h-48 sm:h-full min-h-[160px] overflow-hidden bg-gray-100 block">
          <img :src="outlet.image_url" class="w-full h-full object-cover hover:scale-105 transition duration-300" alt="Outlet" />
        </router-link>
        <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
          <div class="space-y-2">
            <div class="flex items-center justify-between">
              <router-link :to="'/outlets/' + outlet.id" class="hover:text-brand-vinto transition">
                <h3 class="text-lg font-heading font-semibold text-brand-tinta">{{ outlet.name }}</h3>
              </router-link>
              <div class="flex items-center gap-1 text-sm font-semibold text-brand-pudar">
                <span>★</span>
                <span>{{ outlet.rating }}</span>
              </div>
            </div>
            <p class="text-xs text-gray-400 font-body leading-relaxed">{{ outlet.address }}</p>
            <p v-if="outlet.distance" class="text-xs text-brand-vinto font-heading font-bold bg-brand-vinto/10 px-2.5 py-1 rounded-full inline-flex items-center gap-1">
              <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>Terpaut {{ outlet.distance.toFixed(1) }} km</span>
            </p>
          </div>
          <div class="flex items-center gap-2">
            <BaseButton @click="bookAtOutlet(outlet)" variant="primary" size="sm" class="flex-1">
              Pesan Sesi
            </BaseButton>
            <router-link :to="'/outlets/' + outlet.id" class="px-4 py-2 border border-gray-200 hover:bg-gray-50 text-gray-600 text-xs font-heading font-bold rounded-xl flex items-center justify-center transition">
              Detail
            </router-link>
          </div>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useOutletStore } from '../stores/outlet'
import { useBookingStore } from '../stores/booking'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseCard from '../Components/ui/BaseCard.vue'
import BaseLoader from '../Components/ui/BaseLoader.vue'

const outletStore = useOutletStore()
const bookingStore = useBookingStore()
const router = useRouter()

const loading = ref(true)
const geoLoading = ref(false)

onMounted(async () => {
  await outletStore.fetchOutlets()
  loading.value = false
})

function detectLocation() {
  geoLoading.value = true
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((position) => {
      outletStore.sortOutletsByDistance(position.coords.latitude, position.coords.longitude)
      geoLoading.value = false
    }, () => {
      geoLoading.value = false
      alert('Gagal mendeteksi lokasi. Pastikan izin lokasi aktif.')
    })
  } else {
    geoLoading.value = false
    alert('Browser Anda tidak mendukung deteksi lokasi.')
  }
}

function bookAtOutlet(outlet) {
  bookingStore.reset()
  bookingStore.bookingData.outlet_id = outlet.id
  bookingStore.selectedOutlet = outlet
  bookingStore.currentStep = 2 // Skip step 1, proceed to service selection
  router.push('/booking')
}
</script>
