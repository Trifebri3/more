<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta">Jadwal & Riwayat</h1>
        <p class="text-xs text-gray-500 font-body">Pantau seluruh agenda mencukur mendatang dan rekam jejak kerja Anda.</p>
      </div>
    </div>

    <!-- Segmented Tabs -->
    <div class="flex border-b border-gray-200">
      <button 
        @click="switchTab('upcoming')"
        class="px-6 py-3 font-heading font-bold text-sm border-b-2 transition cursor-pointer"
        :class="[
          activeTab === 'upcoming' 
            ? 'border-brand-vinto text-brand-vinto' 
            : 'border-transparent text-gray-400 hover:text-brand-tinta'
        ]"
      >
        Jadwal Mendatang
      </button>
      <button 
        @click="switchTab('history')"
        class="px-6 py-3 font-heading font-bold text-sm border-b-2 transition cursor-pointer"
        :class="[
          activeTab === 'history' 
            ? 'border-brand-vinto text-brand-vinto' 
            : 'border-transparent text-gray-400 hover:text-brand-tinta'
        ]"
      >
        Riwayat Pekerjaan
      </button>
    </div>

    <!-- Content Panel -->
    <div class="bg-white border border-gray-100 p-6 rounded-2xl shadow-xs">
      <div v-if="loading" class="space-y-4">
        <BaseLoader skeleton height="70px" v-for="i in 3" :key="i" />
      </div>

      <div v-else-if="bookings.length === 0">
        <EmptyState 
          :title="activeTab === 'upcoming' ? 'Kalender Kosong' : 'Belum Ada Riwayat'" 
          :message="activeTab === 'upcoming' ? 'Tidak ada pemesanan jadwal mencukur di masa mendatang.' : 'Anda belum menyelesaikan sesi potong rambut apa pun.'" 
        />
      </div>

      <!-- Bookings List -->
      <div v-else class="space-y-4 animate-fade-in">
        <div 
          v-for="booking in bookings" 
          :key="booking.id" 
          class="p-4 border border-gray-100 hover:border-gray-200 rounded-xl flex items-center justify-between gap-4 transition duration-150"
        >
          <div class="space-y-1">
            <div class="flex items-center gap-2 flex-wrap">
              <span class="text-xs font-heading font-bold text-brand-vinto bg-brand-vinto/10 px-2 py-0.5 rounded-md">
                {{ formatDate(booking.booking_date) }} @ {{ booking.booking_time }}
              </span>
              <span class="text-[10px] font-heading font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full"
                :class="[
                  booking.status === 'completed' ? 'bg-green-50 text-green-600 border border-green-100' :
                  ['cancelled', 'no_show'].includes(booking.status) ? 'bg-red-50 text-red-600 border border-red-100' :
                  booking.status === 'in_progress' ? 'bg-amber-50 text-amber-600 border border-amber-100' :
                  'bg-blue-50 text-blue-600 border border-blue-100'
                ]"
              >
                {{ booking.status }}
              </span>
            </div>
            <h4 class="text-sm font-heading font-bold text-brand-tinta">{{ booking.customer?.full_name }}</h4>
            <p class="text-xs text-gray-500 font-body">Layanan: {{ getServicesText(booking) }} • Durasi: {{ booking.duration }} menit</p>
          </div>

          <button 
            @click="openDetail(booking)"
            class="px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-heading font-bold rounded-lg border border-gray-100 hover:bg-gray-100 cursor-pointer"
          >
            Detail
          </button>
        </div>
      </div>
    </div>

    <!-- Booking Detail Modal (Duplicated for coherence) -->
    <div 
      v-if="showModal && selectedBooking" 
      class="fixed inset-0 bg-brand-tinta/80 flex items-center justify-center p-4 z-50 animate-fade-in"
      @click.self="closeDetail"
    >
      <div class="bg-white border border-gray-100 w-full max-w-md rounded-3xl shadow-xl overflow-hidden animate-slide-up">
        <!-- Modal Header -->
        <div class="p-6 bg-brand-tinta text-white flex items-center justify-between">
          <div>
            <span class="text-[10px] font-heading font-bold text-gray-400 tracking-widest uppercase">Kode Booking</span>
            <h3 class="text-xl font-heading font-black text-brand-vinto leading-none mt-1">
              {{ selectedBooking.booking_code }}
            </h3>
          </div>
          <button @click="closeDetail" class="text-gray-400 hover:text-white transition cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto">
          <!-- Customer Info Section -->
          <div class="space-y-2">
            <h5 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest">Detail Pelanggan</h5>
            <div class="p-4 bg-gray-50 rounded-2xl space-y-2">
              <div class="flex justify-between text-sm">
                <span class="text-gray-400 font-body">Nama Lengkap</span>
                <span class="font-heading font-bold text-brand-tinta">{{ selectedBooking.customer?.full_name }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-400 font-body">No. WhatsApp</span>
                <span class="font-heading font-bold text-brand-tinta">{{ selectedBooking.customer?.whatsapp }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-400 font-body">Email</span>
                <span class="font-heading font-bold text-brand-tinta">{{ selectedBooking.customer?.email || '-' }}</span>
              </div>
            </div>
          </div>

          <!-- Services Treatment Section -->
          <div class="space-y-2">
            <h5 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest">Layanan Yang Dipilih</h5>
            <div class="space-y-2">
              <div 
                v-for="detail in selectedBooking.details" 
                :key="detail.id"
                class="flex items-center justify-between p-3 border border-gray-100 rounded-xl"
              >
                <div>
                  <span class="text-sm font-heading font-bold text-brand-tinta">{{ detail.service?.name }}</span>
                  <p class="text-[10px] text-gray-400 font-body mt-0.5">Durasi: {{ detail.service?.duration }} menit</p>
                </div>
                <span class="text-sm font-heading font-black text-brand-tinta">
                  Rp {{ formatMoney(detail.price) }}
                </span>
              </div>
            </div>
          </div>

          <!-- Financial Breakdown -->
          <div class="space-y-2">
            <h5 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest">Rincian Pembayaran</h5>
            <div class="p-4 bg-gray-50 border border-gray-100 rounded-2xl space-y-2 font-body text-sm">
              <div class="flex justify-between">
                <span class="text-gray-500">Harga Awal</span>
                <span class="font-bold text-brand-tinta">Rp {{ formatMoney(selectedBooking.total_price) }}</span>
              </div>
              <div class="flex justify-between text-green-600">
                <span>Diskon</span>
                <span class="font-bold">- Rp {{ formatMoney(selectedBooking.discount_amount) }}</span>
              </div>
              <div class="border-t border-gray-200 my-2 pt-2 flex justify-between font-heading font-black text-base text-brand-tinta">
                <span>Total Bersih</span>
                <span class="text-brand-vinto">Rp {{ formatMoney(selectedBooking.final_price) }}</span>
              </div>
            </div>
          </div>

          <!-- Notes -->
          <div v-if="selectedBooking.notes" class="space-y-2">
            <h5 class="text-xs font-heading font-bold text-gray-400 uppercase tracking-widest">Catatan / Request</h5>
            <p class="p-4 bg-brand-natural/30 text-sm text-brand-tinta rounded-2xl font-body leading-relaxed">
              "{{ selectedBooking.notes }}"
            </p>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="p-6 bg-gray-50 border-t border-gray-100 flex items-center justify-end">
          <button 
            @click="closeDetail"
            class="px-5 py-2.5 bg-brand-tinta text-white text-sm font-heading font-bold rounded-xl cursor-pointer hover:opacity-90"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import EmptyState from '../../Components/common/EmptyState.vue'

const loading = ref(true)
const bookings = ref([])
const activeTab = ref('upcoming')

// Modal States
const showModal = ref(false)
const selectedBooking = ref(null)

async function fetchSchedule() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const isHistory = activeTab.value === 'history'
    const response = await api.get(`/v1/stylist/schedule?history=${isHistory}`)
    bookings.value = response.data.bookings || []
  } catch (error) {
    console.error('Failed to load stylist schedule list:', error)
  } finally {
    loading.value = false
  }
}

function switchTab(tab) {
  activeTab.value = tab
  fetchSchedule()
}

function getServicesText(booking) {
  if (booking.details && booking.details.length > 0) {
    return booking.details.map(d => d.service?.name).join(', ')
  }
  return 'Layanan Haircut'
}

function openDetail(booking) {
  selectedBooking.value = booking
  showModal.value = true
}

function closeDetail() {
  showModal.value = false
  selectedBooking.value = null
}

function formatDate(dateStr) {
  const d = new Date(dateStr)
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
}

function formatMoney(amount) {
  return number_format(amount, 0, ',', '.')
}

function number_format(number, decimals, decPoint, thousandsSep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  const n = !isFinite(+number) ? 0 : +number
  const prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  const sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  const dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  let s = ''
  const toFixedFix = function(n, prec) {
    const k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k).toFixed(prec)
  }
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }
  return s.join(dec)
}

onMounted(() => {
  fetchSchedule()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.15s ease-out forwards;
}
.animate-slide-up {
  animation: slideUp 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideUp {
  from { transform: translateY(16px); opacity: 0; }
  to { transform: translateY(0); opacity: 1; }
}
</style>
