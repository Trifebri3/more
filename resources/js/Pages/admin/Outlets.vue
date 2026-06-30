<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Outlet</h1>
        <p class="text-xs text-gray-500 font-body">Tambah, edit, atau hapus lokasi outlet barbershop.</p>
      </div>
      <BaseButton @click="openCreateModal" variant="primary" size="sm">
        + Tambah Outlet
      </BaseButton>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Outlet Table list -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Outlet</th>
              <th class="px-6 py-4">Alamat</th>
              <th class="px-6 py-4">Telepon</th>
              <th class="px-6 py-4">Rating</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="outlet in outlets" :key="outlet.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4 flex items-center gap-3">
                <img :src="outlet.image_url || 'https://placehold.co/100x100/FAF8F5/1C1B1A.png?text=MORE'" class="w-10 h-10 rounded-xl object-cover border border-gray-100" />
                <span class="font-heading font-bold">{{ outlet.name }}</span>
              </td>
              <td class="px-6 py-4 max-w-xs truncate text-xs text-gray-500 font-body">{{ outlet.address }}</td>
              <td class="px-6 py-4 font-body text-xs">{{ outlet.phone }}</td>
              <td class="px-6 py-4 text-brand-vinto">★ {{ outlet.rating }}</td>
              <td class="px-6 py-4 text-right space-x-2">
                <button @click="openEditModal(outlet)" class="px-3 py-1.5 bg-brand-vinto/10 text-brand-vinto rounded-lg hover:bg-brand-vinto/20 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Edit
                </button>
                <button @click="deleteOutlet(outlet.id)" class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="outlets.length === 0">
              <td colspan="5" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada outlet terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Create/Edit Modal -->
    <BaseModal :show="modalOpen" :title="isEdit ? 'Edit Outlet' : 'Tambah Outlet Baru'" @close="closeModal">
      <form @submit.prevent="saveOutlet" class="space-y-4 font-body text-sm">
        <BaseInput v-model="form.name" label="Nama Outlet" required />
        <BaseInput v-model="form.address" label="Alamat Lengkap" required />
        
        <div class="grid grid-cols-2 gap-4">
          <BaseInput v-model="form.latitude" label="Latitude" />
          <BaseInput v-model="form.longitude" label="Longitude" />
        </div>

        <BaseInput v-model="form.phone" label="Nomor Telepon" required />
        <BaseInput v-model="form.rating" label="Rating (misal: 4.9)" type="number" step="0.1" max="5" required />
        <BaseInput v-model="form.image_url" label="Link Gambar / Thumbnail URL" />

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <BaseButton @click="closeModal" variant="ghost" size="sm">Batal</BaseButton>
          <BaseButton type="submit" variant="primary" size="sm" :loading="submitLoading">
            Simpan
          </BaseButton>
        </div>
      </form>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseModal from '../../Components/ui/BaseModal.vue'

const outlets = ref([])
const loading = ref(true)
const submitLoading = ref(false)
const modalOpen = ref(false)
const isEdit = ref(false)
const editingId = ref(null)

const form = reactive({
  name: '',
  address: '',
  latitude: '',
  longitude: '',
  phone: '',
  rating: '4.8',
  image_url: ''
})

onMounted(async () => {
  await fetchOutlets()
})

async function fetchOutlets() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/outlets')
    outlets.value = res.data
  } catch (err) {
    console.error('Error fetching outlets:', err)
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  isEdit.value = false
  editingId.value = null
  form.name = ''
  form.address = ''
  form.latitude = ''
  form.longitude = ''
  form.phone = ''
  form.rating = '4.8'
  form.image_url = ''
  modalOpen.value = true
}

function openEditModal(outlet) {
  isEdit.value = true
  editingId.value = outlet.id
  form.name = outlet.name
  form.address = outlet.address
  form.latitude = outlet.latitude || ''
  form.longitude = outlet.longitude || ''
  form.phone = outlet.phone
  form.rating = outlet.rating
  form.image_url = outlet.image_url || ''
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
}

async function saveOutlet() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    if (isEdit.value) {
      await api.put(`/v1/outlets/${editingId.value}`, form)
      alert('Outlet berhasil diperbarui!')
    } else {
      await api.post('/v1/outlets', form)
      alert('Outlet baru berhasil ditambahkan!')
    }
    modalOpen.value = false
    await fetchOutlets()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan outlet.')
  } finally {
    submitLoading.value = false
  }
}

async function deleteOutlet(id) {
  if (confirm('Apakah Anda yakin ingin menghapus outlet ini?')) {
    try {
      const api = (await import('../../utils/api')).default
      await api.delete(`/v1/outlets/${id}`)
      alert('Outlet berhasil dihapus!')
      await fetchOutlets()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus outlet.')
    }
  }
}
</script>
