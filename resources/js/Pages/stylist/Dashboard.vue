<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta">Dashboard Stylist</h1>
        <p class="text-xs text-gray-500 font-body">Kendalikan jadwal harian, antrean pelanggan, dan status layanan Anda.</p>
      </div>
      <div v-if="stylist" class="flex items-center gap-3 bg-white p-3 border border-gray-100 rounded-2xl shadow-xs">
        <img 
          :src="stylist.avatar_url || '/images/logo.png'" 
          class="w-10 h-10 rounded-full object-cover border border-gray-100" 
          alt="Avatar" 
        />
        <div>
          <h4 class="text-sm font-heading font-bold text-brand-tinta leading-tight">{{ stylist.name }}</h4>
          <p class="text-[10px] text-brand-vinto font-body font-bold uppercase tracking-wider">{{ stylist.specialty || 'Professional Stylist' }}</p>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
      <!-- Stat 1 -->
      <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-brand-vinto/10 text-brand-vinto flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <div class="text-2xl font-heading font-black text-brand-tinta leading-none">
            {{ stats.today_count || 0 }}
          </div>
          <div class="text-[11px] text-gray-400 font-body font-medium mt-1 uppercase tracking-wider">Antrean Hari Ini</div>
        </div>
      </div>

      <!-- Stat 2 -->
      <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <div class="text-2xl font-heading font-black text-brand-tinta leading-none">
            {{ stats.completed_count || 0 }}
          </div>
          <div class="text-[11px] text-gray-400 font-body font-medium mt-1 uppercase tracking-wider">Selesai Dicukur</div>
        </div>
      </div>

      <!-- Stat 3 -->
      <div class="bg-white border border-gray-100 p-5 rounded-2xl shadow-xs flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-500 flex items-center justify-center shrink-0">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
          </svg>
        </div>
        <div>
          <div class="text-2xl font-heading font-black text-brand-tinta leading-none">
            {{ stats.rating ? stats.rating.toFixed(2) : '5.00' }}
          </div>
          <div class="text-[11px] text-gray-400 font-body font-medium mt-1 uppercase tracking-wider">Rating Kepuasan</div>
        </div>
      </div>
    </div>

    <!-- Active Timeline / List -->
    <div class="bg-white border border-gray-100 rounded-2xl shadow-xs p-6 space-y-4">
      <div class="flex items-center justify-between border-b border-gray-50 pb-4">
        <h3 class="text-base font-heading font-bold text-brand-tinta">Jadwal Sesi Hari Ini</h3>
        <span class="text-xs text-gray-400 font-body">Tanggal: {{ todayFormatted }}</span>
      </div>

      <div v-if="loading" class="space-y-4">
        <BaseLoader skeleton height="85px" v-for="i in 3" :key="i" />
      </div>

      <div v-else-if="bookings.length === 0" class="py-8">
        <EmptyState title="Selesai Semua!" message="Tidak ada antrean tersisa untuk Anda hari ini." />
      </div>

      <div v-else class="space-y-4">
        <div 
          v-for="booking in bookings" 
          :key="booking.id" 
          class="group border border-gray-100 hover:border-gray-200 p-4 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4 transition duration-150"
        >
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-brand-natural/30 rounded-xl flex flex-col items-center justify-center shrink-0 text-brand-tinta">
              <span class="text-sm font-heading font-black leading-tight">{{ booking.booking_time }}</span>
              <span class="text-[8px] font-heading font-bold uppercase tracking-wider opacity-60">WIB</span>
            </div>
            
            <div class="space-y-1">
              <div class="flex items-center gap-2 flex-wrap">
                <h4 class="text-base font-heading font-bold text-brand-tinta">{{ booking.customer?.full_name }}</h4>
                <span class="text-[10px] font-heading font-semibold uppercase tracking-wider px-2 py-0.5 rounded-full"
                  :class="[
                    booking.status === 'checked_in' ? 'bg-blue-50 text-blue-600 border border-blue-100' :
                    booking.status === 'in_progress' ? 'bg-amber-50 text-amber-600 border border-amber-100' :
                    'bg-zinc-50 text-zinc-500 border border-zinc-100'
                  ]"
                >
                  {{ booking.status === 'checked_in' ? 'Check In' : booking.status === 'in_progress' ? 'Mulai Cukur' : 'Booked' }}
                </span>
              </div>
              <p class="text-xs text-gray-500 font-body">Layanan: {{ getServicesText(booking) }} • Durasi: {{ booking.duration }}m</p>
              <p class="text-xs text-gray-400 font-body">WA: {{ booking.customer?.whatsapp || '-' }}</p>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center gap-2 self-end sm:self-center">
            <button 
              @click="openDetail(booking)"
              class="px-3 py-1.5 bg-gray-50 text-gray-600 text-xs font-heading font-bold rounded-lg border border-gray-100 hover:bg-gray-100 cursor-pointer"
            >
              Detail
            </button>

            <!-- State-specific Actions -->
            <button 
              v-if="booking.status === 'booked'"
              @click="updateStatus(booking.id, 'checked_in')"
              class="px-3 py-1.5 bg-blue-600 text-white text-xs font-heading font-bold rounded-lg hover:bg-blue-700 cursor-pointer"
            >
              Check In
            </button>

            <button 
              v-if="booking.status === 'checked_in'"
              @click="updateStatus(booking.id, 'in_progress')"
              class="px-3 py-1.5 bg-amber-500 text-white text-xs font-heading font-bold rounded-lg hover:bg-amber-600 cursor-pointer"
            >
              Mulai Cukur
            </button>

            <button 
              v-if="booking.status === 'in_progress'"
              @click="updateStatus(booking.id, 'completed')"
              class="px-3 py-1.5 bg-green-600 text-white text-xs font-heading font-bold rounded-lg hover:bg-green-700 cursor-pointer"
            >
              Selesai
            </button>

            <!-- Cancellation Option -->
            <button 
              v-if="['booked', 'checked_in'].includes(booking.status)"
              @click="updateStatus(booking.id, 'cancelled')"
              class="px-2 py-1.5 text-red-500 hover:bg-red-50 rounded-lg cursor-pointer transition"
              title="Batalkan Booking"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Booking Detail Modal -->
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
import { ref, onMounted, computed } from 'vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import EmptyState from '../../Components/common/EmptyState.vue'

const loading = ref(true)
const bookings = ref([])
const stylist = ref(null)
const stats = ref({ today_count: 0, completed_count: 0, rating: 5.0 })

// Modal View States
const showModal = ref(false)
const selectedBooking = ref(null)

const todayFormatted = computed(() => {
  const d = new Date()
  return d.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' })
})

async function fetchDashboard() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/stylist/schedule')
    bookings.value = response.data.bookings || []
    stylist.value = response.data.stylist
    stats.value = response.data.stats || { today_count: 0, completed_count: 0, rating: 5.0 }
  } catch (error) {
    console.error('Failed to load stylist schedule:', error)
  } finally {
    loading.value = false
  }
}

async function updateStatus(bookingId, newStatus) {
  if (newStatus === 'cancelled' && !confirm('Apakah Anda yakin ingin membatalkan booking ini?')) {
    return
  }
  try {
    const api = (await import('../../utils/api')).default
    await api.put(`/v1/bookings/${bookingId}`, { status: newStatus })
    await fetchDashboard()
  } catch (error) {
    alert('Gagal memperbarui status: ' + (error.response?.data?.message || error.message))
  }
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
  fetchDashboard()
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
