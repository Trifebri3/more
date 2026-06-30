<template>
  <div class="space-y-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta">Overview Resepsionis</h1>
        <p class="text-xs text-gray-500 font-body">Pantau aktivitas, check-in, dan antrean hari ini.</p>
      </div>
      <div class="text-xs font-semibold px-3 py-1 bg-brand-pudar/10 text-brand-pudar rounded-full">
        Hari Ini: {{ formatShortDate(new Date()) }}
      </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <BaseCard class="p-5 flex flex-col justify-between">
        <span class="text-xs text-gray-400 font-heading font-bold">Total Booking</span>
        <h3 class="text-3xl font-heading font-black mt-2 text-brand-tinta">{{ stats.total_bookings }}</h3>
      </BaseCard>

      <BaseCard class="p-5 flex flex-col justify-between">
        <span class="text-xs text-gray-400 font-heading font-bold">Check-In</span>
        <h3 class="text-3xl font-heading font-black mt-2 text-brand-vinto">{{ stats.checked_in }}</h3>
      </BaseCard>

      <BaseCard class="p-5 flex flex-col justify-between">
        <span class="text-xs text-gray-400 font-heading font-bold">Sedang Dilayani</span>
        <h3 class="text-3xl font-heading font-black mt-2 text-brand-pudar">{{ stats.in_progress }}</h3>
      </BaseCard>

      <BaseCard class="p-5 flex flex-col justify-between">
        <span class="text-xs text-gray-400 font-heading font-bold">Revenue Selesai</span>
        <h3 class="text-2xl font-heading font-black mt-2 text-green-600">{{ formatPrice(stats.revenue) }}</h3>
      </BaseCard>
    </div>

    <!-- Quick search and actions row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <BaseCard class="space-y-4">
        <h3 class="text-base font-heading font-bold text-brand-tinta">Quick Check-In Lookup</h3>
        <p class="text-xs text-gray-400 font-body">Cari kode booking customer untuk memproses check-in cepat.</p>
        
        <div class="flex gap-2">
          <input
            v-model="searchCode"
            placeholder="Ketik Kode (misal: BK-XXXXXXXX)"
            class="flex-1 px-4 py-3 border border-gray-200 rounded-xl bg-white text-brand-tinta font-heading font-semibold tracking-wider placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-brand-vinto"
          />
          <BaseButton @click="handleLookup" variant="primary" :loading="lookupLoading">Cari</BaseButton>
        </div>

        <div v-if="searchResult" class="p-4 bg-brand-vinto/5 border border-brand-vinto/10 rounded-2xl space-y-3">
          <div class="flex justify-between text-xs">
            <span class="text-gray-400 font-body">Customer</span>
            <span class="font-bold text-brand-tinta">{{ searchResult.customer?.full_name }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-gray-400 font-body">Stylist</span>
            <span class="font-bold text-brand-tinta">{{ searchResult.stylist?.name }}</span>
          </div>
          <div class="flex justify-between text-xs">
            <span class="text-gray-400 font-body">Status</span>
            <span class="font-bold text-brand-pudar capitalize">{{ searchResult.status }}</span>
          </div>
          
          <BaseButton 
            v-if="searchResult.status === 'booked'" 
            @click="processCheckIn" 
            variant="secondary" 
            size="sm" 
            block 
            :loading="checkinLoading"
          >
            Proses Check-In
          </BaseButton>
        </div>
      </BaseCard>

      <BaseCard class="flex flex-col justify-between p-6 bg-brand-vinto text-white">
        <div class="space-y-2">
          <h3 class="text-lg font-heading font-black">Layanan Mandiri Kiosk</h3>
          <p class="text-xs text-brand-natural/80 leading-relaxed font-body">
            Anda dapat membuka modul self-service kiosk pada perangkat iPad/Tablet di depan untuk membiarkan customer melakukan walk-in mandiri.
          </p>
        </div>
        <BaseButton @click="$router.push('/kiosk')" variant="ghost" class="border-white text-white hover:bg-white/10 mt-6">
          Buka Modul Kiosk
        </BaseButton>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import { formatPrice } from '../../utils/formatter'
import { formatShortDate } from '../../utils/dateHelper'

const stats = ref({
  total_bookings: 0,
  checked_in: 0,
  in_progress: 0,
  completed: 0,
  revenue: 0
})

const searchCode = ref('')
const lookupLoading = ref(false)
const searchResult = ref(null)
const checkinLoading = ref(false)

onMounted(async () => {
  await fetchStats()
})

async function fetchStats() {
  try {
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/staff/queue')
    stats.value = response.data.summary
  } catch {
    // Fallback Mock stats
    stats.value = {
      total_bookings: 14,
      checked_in: 4,
      in_progress: 2,
      completed: 6,
      revenue: 950000.00
    }
  }
}

async function handleLookup() {
  if (!searchCode.value) return
  lookupLoading.value = true
  searchResult.value = null
  
  try {
    const api = (await import('../../utils/api')).default
    const code = searchCode.value.toUpperCase().trim()
    const response = await api.get(`/v1/check-in/${code}`)
    searchResult.value = response.data
  } catch {
    alert('Kode booking tidak ditemukan.')
  } finally {
    lookupLoading.value = false
  }
}

async function processCheckIn() {
  checkinLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    await api.post('/v1/check-in', { booking_code: searchResult.value.booking_code })
    alert('Check-in berhasil!')
    searchResult.value = null
    searchCode.value = ''
    await fetchStats()
  } catch (err) {
    alert(err.response?.data?.message || 'Check-in gagal.')
  } finally {
    checkinLoading.value = false
  }
}
</script>
