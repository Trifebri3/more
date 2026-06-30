<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Promosi</h1>
        <p class="text-xs text-gray-500 font-body">Buat kode diskon, voucher event, dan kampanye loyalitas khusus.</p>
      </div>
      <BaseButton @click="openCreateModal" variant="primary" size="sm">
        + Tambah Promo
      </BaseButton>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Promo list grid -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Kode Promo</th>
              <th class="px-6 py-4">Nama Promo</th>
              <th class="px-6 py-4">Diskon</th>
              <th class="px-6 py-4">Periode</th>
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="promo in promotions" :key="promo.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4 text-xs font-heading font-black text-brand-vinto tracking-wider uppercase">
                {{ promo.code }}
              </td>
              <td class="px-6 py-4">
                <p class="text-sm font-heading font-bold">{{ promo.name }}</p>
                <p class="text-[10px] text-gray-400 font-body max-w-xs truncate">{{ promo.description }}</p>
              </td>
              <td class="px-6 py-4 font-body text-xs">
                {{ promo.discount_type === 'percentage' ? promo.discount_value + '%' : formatPrice(promo.discount_value) }}
              </td>
              <td class="px-6 py-4 text-xs text-gray-400 font-body">
                {{ formatShortDate(promo.start_date) }} - {{ formatShortDate(promo.end_date) }}
              </td>
              <td class="px-6 py-4">
                <span class="text-[9px] font-heading font-bold px-2.5 py-0.5 rounded-full"
                  :class="promo.is_active ? 'bg-green-50 text-green-600' : 'bg-gray-100 text-gray-500'"
                >
                  {{ promo.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right space-x-2">
                <button @click="openEditModal(promo)" class="px-3 py-1.5 bg-brand-vinto/10 text-brand-vinto rounded-lg hover:bg-brand-vinto/20 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Edit
                </button>
                <button @click="deletePromo(promo.id)" class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="promotions.length === 0">
              <td colspan="6" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada promo terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Create/Edit Modal -->
    <BaseModal :show="modalOpen" :title="isEdit ? 'Edit Promo' : 'Tambah Promo Baru'" @close="closeModal">
      <form @submit.prevent="savePromo" class="space-y-4 font-body text-sm">
        <BaseInput v-model="form.code" label="Kode Kupon (misal: LUXURY20)" required />
        <BaseInput v-model="form.name" label="Nama Promosi" required />
        <BaseInput v-model="form.description" label="Deskripsi" required />
        
        <BaseSelect v-model="form.discount_type" label="Tipe Diskon" required>
          <option value="percentage">Persentase (%)</option>
          <option value="fixed">Nominal Flat (Rp)</option>
        </BaseSelect>

        <BaseInput v-model="form.discount_value" label="Nilai Diskon" type="number" required />

        <div class="grid grid-cols-2 gap-4">
          <BaseInput v-model="form.start_date" label="Tanggal Mulai" type="date" required />
          <BaseInput v-model="form.end_date" label="Tanggal Selesai" type="date" required />
        </div>

        <div class="flex items-center gap-2 py-2">
          <input type="checkbox" id="is_active" v-model="form.is_active" class="w-4 h-4 text-brand-vinto border-gray-300 rounded focus:ring-brand-vinto" />
          <label for="is_active" class="text-xs text-gray-600 font-heading font-semibold uppercase">Aktifkan Kode Promo</label>
        </div>

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
import { formatShortDate } from '../../utils/dateHelper'

const promotions = ref([])
const loading = ref(true)
const submitLoading = ref(false)
const modalOpen = ref(false)
const isEdit = ref(false)
const editingId = ref(null)

const form = reactive({
  code: '',
  name: '',
  description: '',
  discount_type: 'percentage',
  discount_value: '20',
  start_date: '',
  end_date: '',
  is_active: true
})

onMounted(async () => {
  await fetchPromotions()
})

async function fetchPromotions() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/promotions')
    promotions.value = res.data
  } catch (err) {
    console.error('Error fetching promotions:', err)
  } finally {
    loading.value = false
  }
}

function openCreateModal() {
  isEdit.value = false
  editingId.value = null
  form.code = ''
  form.name = ''
  form.description = ''
  form.discount_type = 'percentage'
  form.discount_value = '20'
  form.start_date = ''
  form.end_date = ''
  form.is_active = true
  modalOpen.value = true
}

function openEditModal(promo) {
  isEdit.value = true
  editingId.value = promo.id
  form.code = promo.code
  form.name = promo.name
  form.description = promo.description
  form.discount_type = promo.discount_type
  form.discount_value = promo.discount_value
  form.start_date = promo.start_date
  form.end_date = promo.end_date
  form.is_active = !!promo.is_active
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
}

async function savePromo() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    if (isEdit.value) {
      await api.put(`/v1/promotions/${editingId.value}`, form)
      alert('Kupon promo berhasil diperbarui!')
    } else {
      await api.post('/v1/promotions', form)
      alert('Promo baru berhasil ditambahkan!')
    }
    modalOpen.value = false
    await fetchPromotions()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan promo.')
  } finally {
    submitLoading.value = false
  }
}

async function deletePromo(id) {
  if (confirm('Apakah Anda yakin ingin menghapus promo ini?')) {
    try {
      const api = (await import('../../utils/api')).default
      await api.delete(`/v1/promotions/${id}`)
      alert('Promo berhasil dihapus!')
      await fetchPromotions()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus promo.')
    }
  }
}
</script>
