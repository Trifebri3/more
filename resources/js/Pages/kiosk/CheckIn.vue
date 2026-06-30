<template>
  <div class="min-h-screen bg-brand-tinta text-white flex flex-col justify-between p-8 font-body select-none">
    
    <!-- Top Header -->
    <div class="flex justify-between items-center border-b border-gray-900 pb-6">
      <div class="flex items-center gap-3">
        <button @click="backToHome" class="p-2 border border-gray-800 rounded-xl hover:bg-gray-900 active:scale-95 text-gray-400">
          ← Kembali
        </button>
        <span class="font-heading font-black text-2xl tracking-wider text-brand-vinto">Check-In</span>
      </div>
      <span class="text-xs text-gray-400 uppercase font-heading font-semibold">Mandiri</span>
    </div>

    <!-- Center layout -->
    <div class="max-w-md w-full mx-auto my-auto py-12 space-y-8">
      <div class="text-center space-y-2">
        <h2 class="text-2xl font-heading font-black">Masukkan Kode Booking</h2>
        <p class="text-xs text-gray-400">Ketik 8 digit kode booking yang Anda terima di WhatsApp / Email.</p>
      </div>

      <div v-if="successMsg" class="p-4 bg-green-500/10 border border-green-500/20 text-green-400 rounded-2xl text-sm font-body text-center space-y-3">
        <p class="font-heading font-bold text-base">{{ successMsg }}</p>
        <p class="text-xs text-gray-400">Antrean: <span class="font-heading font-black text-lg text-brand-pudar">{{ successQueue }}</span></p>
        <p class="text-[10px] text-gray-500">Kembali ke menu utama dalam {{ countdown }} detik...</p>
      </div>

      <div v-else-if="errorMsg" class="p-4 bg-red-500/10 border border-red-500/20 text-red-400 rounded-2xl text-sm font-body text-center">
        {{ errorMsg }}
      </div>

      <form v-if="!successMsg" @submit.prevent="handleCheckIn" class="space-y-4">
        <div class="relative w-full">
          <input
            v-model="bookingCode"
            placeholder="BK-XXXXXXXX"
            class="w-full px-5 py-4 border border-gray-800 bg-gray-950 rounded-2xl text-2xl text-white text-center font-heading font-black tracking-widest uppercase placeholder-gray-800 focus:outline-none focus:ring-2 focus:ring-brand-vinto focus:border-brand-vinto"
            required
            maxlength="11"
          />
        </div>

        <BaseButton type="submit" variant="secondary" size="lg" :loading="loading" block>
          Check-In Sekarang
        </BaseButton>
      </form>
    </div>

    <!-- Footer -->
    <div class="flex justify-between items-center text-xs text-gray-600 border-t border-gray-900 pt-6">
      <p>Scan QR code pada kamera tablet jika tersedia.</p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../Components/ui/BaseButton.vue'

const router = useRouter()

const bookingCode = ref('')
const loading = ref(false)
const successMsg = ref('')
const successQueue = ref('')
const errorMsg = ref('')
const countdown = ref(5)
let timer = null

async function handleCheckIn() {
  if (!bookingCode.value) return
  loading.value = true
  errorMsg.value = ''
  successMsg.value = ''

  try {
    const api = (await import('../../utils/api')).default
    const code = bookingCode.value.toUpperCase().trim()
    const response = await api.post('/v1/check-in', { booking_code: code })
    
    successMsg.value = response.data.message
    successQueue.value = response.data.queue?.queue_number
    
    // Countdown to redirect
    countdown.value = 5
    timer = setInterval(() => {
      countdown.value--
      if (countdown.value <= 0) {
        backToHome()
      }
    }, 1000)
  } catch (err) {
    errorMsg.value = err.response?.data?.message || 'Kode booking tidak ditemukan atau sudah check-in.'
  } finally {
    loading.value = false
  }
}

function backToHome() {
  if (timer) clearInterval(timer)
  router.push('/kiosk')
}
</script>
