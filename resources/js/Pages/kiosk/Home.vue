<template>
  <div class="min-h-screen bg-brand-noir text-white flex flex-col justify-between p-8 font-body select-none">
    <!-- Header info -->
    <div class="flex justify-between items-center border-b border-gray-900 pb-6">
      <div class="flex items-center gap-3">
        <img src="/images/logo.png" class="h-8 w-auto object-contain" alt="MORE Logo" />
        <span class="text-xs font-heading font-bold uppercase tracking-wider px-3 py-1 bg-brand-vinto/25 text-brand-vinto rounded-full">
          Self-Service Kiosk
        </span>
      </div>
      <div class="flex items-center gap-4">
        <span class="text-sm font-heading font-bold text-gray-400">{{ timeString }}</span>
        <!-- Fullscreen Button -->
        <button @click="toggleFullscreen" class="p-2 border border-gray-800 rounded-xl hover:bg-gray-900 active:scale-95 text-gray-400 cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
          </svg>
        </button>
      </div>
    </div>

    <!-- Center greeting CTA -->
    <div class="max-w-xl mx-auto text-center space-y-10 my-auto py-12">
      <div class="space-y-4">
        <h1 class="text-4xl sm:text-5xl font-heading font-black tracking-wide leading-tight uppercase">
          Selamat Datang di <span class="text-brand-vinto">MORE Barbershop</span>
        </h1>
        <p class="text-sm text-gray-400 font-body max-w-sm mx-auto">
          Silakan pilih Walk-In untuk mendaftar langsung di tempat, atau Check-In jika Anda sudah memesan sesi secara online.
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-lg mx-auto">
        <!-- Walk In CTA -->
        <button
          @click="$router.push('/kiosk/walk-in')"
          class="flex flex-col items-center justify-center p-8 bg-brand-vinto hover:bg-primary-dark rounded-3xl active:scale-[0.98] transition-all duration-200 border-2 border-brand-vinto shadow-lg group cursor-pointer"
        >
          <div class="w-16 h-16 rounded-full bg-white/10 text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <span class="text-xl font-heading font-black text-white uppercase tracking-wider">Walk-In</span>
          <span class="text-[10px] text-brand-natural/80 mt-1 font-body">Daftar antrean langsung</span>
        </button>

        <!-- Check In CTA -->
        <button
          @click="$router.push('/kiosk/check-in')"
          class="flex flex-col items-center justify-center p-8 bg-brand-pudar hover:bg-gray-800 rounded-3xl active:scale-[0.98] transition-all duration-200 border-2 border-brand-pudar shadow-md group cursor-pointer"
        >
          <div class="w-16 h-16 rounded-full bg-white/10 text-white flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <span class="text-xl font-heading font-black text-white uppercase tracking-wider">Check-In QR</span>
          <span class="text-[10px] text-gray-400 mt-1 font-body">Sudah booking online</span>
        </button>
      </div>
    </div>

    <!-- Footer info -->
    <div class="flex flex-col sm:flex-row justify-between items-center text-xs text-gray-500 border-t border-gray-900 pt-6 gap-2">
      <p>Silakan sentuh layar untuk mendaftar. Butuh bantuan? Silakan panggil staf kami.</p>
      <button @click="$router.push('/kiosk/queue')" class="px-4 py-2 border border-gray-800 rounded-xl hover:bg-gray-900 active:scale-95 text-gray-400 font-heading font-semibold cursor-pointer">
        Lihat Antrean Lounge
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'

const timeString = ref('')
let timer = null

onMounted(() => {
  updateTime()
  timer = setInterval(updateTime, 1000)

  // Prevent sleep simulation
  if ('keepAwake' in navigator) {
    navigator.keepAwake = true
  }
})

onUnmounted(() => {
  clearInterval(timer)
})

function updateTime() {
  const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }
  timeString.value = new Date().toLocaleTimeString('id-ID', options)
}

function toggleFullscreen() {
  if (!document.fullscreenElement) {
    document.documentElement.requestFullscreen().catch((err) => {
      console.log('Error enabling fullscreen: ', err)
    })
  } else {
    document.exitFullscreen()
  }
}
</script>
