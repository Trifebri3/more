<template>
  <div class="space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Riwayat Booking</h1>
      <p class="text-xs text-gray-500 font-body">Pantau jadwal aktif dan riwayat kunjungan sesi potong rambut Anda.</p>
    </div>

    <BaseCard padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div v-if="loading" class="p-6 space-y-3">
        <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
      </div>
      <div v-else-if="bookings.length === 0" class="p-6">
        <EmptyState title="Belum Ada Sesi" message="Anda belum melakukan pemesanan sesi grooming." action-text="Booking Sekarang" @action="$router.push('/booking')" />
      </div>
      <div v-else class="divide-y divide-gray-150 text-sm">
        <div v-for="booking in bookings" :key="booking.id" class="p-5 flex justify-between items-center hover:bg-gray-50/50">
          <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-brand-natural text-brand-vinto flex items-center justify-center flex-shrink-0">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7a1 1 0 11-2 0 1 1 0 012 0zM3.879 3.879L8.8 8.8m0 0l-5.13-5.13M8.8 8.8a1 1 0 112 0 1 1 0 01-2 0z" />
              </svg>
            </div>
            <div>
              <div class="flex items-center gap-2">
                <span class="font-heading font-bold text-brand-tinta">{{ booking.outlet?.name }}</span>
                <span class="text-[9px] font-heading font-bold px-2 py-0.5 rounded-full capitalize"
                  :class="booking.status === 'completed' ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600'"
                >
                  {{ booking.status }}
                </span>
              </div>
              <p class="text-xs text-gray-500 mt-0.5 font-body">Stylist: {{ booking.stylist?.name }}</p>
              <p class="text-[10px] text-gray-400 mt-0.5 font-body">{{ formatShortDate(booking.booking_date) }} @ {{ booking.booking_time }} WIB</p>
            </div>
          </div>
          <span class="font-heading font-black text-brand-tinta">{{ formatPrice(booking.final_price) }}</span>
        </div>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useCustomerStore } from '../../stores/customer'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import EmptyState from '../../Components/common/EmptyState.vue'
import { formatPrice } from '../../utils/formatter'
import { formatShortDate } from '../../utils/dateHelper'

const customerStore = useCustomerStore()
const loading = ref(true)
const bookings = ref([])

onMounted(async () => {
  await customerStore.fetchBookings()
  bookings.value = customerStore.bookings
  loading.value = false
})
</script>
