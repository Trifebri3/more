<template>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Manajemen Pelanggan</h1>
        <p class="text-xs text-gray-500 font-body">Daftar pelanggan terdaftar, tier keanggotaan, dan poin loyalitas.</p>
      </div>
      <button 
        @click="exportToCSV"
        class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl font-heading text-xs font-bold uppercase tracking-wider flex items-center gap-1.5 cursor-pointer shadow-sm transition"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        Export Excel/CSV
      </button>
    </div>

    <!-- Loading Shimmer -->
    <div v-if="loading" class="space-y-3">
      <BaseLoader skeleton height="60px" v-for="i in 3" :key="i" />
    </div>

    <!-- Customer register table -->
    <BaseCard v-else padding="none" class="overflow-hidden bg-white border border-gray-150 shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm font-body border-collapse">
          <thead class="bg-gray-50 border-b border-gray-150 text-gray-400 font-heading font-bold text-xs uppercase tracking-wider">
            <tr>
              <th class="px-6 py-4">Nama Pelanggan</th>
              <th class="px-6 py-4">WhatsApp</th>
              <th class="px-6 py-4">Poin</th>
              <th class="px-6 py-4">Tier</th>
              <th class="px-6 py-4">Total Spending</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 text-brand-tinta font-semibold">
            <tr v-for="customer in customers" :key="customer.id" class="hover:bg-gray-50/50">
              <td class="px-6 py-4">
                <p class="font-heading font-bold text-brand-tinta">{{ customer.full_name }}</p>
                <p class="text-[10px] text-gray-400 font-body">{{ customer.email }}</p>
              </td>
              <td class="px-6 py-4 font-body text-xs">{{ customer.whatsapp }}</td>
              <td class="px-6 py-4 font-body text-xs text-brand-vinto">{{ customer.loyalty_points }} pts</td>
              <td class="px-6 py-4">
                <span class="text-[9px] font-heading font-bold px-2.5 py-0.5 rounded-full capitalize"
                  :class="[
                    customer.membership_status === 'gold' || customer.membership_status === 'vip' ? 'bg-amber-50 text-amber-600' :
                    customer.membership_status === 'silver' ? 'bg-slate-100 text-slate-600' :
                    'bg-gray-100 text-gray-500'
                  ]"
                >
                  {{ customer.membership_status }}
                </span>
              </td>
              <td class="px-6 py-4 font-heading font-black text-brand-vinto">{{ formatPrice(customer.total_spending) }}</td>
              <td class="px-6 py-4 text-right space-x-2">
                <button @click="openEditModal(customer)" class="px-3 py-1.5 bg-brand-vinto/10 text-brand-vinto rounded-lg hover:bg-brand-vinto/20 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Edit Tier
                </button>
                <button @click="deleteCustomer(customer.id)" class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 font-heading text-xs font-bold uppercase tracking-wider cursor-pointer">
                  Hapus
                </button>
              </td>
            </tr>
            <tr v-if="customers.length === 0">
              <td colspan="6" class="text-center py-12 text-xs text-gray-400 font-body">Belum ada pelanggan terdaftar.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </BaseCard>

    <!-- Edit Tier Modal -->
    <BaseModal :show="modalOpen" title="Edit Detail Keanggotaan" @close="closeModal">
      <form @submit.prevent="saveCustomer" class="space-y-4 font-body text-sm">
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-1">
          <p class="text-xs text-gray-400 font-heading uppercase font-bold tracking-wider">Pelanggan</p>
          <h3 class="text-base font-heading font-black text-brand-tinta">{{ selectedCustomer?.full_name }}</h3>
          <p class="text-xs text-gray-500">{{ selectedCustomer?.email }} • {{ selectedCustomer?.whatsapp }}</p>
        </div>

        <BaseSelect v-model="form.membership_status" label="Membership Tier" required>
          <option value="regular">Regular</option>
          <option value="silver">Silver</option>
          <option value="gold">Gold</option>
          <option value="vip">VIP</option>
        </BaseSelect>

        <BaseInput v-model="form.loyalty_points" label="Poin Loyalitas" type="number" required />

        <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
          <BaseButton @click="closeModal" variant="ghost" size="sm">Batal</BaseButton>
          <BaseButton type="submit" variant="primary" size="sm" :loading="submitLoading">
            Simpan Perubahan
          </BaseButton>
        </div>
      </form>
    </BaseModal>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import BaseCard from '../../Components/ui/BaseCard.vue'
import BaseLoader from '../../Components/ui/BaseLoader.vue'
import BaseSelect from '../../Components/ui/BaseSelect.vue'
import BaseInput from '../../Components/ui/BaseInput.vue'
import BaseModal from '../../Components/ui/BaseModal.vue'
import BaseButton from '../../Components/ui/BaseButton.vue'
import { formatPrice } from '../../utils/formatter'

const customers = ref([])
const loading = ref(true)
const submitLoading = ref(false)
const modalOpen = ref(false)
const selectedCustomer = ref(null)

const form = reactive({
  membership_status: 'regular',
  loyalty_points: 0
})

onMounted(async () => {
  await fetchCustomers()
})

async function fetchCustomers() {
  loading.value = true
  try {
    const api = (await import('../../utils/api')).default
    const res = await api.get('/v1/customers')
    customers.value = res.data
  } catch (err) {
    console.error('Error fetching customers:', err)
  } finally {
    loading.value = false
  }
}

function openEditModal(customer) {
  selectedCustomer.value = customer
  form.membership_status = customer.membership_status || 'regular'
  form.loyalty_points = customer.loyalty_points || 0
  modalOpen.value = true
}

function closeModal() {
  modalOpen.value = false
}

async function saveCustomer() {
  submitLoading.value = true
  try {
    const api = (await import('../../utils/api')).default
    await api.put(`/v1/customers/${selectedCustomer.value.id}`, {
      membership_status: form.membership_status,
      loyalty_points: form.loyalty_points
    })
    alert('Informasi keanggotaan berhasil diperbarui!')
    modalOpen.value = false
    await fetchCustomers()
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan perubahan.')
  } finally {
    submitLoading.value = false
  }
}

async function deleteCustomer(id) {
  if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')) {
    try {
      const api = (await import('../../utils/api')).default
      await api.delete(`/v1/customers/${id}`)
      alert('Pelanggan berhasil dihapus!')
      await fetchCustomers()
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus pelanggan.')
    }
  }
}

function exportToCSV() {
  if (customers.value.length === 0) return alert('Tidak ada data pelanggan untuk diexport.')
  
  // Define columns
  const headers = ['ID', 'Nama Lengkap', 'Email', 'No WhatsApp', 'Tanggal Lahir', 'Status Keanggotaan', 'Poin Loyalitas', 'Total Kunjungan', 'Total Belanja (IDR)', 'Terakhir Berkunjung']
  
  // Map rows
  const rows = customers.value.map(c => [
    c.id,
    c.full_name,
    c.email || '',
    c.whatsapp || '',
    c.date_of_birth || '',
    c.membership_status || 'regular',
    c.loyalty_points || 0,
    c.total_visits || 0,
    c.total_spending || 0,
    c.last_visit_at || ''
  ])
  
  // Construct CSV content (handling quotes and commas)
  const csvRows = [headers.join(',')]
  for (const row of rows) {
    const formatted = row.map(val => {
      const cleanVal = String(val).replace(/"/g, '""')
      return `"${cleanVal}"`
    })
    csvRows.push(formatted.join(','))
  }
  
  const csvContent = "data:text/csv;charset=utf-8," + csvRows.join('\n')
  
  // Trigger download
  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", `more_customers_${new Date().toISOString().slice(0, 10)}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}
</script>
