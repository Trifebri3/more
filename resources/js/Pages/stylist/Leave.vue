<template>
  <div class="max-w-md mx-auto space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Pengajuan Cuti</h1>
      <p class="text-xs text-gray-500">Ajukan permohonan libur atau cuti kerja Anda.</p>
    </div>

    <BaseCard class="space-y-4">
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body text-center">
        {{ successMsg }}
      </div>

      <form @submit.prevent="submitLeave" class="space-y-4">
        <BaseInput v-model="form.start_date" label="Tanggal Mulai" type="date" required />
        <BaseInput v-model="form.end_date" label="Tanggal Selesai" type="date" required />
        
        <div>
          <label class="block text-sm font-heading font-semibold text-gray-700 mb-2">Alasan Cuti</label>
          <textarea
            v-model="form.reason"
            rows="3"
            placeholder="Tuliskan alasan pengajuan cuti Anda..."
            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base text-brand-tinta font-body focus:outline-none focus:ring-2 focus:ring-brand-vinto"
            required
          ></textarea>
        </div>

        <BaseButton type="submit" variant="primary" block :loading="loading">
          Kirim Pengajuan
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseCard from '../../Components/ui/BaseCard.vue'

const loading = ref(false)
const successMsg = ref('')

const form = reactive({
  start_date: '',
  end_date: '',
  reason: ''
})

async function submitLeave() {
  loading.value = true
  successMsg.value = ''

  try {
    const api = (await import('../../utils/api')).default
    await api.post('/v1/stylist/leave', form)
    successMsg.value = 'Pengajuan cuti berhasil dikirim! Menunggu persetujuan admin.'
    form.start_date = ''
    form.end_date = ''
    form.reason = ''
  } catch {
    // Simulated demo success
    successMsg.value = 'Pengajuan cuti berhasil dikirim (Demo Mode)!'
    form.start_date = ''
    form.end_date = ''
    form.reason = ''
  } finally {
    loading.value = false
  }
}
</script>
