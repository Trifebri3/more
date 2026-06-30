<template>
  <div class="fixed bottom-20 right-6 sm:bottom-6 sm:right-6 z-50 font-heading">
    <!-- Floating Button Trigger -->
    <button @click="togglePanel" 
      class="w-12 h-12 rounded-full bg-brand-vinto text-white shadow-xl hover:scale-105 active:scale-95 flex items-center justify-center transition-all duration-300 cursor-pointer border border-white/10"
      aria-label="Pengaturan Aksesibilitas dan Suara">
      <svg v-if="!isOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
      </svg>
      <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Control Panel -->
    <div v-if="isOpen" 
      class="absolute bottom-16 right-0 w-80 bg-[#1C1B1A] border border-zinc-800 rounded-3xl p-6 shadow-2xl space-y-6 text-white backdrop-blur-md">
      <div>
        <h3 class="text-sm font-black uppercase tracking-wider text-brand-vinto">Aksesibilitas & Asisten Suara</h3>
        <p class="text-[10px] text-zinc-400 font-body mt-0.5">Sesuaikan tampilan atau kendalikan dengan perintah suara.</p>
      </div>

      <!-- VOICE COMMAND SECTION -->
      <div class="space-y-3 border-t border-zinc-800 pt-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-300">Asisten Suara</h4>
        <div class="flex items-center gap-3 bg-zinc-900/60 border border-zinc-800 rounded-2xl p-3">
          <button @click="toggleVoiceListening" 
            :class="[isListening ? 'bg-red-500 animate-pulse' : 'bg-brand-vinto']"
            class="w-10 h-10 rounded-full flex items-center justify-center text-white shrink-0 cursor-pointer shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
            </svg>
          </button>
          <div class="text-xs font-body leading-tight">
            <p class="font-semibold text-zinc-200">{{ voiceStatus }}</p>
            <p class="text-[10px] text-zinc-500 mt-0.5">Katakan "booking", "lokasi", atau "bantuan".</p>
          </div>
        </div>
      </div>

      <!-- VISUAL CONTROLS -->
      <div class="space-y-4 border-t border-zinc-800 pt-4">
        <h4 class="text-xs font-bold uppercase tracking-wider text-zinc-300">Pengaturan Tampilan</h4>
        
        <!-- Text Size -->
        <div class="space-y-1.5">
          <label class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Ukuran Teks</label>
          <div class="grid grid-cols-3 gap-2">
            <button @click="setTextSize('normal')" :class="[textSize === 'normal' ? 'bg-brand-vinto text-white border-brand-vinto' : 'bg-zinc-900 text-zinc-400 border-zinc-800']" class="text-[10px] py-2 rounded-xl font-bold uppercase border cursor-pointer">
              Normal
            </button>
            <button @click="setTextSize('large')" :class="[textSize === 'large' ? 'bg-brand-vinto text-white border-brand-vinto' : 'bg-zinc-900 text-zinc-400 border-zinc-800']" class="text-[10px] py-2 rounded-xl font-bold uppercase border cursor-pointer">
              Besar
            </button>
            <button @click="setTextSize('xlarge')" :class="[textSize === 'xlarge' ? 'bg-brand-vinto text-white border-brand-vinto' : 'bg-zinc-900 text-zinc-400 border-zinc-800']" class="text-[10px] py-2 rounded-xl font-bold uppercase border cursor-pointer">
              Sangat Besar
            </button>
          </div>
        </div>

        <!-- High Contrast -->
        <div class="flex items-center justify-between bg-zinc-900/40 border border-zinc-800/60 rounded-2xl p-3">
          <span class="text-xs font-bold uppercase tracking-wider text-zinc-300">Kontras Tinggi</span>
          <button @click="toggleContrast" 
            :class="[isHighContrast ? 'bg-green-500' : 'bg-zinc-800']"
            class="w-10 h-6 rounded-full p-1 transition-colors duration-200 focus:outline-none cursor-pointer">
            <div :class="[isHighContrast ? 'translate-x-4' : 'translate-x-0']" class="w-4 h-4 bg-white rounded-full transition-transform duration-200"></div>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const isOpen = ref(false)
const isListening = ref(false)
const voiceStatus = ref('Klik mikrofon untuk bicara')
const textSize = ref('normal')
const isHighContrast = ref(false)

let recognition = null

onMounted(() => {
  // Initialize speech recognition if available
  const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition
  if (SpeechRecognition) {
    recognition = new SpeechRecognition()
    recognition.lang = 'id-ID' // Indonesian language support
    recognition.continuous = false
    recognition.interimResults = false

    recognition.onstart = () => {
      isListening.value = true
      voiceStatus.value = 'Mendengarkan suara Anda...'
    }

    recognition.onresult = (event) => {
      const command = event.results[0][0].transcript.toLowerCase()
      processVoiceCommand(command)
    }

    recognition.onerror = (event) => {
      console.error('Speech error:', event.error)
      isListening.value = false
      voiceStatus.value = 'Gagal mendengar, coba lagi'
    }

    recognition.onend = () => {
      isListening.value = false
    }
  } else {
    voiceStatus.value = 'Perintah suara tidak didukung browser'
  }
})

function togglePanel() {
  isOpen.value = !isOpen.value
}

function toggleVoiceListening() {
  if (!recognition) return
  if (isListening.value) {
    recognition.stop()
  } else {
    recognition.start()
  }
}

function speakText(text) {
  if ('speechSynthesis' in window) {
    const utterance = new SpeechSynthesisUtterance(text)
    utterance.lang = 'id-ID'
    window.speechSynthesis.speak(utterance)
  }
}

function processVoiceCommand(command) {
  voiceStatus.value = `Perintah: "${command}"`
  
  if (command.includes('booking') || command.includes('pesan') || command.includes('potong')) {
    speakText('Membuka halaman pemesanan sesi rambut.')
    router.push('/booking')
    isOpen.value = false
  } else if (command.includes('lokasi') || command.includes('outlet') || command.includes('cabang')) {
    speakText('Membuka daftar outlet MORE.')
    router.push('/outlets')
    isOpen.value = false
  } else if (command.includes('layanan') || command.includes('harga') || command.includes('services')) {
    speakText('Menampilkan katalog layanan barbershop.')
    router.push('/services')
    isOpen.value = false
  } else if (command.includes('home') || command.includes('beranda') || command.includes('depan')) {
    speakText('Kembali ke beranda utama.')
    router.push('/')
    isOpen.value = false
  } else if (command.includes('kontak') || command.includes('hubungi')) {
    speakText('Membuka informasi kontak MORE.')
    router.push('/contact')
    isOpen.value = false
  } else if (command.includes('bantuan') || command.includes('tujuan') || command.includes('help')) {
    speakText('Katakan booking untuk memesan, lokasi untuk mencari cabang, layanan untuk daftar harga, atau beranda untuk ke halaman depan.')
    voiceStatus.value = 'Petunjuk bantuan disuarakan.'
  } else {
    speakText('Perintah tidak dikenali, katakan bantuan untuk panduan.')
    voiceStatus.value = 'Kurang paham. Katakan "bantuan".'
  }
}

function setTextSize(size) {
  textSize.value = size
  const html = document.documentElement
  html.classList.remove('large-text', 'xlarge-text')
  
  if (size === 'large') {
    html.classList.add('large-text')
  } else if (size === 'xlarge') {
    html.classList.add('xlarge-text')
  }
}

function toggleContrast() {
  isHighContrast.value = !isHighContrast.value
  const html = document.documentElement
  if (isHighContrast.value) {
    html.classList.add('high-contrast')
  } else {
    html.classList.remove('high-contrast')
  }
}
</script>
