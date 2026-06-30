<template>
  <div class="min-h-screen bg-brand-tinta text-white flex flex-col justify-between p-8 font-body select-none">
    
    <!-- Top Header -->
    <div class="flex justify-between items-center border-b border-gray-900 pb-6">
      <div class="flex items-center gap-3">
        <button @click="$router.push('/kiosk')" class="p-2 border border-gray-800 rounded-xl hover:bg-gray-900 active:scale-95 text-gray-400">
          ← Menu Utama
        </button>
        <span class="font-heading font-black text-2xl tracking-wider text-brand-vinto">Lounge Queue Board</span>
      </div>
      <span class="text-xs text-green-400 flex items-center gap-1.5 font-heading font-semibold uppercase animate-pulse">
        <span class="w-2.5 h-2.5 bg-green-500 rounded-full"></span> Live Updates
      </span>
    </div>

    <!-- MAIN QUEUE PANELS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 flex-1 my-6 overflow-hidden">
      <!-- 1. WAITING COLUMN -->
      <div class="bg-gray-950/40 border border-gray-900 rounded-3xl p-5 flex flex-col h-full min-h-[300px]">
        <h3 class="font-heading font-black text-base border-b border-gray-900 pb-3 mb-4 flex justify-between">
          <span>Menunggu</span>
          <span class="text-xs font-semibold px-2.5 py-0.5 bg-yellow-500/10 text-yellow-500 rounded-full">{{ waitingList.length }} antrean</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3 pr-1">
          <div v-for="q in waitingList" :key="q.id" class="p-4 bg-gray-950 border border-gray-900 rounded-2xl flex items-center justify-between">
            <div>
              <h4 class="text-2xl font-heading font-black text-brand-pudar tracking-wider">{{ q.queue_number }}</h4>
              <p class="text-xs text-white mt-1">{{ q.booking?.customer?.full_name }}</p>
            </div>
            <div class="text-right text-xs text-gray-500 font-body">
              <p>Stylist: {{ q.booking?.stylist?.name }}</p>
              <p class="mt-0.5 font-bold text-brand-vinto">Wait: {{ q.estimated_wait_time }}m</p>
            </div>
          </div>
          <div v-if="waitingList.length === 0" class="py-12 text-center text-xs text-gray-600 font-body">Tidak ada antrean menunggu.</div>
        </div>
      </div>

      <!-- 2. SERVING COLUMN -->
      <div class="bg-gray-950/40 border border-gray-900 rounded-3xl p-5 flex flex-col h-full min-h-[300px]">
        <h3 class="font-heading font-black text-base border-b border-gray-900 pb-3 mb-4 flex justify-between">
          <span>Sedang Dilayani</span>
          <span class="text-xs font-semibold px-2.5 py-0.5 bg-green-500/10 text-green-500 rounded-full">{{ servingList.length }} antrean</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3 pr-1">
          <div v-for="q in servingList" :key="q.id" class="p-4 bg-brand-vinto/5 border border-brand-vinto/20 rounded-2xl flex items-center justify-between animate-pulse">
            <div>
              <h4 class="text-2xl font-heading font-black text-brand-vinto tracking-wider">{{ q.queue_number }}</h4>
              <p class="text-xs text-white mt-1 font-bold">{{ q.booking?.customer?.full_name }}</p>
            </div>
            <div class="text-right text-xs text-gray-300 font-body">
              <p>Stylist: <span class="font-semibold text-white">{{ q.booking?.stylist?.name }}</span></p>
              <p class="mt-0.5 text-xs text-gray-500">Durasi: {{ q.booking?.duration }}m</p>
            </div>
          </div>
          <div v-if="servingList.length === 0" class="py-12 text-center text-xs text-gray-600 font-body">Belum ada antrean yang diproses.</div>
        </div>
      </div>

      <!-- 3. COMPLETED COLUMN -->
      <div class="bg-gray-950/40 border border-gray-900 rounded-3xl p-5 flex flex-col h-full min-h-[300px]">
        <h3 class="font-heading font-black text-base border-b border-gray-900 pb-3 mb-4 flex justify-between">
          <span>Selesai</span>
          <span class="text-xs font-semibold px-2.5 py-0.5 bg-gray-500/10 text-gray-400 rounded-full">{{ completedList.length }} antrean</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3 pr-1 opacity-60">
          <div v-for="q in completedList" :key="q.id" class="p-4 bg-gray-950 border border-gray-900 rounded-2xl flex items-center justify-between">
            <div>
              <h4 class="text-xl font-heading font-bold text-gray-500 tracking-wider">{{ q.queue_number }}</h4>
              <p class="text-xs text-gray-400 mt-1">{{ q.booking?.customer?.full_name }}</p>
            </div>
            <div class="text-right text-xs text-gray-600 font-body">
              <p>Stylist: {{ q.booking?.stylist?.name }}</p>
              <p class="mt-0.5 text-green-500">Selesai ✓</p>
            </div>
          </div>
          <div v-if="completedList.length === 0" class="py-12 text-center text-xs text-gray-600 font-body">Belum ada antrean selesai hari ini.</div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center text-xs text-gray-600 border-t border-gray-900 pt-6">
      <p>Data diperbarui secara otomatis setiap 10 detik.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const waitingList = ref([])
const servingList = ref([])
const completedList = ref([])
let pollingTimer = null

onMounted(async () => {
  await fetchQueueData()
  pollingTimer = setInterval(fetchQueueData, 10000) // Poll every 10 seconds
})

onUnmounted(() => {
  clearInterval(pollingTimer)
})

async function fetchQueueData() {
  try {
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/staff/queue')
    waitingList.value = response.data.queues?.waiting || []
    servingList.value = response.data.queues?.serving || []
    completedList.value = response.data.queues?.completed || []
  } catch {
    // Fallback Mock data for standalone view without network connectivity
    waitingList.value = [
      { id: 1, queue_number: 'Q-001', estimated_wait_time: 15, booking: { customer: { full_name: 'Anto Wijaya' }, stylist: { name: 'Alexandre Dumas' } } },
      { id: 2, queue_number: 'W-001', estimated_wait_time: 45, booking: { customer: { full_name: 'Dodi Hermawan' }, stylist: { name: 'Vito Corleone' } } }
    ]
    servingList.value = [
      { id: 3, queue_number: 'Q-002', booking: { customer: { full_name: 'Rian Wijaya' }, stylist: { name: 'Rian Hidayat' }, duration: 45 } }
    ]
    completedList.value = [
      { id: 4, queue_number: 'W-002', booking: { customer: { full_name: 'Jane Doe' }, stylist: { name: 'Siti Aminah' } } }
    ]
  }
}
</script>
