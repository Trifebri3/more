<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Layanan</h1>
        <p class="text-xs text-gray-500 font-body">Konfigurasi harga, durasi, dan katalog perawatan salon.</p>
      </div>
      <BaseButton @click="openCreateModal" variant="primary" size="sm">
        + Tambah Layanan
      </BaseButton>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Service Catalog grid -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Layanan</th>
              <th class="px-6 py-4">Kategori</th>
              <th class="px-6 py-4">Durasi</th>
              <th class="px-6 py-4">Harga</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="service in services" :key="service.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4 flex items-center gap-3">
                <img :src="service.image_url || 'https://placehold.co/100x100/FAF8F5/1C1B1A.png?text=MORE'" class="w-10 h-10 rounded-xl object-cover border border-gray-100" />
                <span class="font-heading font-bold">{{ service.name }}</span>
              </td>
              <td class="px-6 py-4 text-xs text-gray-500 font-body capitalize">
                {{ service.category?.name || 'Haircut' }}
              </td>
              <td class="px-6 py-4 font-body text-xs">{{ service.duration }} menit</td>
              <td class="px-6 py-4 font-heading font-black text-brand-vinto">{{ formatPrice(service.price) }}</td>
              <td class="px-6 py-4 text-right space-x-2">
                <button @click="openEditModal(service)" class="px-3 py-1.5 bg-brand-vinto/10 text-brand-vinto rounded-lg hover:bg-brand-vinto/20 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Edit
                </button>
                <button @click="deleteService(service.id)" class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="services.length === 0">
              <td colspan="5" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada layanan terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Create/Edit Modal -->
    <BaseModal :show="modalOpen" :title="isEdit ? 'Edit Layanan' : 'Tambah Layanan Baru'" @close="closeModal">
      <form @submit.prevent="saveService" class="space-y-4 font-body text-sm">
        <BaseInput v-model="form.name" label="Nama Layanan" required />
        <BaseInput v-model="form.description" label="Deskripsi Singkat" required />
        
        <BaseSelect v-model="form.category_id" label="Kategori Layanan" required>
          <option value="" disabled>Pilih Kategori</option>
          <option v-for="cat in categories" :key="cat.id" :value="cat.id">
            {{ cat.name }}
          </option>
        </BaseSelect>

        <div class="grid grid-cols-2 gap-4">
          <BaseInput v-model="form.duration" label="Durasi (Menit)" type="number" required />
          <BaseInput v-model="form.price" label="Harga (Rupiah)" type="number" required />
        </div>

        <BaseInput v-model="form.image_url" label="Link Gambar Layanan URL" />

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
import BaseSelect from '../../Components/ui/BaseSelect.vue'
import BaseModal from '../../Components/ui/BaseModal.vue'
import { formatPrice } from '../../utils/formatter'

const services = ref([])
const categories = ref([])
const loading = ref(true)
const submitLoading = ref(false)
const modalOpen = ref(false)
const isEdit = ref(false)
const editingId = ref(null)

const form = reactive({
  name: '',
  description: '',
  category_id: '',
  duration: '45',
  price: '150000',
  image_url: ''
})

onMounted(async () => {
  await Promise.all([fetchServices(), fetchCategories()])
})

async function fetchServices() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/services')
    services.value = res.data.services || []
  } catch (err) {
    console.error('Error fetching services:', err)
  } finally {
    loading.value = false
  }
}

async function fetchCategories() {
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/services')
    categories.value = res.data.categories || []
  } catch (err) {
    console.error('Error fetching categories:', err)
  }
}

function openCreateModal() {
  isEdit.value = false
  editingId.value = null
  form.name = ''
  form.description = ''
  form.category_id = categories.value[0]?.id || ''
  form.duration = '45'
  form.price = '150000'
  form.image_url = ''
  modalOpen.value = true
}

function openEditModal(service) {
  isEdit.value = true
  editingId.value = service.id
  form.name = service.name
  form.description = service.description
  form.category_id = service.service_category_id || service.category_id || ''
  form.duration = service.duration
  form.price = service.price
  form.image_url = service.image_url || ''
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
}

async function saveService() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    if (isEdit.value) {
      await api.put(`/v1/services/${editingId.value}`, form)
      alert('Layanan berhasil diperbarui!')
    } else {
      await api.post('/v1/services', form)
      alert('Layanan baru berhasil ditambahkan!')
    }
    modalOpen.value = false
    await fetchServices()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan layanan.')
  } finally {
    submitLoading.value = false
  }
}

async function deleteService(id) {
  if (confirm('Apakah Anda yakin ingin menghapus layanan ini?')) {
    try {
      const api = (await import('../../utils/api')).default
      await api.delete(`/v1/services/${id}`)
      alert('Layanan berhasil dihapus!')
      await fetchServices()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus layanan.')
    }
  }
}
</script>
