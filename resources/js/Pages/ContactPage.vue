<template>
  <div class="space-y-8 max-w-2xl mx-auto">
    <div class="space-y-2">
      <h1 class="text-3xl font-heading font-black">Hubungi Kami</h1>
      <p class="text-sm text-gray-500 font-body">Punya pertanyaan atau masukan tentang sesi grooming Anda? Tim kami siap melayani Anda.</p>
    </div>

    <BaseCard class="space-y-6">
      <h3 class="text-lg font-heading font-semibold text-brand-tinta">Form Dukungan</h3>
      
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body">
        {{ successMsg }}
      </div>

      <form @submit.prevent="submitForm" class="space-y-4">
        <BaseInput v-model="form.name" label="Nama Anda" required />
        <BaseInput v-model="form.email" label="Email" type="email" required />
        <BaseInput v-model="form.subject" label="Subjek Pertanyaan" required />
        
        <div class="relative w-full">
          <textarea
            v-model="form.message"
            required
            rows="4"
            placeholder="Tulis pesan Anda di sini..."
            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base text-brand-tinta font-body focus:outline-none focus:ring-2 focus:ring-brand-vinto"
          ></textarea>
        </div>

        <BaseButton type="submit" variant="primary" block :loading="loading">
          Kirim Pesan
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import BaseInput from '../Components/ui/BaseInput.vue'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseCard from '../Components/ui/BaseCard.vue'

const form = reactive({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const loading = ref(false)
const successMsg = ref('')

function submitForm() {
  loading.value = true
  setTimeout(() => {
    loading.value = false
    successMsg.value = 'Terima kasih! Pesan Anda telah terkirim. Tim support kami akan menghubungi Anda segera.'
    form.name = ''
    form.email = ''
    form.subject = ''
    form.message = ''
  }, 1000)
}
</script>
