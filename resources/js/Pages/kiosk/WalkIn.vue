<template>
  <div class="min-h-screen bg-brand-noir text-white flex flex-col justify-between p-8 font-body select-none">
    
    <!-- Top Header -->
    <div class="flex justify-between items-center border-b border-gray-900 pb-6">
      <div class="flex items-center gap-3">
        <button @click="backToHome" class="p-2 border border-gray-800 rounded-xl hover:bg-gray-900 active:scale-95 text-gray-400 cursor-pointer">
          ← Kembali
        </button>
        <span class="font-heading font-black text-2xl tracking-wider text-brand-vinto uppercase">Walk-In</span>
      </div>
      <span class="text-xs text-gray-400 uppercase font-heading font-semibold">Langkah {{ currentStep }} dari 4</span>
    </div>

    <!-- MAIN FORM BODY -->
    <div class="max-w-xl w-full mx-auto my-auto py-6">
      
      <!-- STEP 1: NAMA & WHATSAPP -->
      <div v-if="currentStep === 1" class="space-y-6">
        <div class="text-center space-y-2">
          <h2 class="text-2xl font-heading font-black uppercase">Data Kontak Anda</h2>
          <p class="text-xs text-gray-400 font-body">Masukkan nama dan WhatsApp Anda. Kami akan mengirimkan notifikasi giliran Anda.</p>
        </div>

        <div class="space-y-4">
          <div class="relative w-full">
            <input
              v-model="form.customer_name"
              placeholder="Nama Lengkap Anda"
              class="w-full px-5 py-4 border border-gray-800 bg-gray-950 rounded-2xl text-lg text-white font-heading placeholder-gray-600 focus:outline-none focus:ring-2 focus:ring-brand-vinto focus:border-brand-vinto"
            />
          </div>

          <!-- Prepend +62 phone helper -->
          <div class="relative flex items-center border border-gray-800 bg-gray-950 rounded-2xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-vinto">
            <div class="px-4 py-4 bg-gray-900 border-r border-gray-800 text-gray-400 font-heading font-semibold text-base">
              +62
            </div>
            <input
              type="tel"
              v-model="displayPhone"
              @input="onPhoneInput"
              placeholder="812-3456-7890 (Nomor WhatsApp)"
              class="w-full px-5 py-4 text-lg text-white font-body placeholder-gray-600 focus:outline-none"
            />
          </div>
        </div>

        <BaseButton @click="goToStep(2)" :disabled="!form.customer_name || !form.whatsapp" variant="secondary" size="lg" block>
          Lanjut Pilih Layanan
        </BaseButton>
      </div>

      <!-- STEP 2: PILIH LAYANAN -->
      <div v-if="currentStep === 2" class="space-y-6">
        <div class="text-center space-y-2">
          <h2 class="text-2xl font-heading font-black uppercase">Pilih Layanan</h2>
          <p class="text-xs text-gray-400 font-body">Pilih perawatan rambut yang Anda inginkan hari ini.</p>
        </div>

        <div v-if="loadingServices" class="space-y-3">
          <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
        </div>
        <div v-else class="space-y-3 max-h-[300px] overflow-y-auto pr-1">
          <div
            v-for="service in services"
            :key="service.id"
            @click="form.service_id = service.id"
            class="flex justify-between items-center p-4 bg-gray-950 border rounded-2xl active:scale-[0.98] transition-all cursor-pointer select-none"
            :class="form.service_id === service.id ? 'border-brand-vinto bg-brand-vinto/10' : 'border-gray-900 hover:border-gray-800'"
          >
            <div>
              <h4 class="font-heading font-semibold text-white text-base">{{ service.name }}</h4>
              <p class="text-xs text-gray-400 font-body mt-0.5">{{ service.duration }} menit</p>
            </div>
            <span class="font-heading font-bold text-brand-vinto text-base">{{ formatPrice(service.price) }}</span>
          </div>
        </div>

        <div class="flex gap-4">
          <BaseButton @click="currentStep = 1" variant="ghost" size="lg" block class="border-gray-800 text-gray-400 hover:bg-gray-900">Kembali</BaseButton>
          <BaseButton @click="goToStep(3)" :disabled="!form.service_id" variant="secondary" size="lg" block>Lanjut</BaseButton>
        </div>
      </div>

      <!-- STEP 3: PILIH STYLIST + WAITING TIME -->
      <div v-if="currentStep === 3" class="space-y-6">
        <div class="text-center space-y-2">
          <h2 class="text-2xl font-heading font-black uppercase">Pilih Stylist</h2>
          <p class="text-xs text-gray-400 font-body">Pilih stylist pilihan Anda. Waktu tunggu saat ini tercantum di bawah.</p>
        </div>

        <div v-if="loadingStylists" class="space-y-3">
          <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
        </div>
        <div v-else class="space-y-3">
          <!-- Option 1: Bebas / Stylist Tercepat -->
          <div
            @click="selectBebasStylist"
            class="flex items-center justify-between p-4 bg-gray-950 border rounded-2xl active:scale-[0.98] transition-all cursor-pointer select-none"
            :class="form.stylist_id === 'any' ? 'border-brand-vinto bg-brand-vinto/10' : 'border-gray-900 hover:border-gray-800'"
          >
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-full bg-brand-vinto/25 flex items-center justify-center text-brand-vinto">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <h4 class="font-heading font-semibold text-white">Stylist Tercepat (Siapa Saja)</h4>
                <p class="text-xs text-gray-400 font-body">Sistem akan memilih stylist pertama yang menyelesaikan layanannya.</p>
              </div>
            </div>
            <span class="text-xs text-green-500 font-bold bg-green-500/10 px-3 py-1 rounded-full">Estimasi: Instan</span>
          </div>

          <!-- Stylist Cards -->
          <div
            v-for="stylist in stylists"
            :key="stylist.id"
            @click="form.stylist_id = stylist.id"
            class="flex items-center justify-between p-4 bg-gray-950 border rounded-2xl active:scale-[0.98] transition-all cursor-pointer select-none"
            :class="form.stylist_id === stylist.id ? 'border-brand-vinto bg-brand-vinto/10' : 'border-gray-900 hover:border-gray-800'"
          >
            <div class="flex items-center gap-3">
              <img :src="stylist.avatar_url" class="w-10 h-10 rounded-full object-cover" />
              <div>
                <h4 class="font-heading font-semibold text-white">{{ stylist.name }}</h4>
                <p class="text-xs text-gray-400 font-body">Spesialisasi: {{ stylist.specialty }}</p>
              </div>
            </div>
            <span class="text-xs text-brand-vinto font-bold bg-brand-vinto/10 px-3 py-1 rounded-full">
              Estimasi: {{ getRandomWaitTime() }} m
            </span>
          </div>
        </div>

        <div class="flex gap-4">
          <BaseButton @click="currentStep = 2" variant="ghost" size="lg" block class="border-gray-800 text-gray-400 hover:bg-gray-900">Kembali</BaseButton>
          <BaseButton @click="goToStep(4)" :disabled="!form.stylist_id" variant="secondary" size="lg" block>Review Pendaftaran</BaseButton>
        </div>
      </div>

      <!-- STEP 4: KONFIRMASI & TICKET -->
      <div v-if="currentStep === 4" class="space-y-6">
        <div class="text-center space-y-2">
          <h2 class="text-2xl font-heading font-black uppercase">Konfirmasi Pendaftaran</h2>
          <p class="text-xs text-gray-400 font-body">Verifikasi kembali data diri Anda sebelum mengambil tiket antrean.</p>
        </div>

        <div class="bg-gray-950 p-6 rounded-3xl border border-gray-900 space-y-4 text-sm font-body">
          <div class="flex justify-between border-b border-gray-900 pb-2">
            <span class="text-gray-400">Pelanggan</span>
            <span class="font-heading font-bold text-white">{{ form.customer_name }} ({{ form.whatsapp }})</span>
          </div>
          <div class="flex justify-between border-b border-gray-900 pb-2">
            <span class="text-gray-400">Layanan</span>
            <span class="font-heading font-bold text-white">{{ selectedServiceName }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-900 pb-2">
            <span class="text-gray-400">Stylist</span>
            <span class="font-heading font-bold text-white">{{ selectedStylistName }}</span>
          </div>
        </div>

        <BaseButton @click="submitWalkIn" variant="secondary" size="lg" :loading="submitLoading" block>
          Ambil Nomor Antrean
        </BaseButton>
      </div>

      <!-- STEP 5: TICKET SUCCESS SCREEN -->
      <div v-if="currentStep === 5" class="space-y-8 py-6 text-center">
        <div class="flex flex-col items-center space-y-3">
          <div class="w-16 h-16 bg-brand-vinto/15 text-brand-vinto rounded-full flex items-center justify-center">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
            </svg>
          </div>
          <h2 class="text-3xl font-heading font-black text-white uppercase">Tiket Antrean Dibuat</h2>
          <p class="text-xs text-gray-400 font-body max-w-sm mx-auto">
            Notifikasi WhatsApp telah dikirimkan ke nomor Anda. Silakan duduk santai di lounge kami.
          </p>
        </div>

        <div class="max-w-xs mx-auto bg-white text-brand-tinta rounded-3xl p-6 shadow-2xl border-t-8 border-brand-vinto space-y-4">
          <div class="bg-gray-50 py-2 rounded-xl">
            <span class="text-[9px] text-gray-400 font-heading font-bold tracking-wider">NOMOR ANTREAN</span>
            <h1 class="text-4xl font-heading font-black tracking-wider text-brand-vinto mt-1">
              {{ successDetails?.queue_number }}
            </h1>
          </div>
          
          <div class="text-left space-y-1.5 text-xs font-body border-t border-gray-100 pt-4">
            <div class="flex justify-between">
              <span class="text-gray-400">Pelanggan</span>
              <span class="font-semibold text-brand-tinta">{{ form.customer_name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Estimasi Tunggu</span>
              <span class="font-semibold text-brand-vinto">{{ successDetails?.estimated_wait_time }} menit</span>
            </div>
          </div>
        </div>

        <p class="text-xs text-gray-600 animate-pulse font-body">Kiosk akan otomatis kembali ke menu utama dalam {{ countdown }} detik...</p>

        <BaseButton @click="backToHome" variant="ghost" class="border-gray-800 text-gray-400 hover:bg-gray-900 mx-auto">
          Kembali Sekarang
        </BaseButton>
      </div>

    </div>

    <!-- Kiosk Footer Info -->
    <div class="flex justify-between items-center text-xs text-gray-600 border-t border-gray-900 pt-6">
      <p>MORE Grooming Kiosk PWA. Selalu bersih, aman, dan higienis.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import { formatPrice } from '../../utils/formatter'

const router = useRouter()

const currentStep = ref(1)
const displayPhone = ref('')
const loadingServices = ref(false)
const loadingStylists = ref(false)
const submitLoading = ref(false)

const form = reactive({
  customer_name: '',
  whatsapp: '',
  service_id: null,
  stylist_id: null,
  outlet_id: 1 // Grand Indonesia is default for kiosk
})

const services = ref([])
const stylists = ref([])
const successDetails = ref(null)
const countdown = ref(10)
let countdownTimer = null

// Strip country code prepends visually
function onPhoneInput(e) {
  let val = e.target.value.replace(/\D/g, '')
  if (val.startsWith('0')) val = val.slice(1)
  displayPhone.value = val
  form.whatsapp = val ? `+62${val}` : ''
}

async function goToStep(step) {
  currentStep.value = step
  
  if (step === 2 && services.value.length === 0) {
    loadingServices.value = true
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/services')
    services.value = response.data.services || []
    loadingServices.value = false
  }

  if (step === 3 && stylists.value.length === 0) {
    loadingStylists.value = true
    const api = (await import('../../utils/api')).default
    const response = await api.get('/v1/stylists?outlet_id=1')
    stylists.value = response.data || []
    loadingStylists.value = false
  }
}

const selectedServiceName = computed(() => {
  const srv = services.value.find(s => s.id === form.service_id)
  return srv ? srv.name : ''
})

const selectedStylistName = computed(() => {
  if (form.stylist_id === 'any') return 'Bebas (Stylist Tercepat)'
  const sty = stylists.value.find(s => s.id === form.stylist_id)
  return sty ? sty.name : ''
})

function selectBebasStylist() {
  form.stylist_id = 'any'
}

function getRandomWaitTime() {
  return Math.floor(Math.random() * 3) * 15 + 15
}

async function submitWalkIn() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const actualStylistId = form.stylist_id === 'any' ? stylists.value[0]?.id : form.stylist_id
    
    const payload = {
      customer_name: form.customer_name,
      whatsapp: form.whatsapp,
      service_id: form.service_id,
      stylist_id: actualStylistId,
      outlet_id: 1
    }

    const response = await api.post('/v1/walk-in', payload)
    successDetails.value = response.data
    currentStep.value = 5

    // Start 10 seconds auto-redirect countdown
    countdown.value = 10
    countdownTimer = setInterval(() => {
      countdown.value--
      if (countdown.value <= 0) {
        backToHome()
      }
    }, 1000)
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mendaftarkan walk-in.')
  } finally {
    submitLoading.value = false
  }
}

function backToHome() {
  if (countdownTimer) clearInterval(countdownTimer)
  router.push('/kiosk')
}
</script>
