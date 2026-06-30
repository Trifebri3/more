<template>
  <div class="space-y-8">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Dashboard Saya</h1>
        <p class="text-xs text-gray-500 font-body">Selamat datang kembali, {{ authStore.customer?.full_name || authStore.user?.name }}!</p>
      </div>
      <span class="text-[10px] font-heading font-bold uppercase tracking-wider px-3 py-1 bg-brand-vinto/15 text-brand-vinto rounded-full">
        Tier: {{ authStore.customer?.membership_status || 'Regular' }}
      </span>
    </div>

    <!-- Loyalty Card Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <BaseCard class="bg-brand-pudar text-white p-6 relative overflow-hidden flex flex-col justify-between min-h-[160px] md:col-span-2 border border-gray-800">
        <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-brand-vinto/10 rounded-full blur-xl"></div>
        
        <div class="flex justify-between items-start z-10">
          <div>
            <span class="text-[10px] uppercase tracking-widest text-brand-vinto font-heading font-bold">MORE Membership</span>
            <h3 class="text-lg font-heading font-black tracking-wider text-white mt-1">GENTLEMAN CARD</h3>
          </div>
          <span class="text-2xl font-black text-brand-vinto">G</span>
        </div>

        <div class="z-10 mt-6">
          <p class="text-[10px] text-gray-400 font-body">Loyalty Points</p>
          <div class="flex items-baseline gap-2">
            <span class="text-3xl font-heading font-black text-white">{{ authStore.customer?.loyalty_points || 0 }}</span>
            <span class="text-xs text-brand-vinto font-body">poin</span>
          </div>

          <!-- Points progress bar -->
          <div class="w-full bg-gray-800 h-1 rounded-full mt-2 overflow-hidden">
            <div class="bg-brand-vinto h-full" :style="{ width: Math.min((authStore.customer?.loyalty_points || 0) / 500 * 100, 100) + '%' }"></div>
          </div>
          <p class="text-[9px] text-gray-500 mt-1.5 font-body">Kumpulkan 500 poin untuk upgrade ke Silver Tier</p>
        </div>
      </BaseCard>

      <!-- Quick Check In QR Code Card -->
      <BaseCard class="p-6 flex flex-col items-center justify-center text-center bg-white border border-gray-150 shadow-sm">
        <h4 class="text-[10px] font-heading font-bold text-gray-400 uppercase tracking-widest mb-3">Check-In Cepat</h4>
        
        <div v-if="upcomingBooking" class="space-y-3 flex flex-col items-center">
          <img 
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=' + upcomingBooking.booking_code"
            class="w-24 h-24 object-contain p-2 border border-gray-100 rounded-xl"
            alt="Checkin QR" 
          />
          <div>
            <p class="text-xs font-heading font-black text-brand-vinto tracking-wider">
              {{ upcomingBooking.booking_code }}
            </p>
            <p class="text-[9px] text-gray-400 font-body mt-0.5">
              Scan di outlet saat kedatangan
            </p>
          </div>
        </div>
        <div v-else class="py-4 space-y-3 flex flex-col items-center">
          <div class="w-10 h-10 bg-brand-natural rounded-full flex items-center justify-center text-brand-tinta">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <p class="text-xs text-gray-500 font-body">Belum ada booking aktif terdekat.</p>
          <BaseButton @click="$router.push('/booking')" variant="ghost" size="sm" class="border-gray-200">
            Pesan Sesi
          </BaseButton>
        </div>
      </BaseCard>
    </div>

    <!-- Booking History Sections -->
    <div class="space-y-4">
      <h3 class="text-base font-heading font-bold text-brand-tinta uppercase tracking-wider">Riwayat Sesi Saya</h3>
      
      <div v-if="loading" class="space-y-3">
        <BaseLoader skeleton height="80px" v-for="i in 2" :key="i" />
      </div>

      <div v-else-if="customerStore.bookings.length === 0">
        <EmptyState title="Belum Ada Riwayat" message="Sesi perawatan Anda selanjutnya akan tampil di sini setelah memesan." action-text="Pesan Sekarang" @action="$router.push('/booking')" />
      </div>

      <div v-else class="space-y-3">
        <div
          v-for="booking in customerStore.bookings"
          :key="booking.id"
          class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-4 bg-white border border-gray-150 rounded-2xl gap-4 shadow-sm"
        >
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-natural text-brand-vinto flex items-center justify-center">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7a1 1 0 11-2 0 1 1 0 012 0zM3.879 3.879L8.8 8.8m0 0l-5.13-5.13M8.8 8.8a1 1 0 112 0 1 1 0 01-2 0z" />
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="text-sm font-heading font-bold text-brand-tinta">
                  {{ booking.outlet?.name }}
                </span>
                <span class="text-[9px] font-heading font-bold px-2.5 py-0.5 rounded-full capitalize"
                  :class="[
                    booking.status === 'completed' ? 'bg-green-50 text-green-600' :
                    booking.status === 'checked_in' || booking.status === 'in_progress' ? 'bg-blue-50 text-blue-600' :
                    booking.status === 'cancelled' ? 'bg-red-50 text-red-600' :
                    'bg-yellow-50 text-yellow-600'
                  ]"
                >
                  {{ booking.status }}
                </span>
              </div>
              <p class="text-xs text-gray-500 font-body mt-0.5">
                Stylist: <span class="font-semibold text-brand-tinta">{{ booking.stylist?.name }}</span>
              </p>
              <p class="text-[10px] text-gray-400 font-body mt-1">
                {{ formatShortDate(booking.booking_date) }} • {{ booking.booking_time }} WIB
              </p>
            </div>
          </div>

          <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 border-gray-50 pt-2 sm:pt-0">
            <div class="text-left sm:text-right">
              <p class="text-[10px] text-gray-400 font-body">Total Biaya</p>
              <p class="text-sm font-heading font-black text-brand-tinta">{{ formatPrice(booking.final_price) }}</p>
            </div>

            <!-- Review Button or Detail QR trigger -->
            <BaseButton v-if="booking.status === 'booked'" @click="showQR(booking.booking_code)" variant="ghost" size="sm" class="border-gray-200">
              Lihat QR
            </BaseButton>
          </div>
        </div>
      </div>
    </div>

    <!-- QR Detail Modal -->
    <BaseModal :show="qrModalOpen" title="Scan QR Code Check-In" @close="qrModalOpen = false">
      <div class="text-center space-y-4 py-4">
        <div class="bg-neutral-light/20 p-4 rounded-2xl border border-gray-200 inline-block">
          <img 
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + activeQR" 
            class="w-48 h-48 object-contain mx-auto" 
            alt="Checkin QR" 
          />
        </div>
        <div>
          <h3 class="text-lg font-heading font-black tracking-widest text-brand-vinto">{{ activeQR }}</h3>
          <p class="text-xs text-gray-400 font-body mt-1">Tunjukkan kode QR ini ke resepsionis untuk melakukan check-in instan.</p>
        </div>
      </div>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useCustomerStore } from '../../stores/customer'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import BaseModal from '../../Components/ui/BaseModal.vue'
import EmptyState from '../../Components/common/EmptyState.vue'
import { formatPrice } from '../../utils/formatter'
import { formatShortDate } from '../../utils/dateHelper'

const authStore = useAuthStore()
const customerStore = useCustomerStore()

const loading = ref(true)
const qrModalOpen = ref(false)
const activeQR = ref('')

onMounted(async () => {
  await customerStore.fetchBookings()
  loading.value = false
})

const upcomingBooking = computed(() => {
  return customerStore.bookings.find(b => b.status === 'booked')
})

function showQR(code) {
  activeQR.value = code
  qrModalOpen.value = true
}
</script>
