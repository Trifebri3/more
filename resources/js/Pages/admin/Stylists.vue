<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Stylist</h1>
        <p class="text-xs text-gray-500 font-body">Kelola profil hair stylist, penugasan outlet, dan keahlian mereka.</p>
      </div>
      <BaseButton @click="openCreateModal" variant="primary" size="sm">
        + Tambah Stylist
      </BaseButton>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Stylist list table -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Nama</th>
              <th class="px-6 py-4">Outlet</th>
              <th class="px-6 py-4">Spesialisasi</th>
              <th class="px-6 py-4">Pengalaman</th>
              <th class="px-6 py-4">Rating</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="stylist in stylists" :key="stylist.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4 flex items-center gap-3">
                <img :src="stylist.avatar_url || 'https://placehold.co/100x100/FAF8F5/1C1B1A.png?text=MORE'" class="w-9 h-9 rounded-full object-cover border border-gray-100" />
                <span class="font-heading font-bold">{{ stylist.name }}</span>
              </td>
              <td class="px-6 py-4 text-xs font-body text-gray-500">
                {{ getOutletName(stylist.outlet_id) }}
              </td>
              <td class="px-6 py-4 text-xs text-gray-500 font-body">{{ stylist.specialty }}</td>
              <td class="px-6 py-4 font-body text-xs">{{ stylist.experience_years }} tahun</td>
              <td class="px-6 py-4 text-brand-vinto">★ {{ stylist.rating }}</td>
              <td class="px-6 py-4 text-right space-x-2">
                <button @click="openEditModal(stylist)" class="px-3 py-1.5 bg-brand-vinto/10 text-brand-vinto rounded-lg hover:bg-brand-vinto/20 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Edit
                </button>
                <button @click="deleteStylist(stylist.id)" class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="stylists.length === 0">
              <td colspan="6" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada stylist terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Create/Edit Modal -->
    <BaseModal :show="modalOpen" :title="isEdit ? 'Edit Stylist' : 'Tambah Stylist Baru'" @close="closeModal">
      <form @submit.prevent="saveStylist" class="space-y-4 font-body text-sm">
        <BaseInput v-model="form.name" label="Nama Lengkap" required />
        <BaseInput v-model="form.specialty" label="Spesialisasi (misal: Classic Cuts, Fade)" required />
        
        <BaseSelect v-model="form.outlet_id" label="Tugaskan ke Outlet" required>
          <option value="" disabled>Pilih Outlet</option>
          <option v-for="outlet in outlets" :key="outlet.id" :value="outlet.id">
            {{ outlet.name }}
          </option>
        </BaseSelect>

        <div class="grid grid-cols-2 gap-4">
          <BaseInput v-model="form.experience_years" label="Pengalaman (Tahun)" type="number" required />
          <BaseInput v-model="form.rating" label="Rating awal" type="number" step="0.1" max="5" required />
        </div>

        <BaseInput v-model="form.avatar_url" label="Link Foto Avatar URL" />

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

const stylists = ref([])
const outlets = ref([])
const loading = ref(true)
const submitLoading = ref(false)
const modalOpen = ref(false)
const isEdit = ref(false)
const editingId = ref(null)

const form = reactive({
  name: '',
  specialty: '',
  outlet_id: '',
  experience_years: '3',
  rating: '4.8',
  avatar_url: ''
})

onMounted(async () => {
  await Promise.all([fetchStylists(), fetchOutlets()])
})

async function fetchStylists() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/stylists')
    stylists.value = res.data
  } catch (err) {
    console.error('Error fetching stylists:', err)
  } finally {
    loading.value = false
  }
}

async function fetchOutlets() {
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/outlets')
    outlets.value = res.data
  } catch (err) {
    console.error('Error fetching outlets:', err)
  }
}

function getOutletName(outletId) {
  const o = outlets.value.find(item => item.id === outletId)
  return o ? o.name : 'MORE GI'
}

function openCreateModal() {
  isEdit.value = false
  editingId.value = null
  form.name = ''
  form.specialty = ''
  form.outlet_id = outlets.value[0]?.id || ''
  form.experience_years = '3'
  form.rating = '4.8'
  form.avatar_url = ''
  modalOpen.value = true
}

function openEditModal(stylist) {
  isEdit.value = true
  editingId.value = stylist.id
  form.name = stylist.name
  form.specialty = stylist.specialty
  form.outlet_id = stylist.outlet_id
  form.experience_years = stylist.experience_years
  form.rating = stylist.rating
  form.avatar_url = stylist.avatar_url || ''
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
}

async function saveStylist() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    if (isEdit.value) {
      await api.put(`/v1/stylists/${editingId.value}`, form)
      alert('Stylist berhasil diperbarui!')
    } else {
      await api.post('/v1/stylists', form)
      alert('Stylist baru berhasil ditambahkan!')
    }
    modalOpen.value = false
    await fetchStylists()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan stylist.')
  } finally {
    submitLoading.value = false
  }
}

async function deleteStylist(id) {
  if (confirm('Apakah Anda yakin ingin menghapus stylist ini?')) {
    try {
      const api = (await import('../../utils/api')).default
      await api.delete(`/v1/stylists/${id}`)
      alert('Stylist berhasil dihapus!')
      await fetchStylists()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus stylist.')
    }
  }
}
</script>
