<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Sesi Booking</h1>
        <p class="text-xs text-gray-500 font-body">Pantau, perbarui status, atau batalkan reservasi sesi salon.</p>
      </div>

      <!-- Filters -->
      <div class="flex gap-2">
        <select v-model="statusFilter" @change="fetchBookings" class="bg-white border border-gray-150 rounded-xl px-3 py-2 text-xs font-heading font-bold uppercase tracking-wider text-brand-tinta cursor-pointer outline-none">
          <option value="">Semua Status</option>
          <option value="booked">Booked</option>
          <option value="checked_in">Checked In</option>
          <option value="in_progress">In Progress</option>
          <option value="completed">Completed</option>
          <option value="cancelled">Cancelled</option>
          <option value="no_show">No Show</option>
        </select>
      </div>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Booking history catalog -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Kode Booking</th>
              <th class="px-6 py-4">Pelanggan</th>
              <th class="px-6 py-4">Outlet / Stylist</th>
              <th class="px-6 py-4">Waktu</th>
              <th class="px-6 py-4">Harga</th>
              <th class="px-6 py-4">Source</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="booking in bookings" :key="booking.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4 text-xs font-heading font-black text-brand-vinto tracking-wider uppercase">{{ booking.booking_code }}</td>
              <td class="px-6 py-4">
                <p class="text-sm font-heading font-bold">{{ booking.customer?.full_name || 'Guest' }}</p>
                <p class="text-[10px] text-gray-400 font-body">{{ booking.customer?.phone }}</p>
              </td>
              <td class="px-6 py-4">
                <p class="text-xs text-brand-tinta font-body font-bold">{{ booking.outlet?.name || 'Grand Indonesia' }}</p>
                <p class="text-[10px] text-gray-400 font-body">Stylist: {{ booking.stylist?.name }}</p>
              </td>
              <td class="px-6 py-4 text-xs font-body">{{ formatShortDate(booking.booking_date) }} @ {{ booking.booking_time }}</td>
              <td class="px-6 py-4 font-heading font-bold text-brand-vinto">{{ formatPrice(booking.final_price) }}</td>
              <td class="px-6 py-4">
                <span class="text-[10px] font-heading font-black px-2 py-1 bg-gray-100 text-gray-600 rounded-lg uppercase tracking-wide">
                  {{ booking.source || 'online' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="text-[9px] font-heading font-bold px-2.5 py-0.5 rounded-full capitalize"
                  :class="[
                    booking.status === 'completed' ? 'bg-green-50 text-green-600' :
                    booking.status === 'checked_in' || booking.status === 'in_progress' ? 'bg-blue-50 text-blue-600' :
                    booking.status === 'cancelled' ? 'bg-red-50 text-red-600' :
                    booking.status === 'no_show' ? 'bg-orange-50 text-orange-600' :
                    'bg-yellow-50 text-yellow-600'
                  ]"
                >
                  {{ booking.status }}
                </span>
              </td>
              <td class="px-6 py-4 text-right space-x-1.5 whitespace-nowrap">
                <button v-if="booking.status === 'booked'" @click="updateStatus(booking.id, 'checked_in')" class="px-2.5 py-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 font-heading text-[10px] font-bold uppercase tracking-wider cursor-pointer">
                  Check-In
                </button>
                <button v-if="['booked', 'checked_in', 'in_progress'].includes(booking.status)" @click="updateStatus(booking.id, 'completed')" class="px-2.5 py-1.5 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 font-heading text-[10px] font-bold uppercase tracking-wider cursor-pointer">
                  Selesai
                </button>
                <button v-if="['booked'].includes(booking.status)" @click="updateStatus(booking.id, 'cancelled')" class="px-2.5 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-[10px] font-bold uppercase tracking-wider cursor-pointer">
                  Cancel
                </button>
              </td>
            </tr>
            <tr v-if="bookings.length === 0">
              <td colspan="7" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada booking terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import { formatPrice } from '../../utils/formatter'
import { formatShortDate } from '../../utils/dateHelper'

const bookings = ref([])
const loading = ref(true)
const statusFilter = ref('')

onMounted(async () => {
  await fetchBookings()
})

async function fetchBookings() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    let url = '/v1/bookings'
    if (statusFilter.value) {
      url += `?status=${statusFilter.value}`
    }
    const res = await api.get(url)
    bookings.value = res.data
  } catch (err) {
    console.error('Error fetching bookings:', err)
  } finally {
    loading.value = false
  }
}

async function updateStatus(id, newStatus) {
  if (confirm(`Apakah Anda yakin ingin mengubah status menjadi ${newStatus}?`)) {
    try {
      const api = (await import('../../utils/api')).default
      await api.put(`/v1/bookings/${id}`, { status: newStatus })
      alert('Status booking berhasil diperbarui!')
      await fetchBookings()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal mengubah status booking.')
    }
  }
}
</script>
