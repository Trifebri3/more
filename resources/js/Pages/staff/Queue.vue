<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta">Papan Antrean</h1>
        <p class="text-xs text-gray-500 font-body">Kelola jalannya antrean, proses check-in, dan selesaikan pelayanan.</p>
      </div>
      <BaseButton @click="fetchQueue" variant="ghost" size="sm" :loading="loading">
        🔄 Refresh Board
      </BaseButton>
    </div>

    <!-- Kanban Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      
      <!-- WAITING COLUMN -->
      <div class="bg-gray-100/60 rounded-3xl p-5 flex flex-col min-h-[400px]">
        <h3 class="font-heading font-black text-sm text-brand-tinta border-b border-gray-200 pb-3 mb-4 flex justify-between">
          <span>Menunggu</span>
          <span class="text-xs px-2.5 py-0.5 bg-yellow-500/10 text-yellow-500 rounded-full font-bold">{{ waiting.length }}</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3">
          <div v-for="q in waiting" :key="q.id" class="p-4 bg-white border border-gray-100 rounded-2xl shadow-xs space-y-3">
            <div class="flex justify-between items-start">
              <div>
                <h4 class="text-xl font-heading font-black text-brand-pudar tracking-wider">{{ q.queue_number }}</h4>
                <p class="text-xs font-heading font-semibold text-brand-tinta mt-1">{{ q.booking?.customer?.full_name }}</p>
              </div>
              <span class="text-[10px] text-gray-400 font-body">Est: {{ q.estimated_wait_time }}m</span>
            </div>
            
            <div class="text-xs text-gray-500 font-body border-t border-gray-50 pt-2 space-y-1">
              <p>Stylist: <span class="font-semibold text-brand-tinta">{{ q.booking?.stylist?.name }}</span></p>
              <p>Source: <span class="capitalize text-brand-vinto">{{ q.booking?.source || 'kiosk' }}</span></p>
            </div>

            <div class="flex gap-2 pt-2">
              <BaseButton @click="updateStatus(q.id, 'serving')" variant="primary" size="sm" class="flex-1 text-xs">Mulai</BaseButton>
              <button @click="updateStatus(q.id, 'skipped')" class="px-3 py-1 bg-red-50 hover:bg-red-100 text-red-500 rounded-lg text-xs font-semibold cursor-pointer">Lewati</button>
            </div>
          </div>
          <div v-if="waiting.length === 0" class="py-12 text-center text-xs text-gray-400 font-body">Antrean kosong.</div>
        </div>
      </div>

      <!-- SERVING COLUMN -->
      <div class="bg-gray-100/60 rounded-3xl p-5 flex flex-col min-h-[400px]">
        <h3 class="font-heading font-black text-sm text-brand-tinta border-b border-gray-200 pb-3 mb-4 flex justify-between">
          <span>Melayani</span>
          <span class="text-xs px-2.5 py-0.5 bg-brand-vinto/10 text-brand-vinto rounded-full font-bold">{{ serving.length }}</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3">
          <div v-for="q in serving" :key="q.id" class="p-4 bg-brand-vinto/5 border border-brand-vinto/10 rounded-2xl shadow-xs space-y-3">
            <div class="flex justify-between items-start">
              <div>
                <h4 class="text-xl font-heading font-black text-brand-vinto tracking-wider">{{ q.queue_number }}</h4>
                <p class="text-xs font-heading font-semibold text-brand-tinta mt-1">{{ q.booking?.customer?.full_name }}</p>
              </div>
              <span class="text-[10px] text-gray-400 font-body">{{ q.booking?.duration }}m</span>
            </div>
            
            <div class="text-xs text-gray-500 font-body border-t border-gray-50 pt-2 space-y-1">
              <p>Stylist: <span class="font-semibold text-brand-tinta">{{ q.booking?.stylist?.name }}</span></p>
            </div>

            <BaseButton @click="updateStatus(q.id, 'completed')" variant="secondary" size="sm" block class="text-xs">Selesaikan</BaseButton>
          </div>
          <div v-if="serving.length === 0" class="py-12 text-center text-xs text-gray-400 font-body">Tidak ada yang sedang dilayani.</div>
        </div>
      </div>

      <!-- COMPLETED COLUMN -->
      <div class="bg-gray-100/60 rounded-3xl p-5 flex flex-col min-h-[400px]">
        <h3 class="font-heading font-black text-sm text-brand-tinta border-b border-gray-200 pb-3 mb-4 flex justify-between">
          <span>Selesai Hari Ini</span>
          <span class="text-xs px-2.5 py-0.5 bg-gray-500/10 text-gray-500 rounded-full font-bold">{{ completed.length }}</span>
        </h3>
        <div class="flex-1 overflow-y-auto space-y-3 opacity-70">
          <div v-for="q in completed" :key="q.id" class="p-4 bg-white border border-gray-200 rounded-2xl shadow-xs space-y-2">
            <div class="flex justify-between items-center">
              <h4 class="text-lg font-heading font-bold text-gray-500 tracking-wider">{{ q.queue_number }}</h4>
              <span class="text-[10px] text-green-600 font-bold bg-green-50 px-2 py-0.5 rounded-full">Selesai ✓</span>
            </div>
            <p class="text-xs font-heading font-semibold text-brand-tinta">{{ q.booking?.customer?.full_name }}</p>
            <p class="text-xs text-gray-400 font-body">Stylist: {{ q.booking?.stylist?.name }}</p>
          </div>
          <div v-if="completed.length === 0" class="py-12 text-center text-xs text-gray-400 font-body">Belum ada antrean selesai hari ini.</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseButton from '../../Components/ui/BaseButton.vue'

const waiting = ref([])
const serving = ref([])
const completed = ref([])
const loading = ref(false)

onMounted(async () => {
  await fetchQueue()
})

async function fetchQueue() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/staff/queue')
    waiting.value = response.data.queues?.waiting || []
    serving.value = response.data.queues?.serving || []
    completed.value = response.data.queues?.completed || []
  } catch {
    // Fallback Mock Board Data
    waiting.value = [
      { id: 1, queue_number: 'Q-001', estimated_wait_time: 15, booking: { customer: { full_name: 'Anto Wijaya' }, stylist: { name: 'Alexandre Dumas' }, source: 'online' } }
    ]
    serving.value = [
      { id: 2, queue_number: 'Q-002', booking: { customer: { full_name: 'Rian Wijaya' }, stylist: { name: 'Rian Hidayat' }, duration: 45 } }
    ]
    completed.value = [
      { id: 3, queue_number: 'W-002', booking: { customer: { full_name: 'Jane Doe' }, stylist: { name: 'Siti Aminah' } } }
    ]
  } finally {
    loading.value = false
  }
}

async function updateStatus(queueId, status) {
  try {
    const api = (await import('../../utils/api')).default
    // The DashboardController has an update method or a direct post to checkin
    await api.post(`/v1/check-in`, { queue_id: queueId, status: status }) // simulated endpoints
    alert('Status antrean berhasil diperbarui!')
    await fetchQueue()
  } catch {
    // Simulate updating locally for mock demonstration
    if (status === 'serving') {
      const item = waiting.value.find(q => q.id === queueId)
      if (item) {
        waiting.value = waiting.value.filter(q => q.id !== queueId)
        item.status = 'serving'
        serving.value.push(item)
      }
    } else if (status === 'completed') {
      const item = serving.value.find(q => q.id === queueId)
      if (item) {
        serving.value = serving.value.filter(q => q.id !== queueId)
        item.status = 'completed'
        completed.value.push(item)
      }
    } else if (status === 'skipped') {
      waiting.value = waiting.value.filter(q => q.id !== queueId)
    }
    alert('Status antrean diperbarui (Demo Mode)!')
  }
}
</script>
