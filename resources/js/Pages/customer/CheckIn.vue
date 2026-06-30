<template>
  <div class="max-w-md mx-auto text-center space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Scan Check-In Cepat</h1>
      <p class="text-xs text-gray-500 font-body">Tunjukkan barcode/QR ini pada kiosk resepsionis saat tiba di outlet.</p>
    </div>

    <BaseCard class="p-8 space-y-4">
      <div v-if="loading" class="space-y-3">
        <BaseLoader skeleton height="120px" />
      </div>
      <div v-else-if="upcomingBooking" class="space-y-4 flex flex-col items-center">
        <div class="bg-gray-50 py-2 px-6 rounded-xl">
          <span class="text-xs text-gray-400 font-heading font-bold">KODE BOOKING</span>
          <h3 class="text-lg font-heading font-black text-brand-vinto tracking-widest">{{ upcomingBooking.booking_code }}</h3>
        </div>

        <img 
          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + upcomingBooking.booking_code" 
          class="w-40 h-40 object-contain p-2 border border-gray-100 rounded-2xl" 
          alt="QR Code" 
        />

        <div class="text-xs text-gray-500 font-body space-y-1">
          <p>Outlet: <span class="font-semibold text-brand-tinta">{{ upcomingBooking.outlet?.name }}</span></p>
          <p>Waktu: <span class="font-semibold text-brand-tinta">{{ formatShortDate(upcomingBooking.booking_date) }} @ {{ upcomingBooking.booking_time }} WIB</span></p>
        </div>
      </div>
      <div v-else>
        <EmptyState title="Tidak Ada Booking Aktif" message="Anda tidak memiliki pemesanan sesi yang terjadwal." action-text="Booking Sesi Baru" @action="$router.push('/booking')" />
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useCustomerStore } from '../../stores/customer'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import EmptyState from '../../Components/common/EmptyState.vue'
import { formatShortDate } from '../../utils/dateHelper'

const customerStore = useCustomerStore()
const loading = ref(true)
const bookings = ref([])

onMounted(async () => {
  await customerStore.fetchBookings()
  bookings.value = customerStore.bookings
  loading.value = false
})

const upcomingBooking = computed(() => {
  return bookings.value.find(b => b.status === 'booked')
})
</script>
