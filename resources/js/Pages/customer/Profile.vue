<template>
  <div class="max-w-md mx-auto space-y-6">
    <div class="space-y-1">
      <h1 class="text-2xl font-heading font-black text-brand-tinta">Edit Profil</h1>
      <p class="text-xs text-gray-500 font-body">Perbarui info profil pribadi Anda di bawah.</p>
    </div>

    <BaseCard class="space-y-4">
      <div v-if="successMsg" class="p-4 bg-green-50 text-green-700 border border-green-100 rounded-xl text-sm font-body text-center">
        {{ successMsg }}
      </div>

      <form @submit.prevent="saveProfile" class="space-y-4">
        <BaseInput v-model="form.full_name" label="Nama Lengkap" required />
        <BaseInput v-model="form.email" label="Email" type="email" />
        <PhoneInput v-model="form.phone" label="Nomor Telepon / WhatsApp" required />
        <BaseInput v-model="form.date_of_birth" label="Tanggal Lahir" type="date" />
        <BaseSelect v-model="form.gender" label="Jenis Kelamin">
          <option value="male">Laki-laki</option>
          <option value="female">Perempuan</option>
        </BaseSelect>

        <BaseButton type="submit" variant="primary" block :loading="loading">
          Simpan Perubahan
        </BaseButton>
      </form>
    </BaseCard>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useCustomerStore } from '../../stores/customer'
import BaseInput from '../../Components/ui/BaseInput.vue'
import PhoneInput from '../../Components/ui/PhoneInput.vue'
import BaseSelect from '../../Components/ui/BaseSelect.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseCard from '../../Components/ui/BaseCard.vue'

const customerStore = useCustomerStore()
const loading = ref(false)
const successMsg = ref('')

const form = reactive({
  full_name: '',
  email: '',
  phone: '',
  date_of_birth: '',
  gender: 'male'
})

onMounted(async () => {
  await customerStore.fetchProfile()
  if (customerStore.profile) {
    form.full_name = customerStore.profile.full_name
    form.email = customerStore.profile.email || ''
    form.phone = customerStore.profile.phone
    form.date_of_birth = customerStore.profile.date_of_birth || ''
    form.gender = customerStore.profile.gender || 'male'
  }
})

async function saveProfile() {
  loading.value = true
  successMsg.value = ''
  
  const res = await customerStore.updateProfile(form)
  loading.value = false
  if (res.success) {
    successMsg.value = 'Profil Anda berhasil diperbarui!'
  } else {
    alert(res.error)
  }
}
</script>
