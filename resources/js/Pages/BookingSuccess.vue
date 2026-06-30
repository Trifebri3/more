<template>
  <div class="max-w-md mx-auto text-center py-12 space-y-6">
    <div class="w-16 h-16 bg-green-500/10 text-green-500 rounded-full flex items-center justify-center mx-auto">
      <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
    </div>
    <h1 class="text-2xl font-heading font-black">Pemesanan Selesai!</h1>
    <p class="text-sm text-gray-500 font-body">
      Terima kasih telah mempercayakan grooming Anda kepada MORE. Silakan cek detail booking Anda di dashboard pelanggan.
    </p>
    <BaseButton @click="$router.push('/customer/dashboard')" variant="primary" block>
      Ke Dashboard Saya
    </BaseButton>
  </div>
</template>

<script setup>
import BaseButton from '../Components/ui/BaseButton.vue'
</script>
