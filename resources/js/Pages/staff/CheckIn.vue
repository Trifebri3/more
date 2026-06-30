<template>
  <div class="max-w-md mx-auto space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Check-In Manual</h1>
      <p class="text-xs text-gray-500">Scan QR Code customer atau masukkan kode booking secara manual di bawah.</p>
    </div>

    <BaseCard class="space-y-4">
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body text-center">
        {{ successMsg }}
      </div>

      <div v-if="errorMsg" class="p-4 bg-red-50 text-red-700 border border-red-100 rounded-xl text-sm font-body text-center">
        {{ errorMsg }}
      </div>

      <form @submit.prevent="handleCheckIn" class="space-y-4">
        <BaseInput v-model="bookingCode" label="Kode Booking (BK-XXXXXXXX)" required />
        
        <BaseButton type="submit" variant="primary" block :loading="loading">
          Proses Check-In
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseCard from '../../Components/ui/BaseCard.vue'

const bookingCode = ref('')
const loading = ref(false)
const successMsg = ref('')
const errorMsg = ref('')

async function handleCheckIn() {
  if (!bookingCode.value) return
  loading.value = true
  errorMsg.value = ''
  successMsg.value = ''

  try {
    const api = (await import('../../utils/api')).default
    const response = await api.post('/v1/check-in', { booking_code: bookingCode.value.toUpperCase().trim() })
    successMsg.value = 'Check-in berhasil! Pelanggan telah ditambahkan ke antrean.'
    bookingCode.value = ''
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Gagal check-in. Kode booking tidak valid.'
  } finally {
    loading.value = false
  }
}
</script>
