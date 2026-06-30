<template>
  <div class="max-w-md mx-auto space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Pengaturan Sistem</h1>
      <p class="text-xs text-gray-500">Konfigurasi operasional global barbershop platform MORE.</p>
    </div>

    <BaseCard class="space-y-4">
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body text-center">
        {{ successMsg }}
      </div>

      <form @submit.prevent="saveSettings" class="space-y-4 font-body text-sm">
        <BaseInput v-model="settings.app_name" label="Nama Aplikasi / Platform" />
        <BaseSelect v-model="settings.slot_interval" label="Interval Waktu Slot (Menit)">
          <option value="15">15 Menit</option>
          <option value="30">30 Menit</option>
          <option value="45">45 Menit</option>
          <option value="60">60 Menit</option>
        </BaseSelect>

        <div>
          <label class="block text-xs text-gray-400 font-heading font-semibold mb-2">Template Notifikasi WhatsApp</label>
          <textarea
            v-model="settings.wa_template"
            rows="4"
            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm text-brand-tinta font-body focus:outline-none focus:ring-2 focus:ring-brand-vinto"
          ></textarea>
        </div>

        <BaseButton type="submit" variant="primary" block :loading="loading">
          Simpan Pengaturan
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseSelect from '../../Components/ui/BaseSelect.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseCard from '../../Components/ui/BaseCard.vue'

const loading = ref(false)
const successMsg = ref('')

const settings = reactive({
  app_name: 'MORE Grooming Experience',
  slot_interval: '30',
  wa_template: 'Halo {customer_name}, booking Anda untuk sesi {service_name} dengan stylist {stylist_name} di outlet {outlet_name} pada tanggal {booking_date} jam {booking_time} telah BERHASIL dikonfirmasi. Kode check-in Anda: {booking_code}. Terima kasih.'
})

function saveSettings() {
  loading.value = true
  setTimeout(() => {
    loading.value = false
    successMsg.value = 'Pengaturan berhasil disimpan!'
  }, 800)
}
</script>
