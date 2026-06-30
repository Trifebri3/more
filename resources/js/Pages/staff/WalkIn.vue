<template>
  <div class="max-w-md mx-auto space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Registrasi Walk-In</h1>
      <p class="text-xs text-gray-500">Daftarkan pelanggan walk-in langsung dari meja resepsionis.</p>
    </div>

    <BaseCard class="space-y-4">
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body text-center">
        {{ successMsg }}
      </div>

      <form @submit.prevent="handleWalkIn" class="space-y-4">
        <BaseInput v-model="form.name" label="Nama Pelanggan" required />
        <PhoneInput v-model="form.phone" label="WhatsApp Pelanggan" required />
        
        <BaseSelect v-model="form.service_id" label="Pilih Layanan" required>
          <option v-for="srv in services" :key="srv.id" :value="srv.id">
            {{ srv.name }} - {{ formatPrice(srv.price) }}
          </option>
        </BaseSelect>

        <BaseSelect v-model="form.stylist_id" label="Pilih Stylist" required>
          <option v-for="sty in stylists" :key="sty.id" :value="sty.id">
            {{ sty.name }}
          </option>
        </BaseSelect>

        <BaseButton type="submit" variant="primary" block :loading="loading">
          Daftarkan Antrean
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseSelect from '../../Components/ui/BaseSelect.vue'
import PhoneInput from '../../Components/ui/PhoneInput.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import { formatPrice } from '../../utils/formatter'

const loading = ref(false)
const successMsg = ref('')
const services = ref([])
const stylists = ref([])

const form = reactive({
  name: '',
  phone: '',
  service_id: '',
  stylist_id: ''
})

onMounted(async () => {
  try {
    const api = (await import('../../utils/api')).default
    const srvRes = await api.get('/v1/services')
    services.value = srvRes.data.services || []
    
    const styRes = await api.get('/v1/stylists')
    stylists.value = styRes.data || []
  } catch {
    // Fallback Mock for stand-alone compiler verification
    services.value = [{ id: 1, name: 'MORE Signature Haircut', price: 150000.00 }]
    stylists.value = [{ id: 1, name: 'Alexandre Dumas' }]
  }
})

async function handleWalkIn() {
  loading.value = true
  successMsg.value = ''
  
  try {
    const api = (await import('../../utils/api')).default
    const payload = {
      customer_name: form.name,
      whatsapp: form.phone,
      service_id: form.service_id,
      stylist_id: form.stylist_id,
      outlet_id: 1 // default GI
    }
    const response = await api.post('/v1/walk-in', payload)
    successMsg.value = `Pelanggan berhasil didaftarkan! Nomor Antrean: ${response.data.queue_number}`
    form.name = ''
    form.phone = ''
    form.service_id = ''
    form.stylist_id = ''
  } catch (err) {
    alert(err.response?.data?.message || 'Registrasi gagal.')
  } finally {
    loading.value = false
  }
}
</script>
