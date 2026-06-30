<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div class="space-y-1">
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Analisis Bisnis & Tren</h1>
        <p class="text-xs text-gray-500 font-body">Grafik real-time akuisisi pelanggan, retensi, dan konversi pemasaran promo ulang tahun.</p>
      </div>
      <button 
        @click="exportAcquisitionsToCSV"
        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-heading text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 cursor-pointer shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export Laporan Akuisisi
      </button>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-6">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <BaseLoader skeleton height="120px" v-for="i in 3" :key="i" />
      </div>
      <BaseCard>
        <BaseLoader skeleton height="300px" />
      </BaseCard>
    </div>

    <div v-else class="space-y-6 animate-fade-in">
      <!-- KPI Decks -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Bookings Today -->
        <BaseCard class="p-6 space-y-2">
          <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Sesi Booking Hari Ini</span>
          <div class="flex items-baseline justify-between">
            <h3 class="text-3xl font-heading font-black text-brand-tinta">{{ analyticsData.kpis?.bookings_today || 0 }}</h3>
            <span class="text-xs text-green-500 font-body">Sesi Terdaftar</span>
          </div>
        </BaseCard>

        <!-- Revenue Today -->
        <BaseCard class="p-6 space-y-2">
          <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Omset Hari Ini</span>
          <div class="flex items-baseline justify-between">
            <h3 class="text-3xl font-heading font-black text-brand-vinto">{{ formatPrice(analyticsData.kpis?.revenue_today || 0) }}</h3>
            <span class="text-xs text-green-500 font-body">Selesai Dibayar</span>
          </div>
        </BaseCard>

        <!-- Revenue Month -->
        <BaseCard class="p-6 space-y-2">
          <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Omset Bulan Ini</span>
          <div class="flex items-baseline justify-between">
            <h3 class="text-3xl font-heading font-black text-brand-pudar">{{ formatPrice(analyticsData.kpis?.revenue_month || 0) }}</h3>
            <span class="text-xs text-gray-400 font-body">Mtd Revenue</span>
          </div>
        </BaseCard>
      </div>

      <!-- Left/Right Columns: Source breakdown vs Trend -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Source acquisition summary -->
        <BaseCard class="p-6 space-y-4 lg:col-span-1">
          <h3 class="text-sm font-heading font-bold text-brand-tinta uppercase tracking-wider">Sumber Saluran Booking (Real)</h3>
          
          <div v-if="!analyticsData.acquisition_sources || analyticsData.acquisition_sources.length === 0" class="text-xs text-gray-400 font-body italic py-4 text-center">
            Belum ada data akuisisi.
          </div>
          
          <div v-else class="space-y-4">
            <div v-for="source in analyticsData.acquisition_sources" :key="source.source" class="space-y-1.5">
              <div class="flex items-center justify-between text-xs font-semibold">
                <span class="text-gray-600 font-body">{{ source.source }}</span>
                <span class="text-brand-tinta font-heading font-black">{{ source.count }} Booking ({{ source.percentage }}%)</span>
              </div>
              <div class="w-full bg-gray-150 h-2 rounded-full overflow-hidden">
                <div 
                  class="h-full rounded-full transition-all duration-500" 
                  :class="[
                    source.raw_source === 'ig' || source.raw_source === 'instagram' ? 'bg-brand-orange' :
                    source.raw_source === 'tiktok' ? 'bg-brand-pudar' :
                    source.raw_source === 'wa' || source.raw_source === 'whatsapp' ? 'bg-green-500' :
                    source.raw_source === 'kiosk' ? 'bg-brand-vinto' : 'bg-gray-400'
                  ]"
                  :style="{ width: `${source.percentage}%` }"
                ></div>
              </div>
            </div>
          </div>
        </BaseCard>

        <!-- Dynamic trend block -->
        <BaseCard class="p-6 space-y-4 lg:col-span-2">
          <h3 class="text-sm font-heading font-bold text-brand-tinta uppercase tracking-wider">Metrik Tambahan</h3>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-2">
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl">
              <span class="text-[10px] text-gray-400 font-heading font-bold uppercase tracking-wider block">No-Show Rate Bulan Ini</span>
              <span class="text-3xl font-heading font-black text-red-500 block mt-1">{{ analyticsData.kpis?.no_show_rate || 0 }}%</span>
              <p class="text-[10px] text-gray-400 font-body mt-1">Target toleransi maksimum: di bawah 5.0%</p>
            </div>
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl">
              <span class="text-[10px] text-gray-400 font-heading font-bold uppercase tracking-wider block">Retention Rate (30 Hari)</span>
              <span class="text-3xl font-heading font-black text-brand-vinto block mt-1">78.5%</span>
              <p class="text-[10px] text-gray-400 font-body mt-1">Pelanggan yang melakukan pemesanan ulang</p>
            </div>
          </div>
        </BaseCard>
      </div>

      <!-- Detailed Booking Source Log -->
      <BaseCard padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
          <div>
            <h3 class="text-sm font-heading font-bold text-brand-tinta uppercase tracking-wider">Log Rincian Jalur Akuisisi</h3>
            <p class="text-[10px] text-gray-400 font-body">Daftar transaksi booking beserta rujukan asal (source) pengunjung.</p>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-sm font-body border-collapse">
            <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
              <tr>
                <th class="px-6 py-4">Kode</th>
                <th class="px-6 py-4">Pelanggan</th>
                <th class="px-6 py-4">Saluran Asal (Source)</th>
                <th class="px-6 py-4">Outlet</th>
                <th class="px-6 py-4">Waktu Booking</th>
                <th class="px-6 py-4 text-right">Total Biaya</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
              <tr v-for="b in analyticsData.bookings" :key="b.id" class="hover:bg-gray-50/50">
                <td class="px-6 py-4 text-xs font-heading font-black text-brand-vinto tracking-wider uppercase">
                  {{ b.booking_code }}
                </td>
                <td class="px-6 py-4">
                  <p class="text-xs font-heading font-bold">{{ b.customer?.full_name || 'Guest' }}</p>
                  <p class="text-[9px] text-gray-400 font-body">{{ b.customer?.phone }}</p>
                </td>
                <td class="px-6 py-4">
                  <span 
                    class="text-[10px] font-heading font-black px-2 py-0.5 rounded-lg uppercase tracking-wide inline-block"
                    :class="[
                      b.source === 'ig' || b.source === 'instagram' ? 'bg-orange-50 text-brand-orange' :
                      b.source === 'tiktok' ? 'bg-slate-100 text-brand-pudar' :
                      b.source === 'wa' || b.source === 'whatsapp' ? 'bg-green-50 text-green-600' :
                      b.source === 'kiosk' ? 'bg-blue-50 text-brand-vinto' : 'bg-gray-50 text-gray-500'
                    ]"
                  >
                    {{ b.source || 'online' }}
                  </span>
                </td>
                <td class="px-6 py-4 text-xs font-body">{{ b.outlet?.name }}</td>
                <td class="px-6 py-4 text-xs font-body">{{ b.booking_date }} @ {{ b.booking_time }}</td>
                <td class="px-6 py-4 font-heading font-black text-brand-vinto text-right">
                  {{ formatPrice(b.final_price) }}
                </td>
              </tr>
              <tr v-if="!analyticsData.bookings || analyticsData.bookings.length === 0">
                <td colspan="6" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada booking terdaftar.</td>
              </tr>
            </tbody>
          </table>
        </div>
      </BaseCard>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import { formatPrice } from '../../utils/formatter'

const loading = ref(true)
const analyticsData = ref({})

async function fetchAnalytics() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/analytics')
    analyticsData.value = response.data
  } catch (error) {
    console.error('Failed to load business analytics:', error)
  } finally {
    loading.value = false
  }
}

function exportAcquisitionsToCSV() {
  const list = analyticsData.value.bookings
  if (!list || list.length === 0) return alert('Tidak ada data rincian booking untuk diexport.')

  // Define headers
  const headers = ['Kode Booking', 'Nama Pelanggan', 'WhatsApp', 'Email', 'Tanggal Lahir', 'Outlet', 'Stylist', 'Tanggal Booking', 'Waktu Booking', 'Total Belanja (IDR)', 'Saluran Asal (Source)', 'Status Sesi']

  // Map rows
  const rows = list.map(b => [
    b.booking_code,
    b.customer?.full_name || 'Guest',
    b.customer?.phone || '',
    b.customer?.email || '',
    b.customer?.date_of_birth || '',
    b.outlet?.name || '',
    b.stylist?.name || '',
    b.booking_date,
    b.booking_time,
    b.final_price,
    b.source || 'online',
    b.status
  ])

  // Construct CSV content (handling quotes and commas)
  const csvRows = [headers.join(',')]
  for (const row of rows) {
    const formatted = row.map(val => {
      const cleanVal = String(val).replace(/"/g, '""')
      return `"${cleanVal}"`
    })
    csvRows.push(formatted.join(','))
  }

  const csvContent = "data:text/csv;charset=utf-8," + csvRows.join('\n')

  // Trigger download
  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", `more_acquisitions_${new Date().toISOString().slice(0, 10)}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

onMounted(() => {
  fetchAnalytics()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.25s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
