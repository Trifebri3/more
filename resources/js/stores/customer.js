import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref } from 'vue'

export const useCustomerStore = defineStore('customer', () => {
  const profile = ref(null)
  const bookings = ref([])
  const loading = ref(false)
  const error = ref(null)

  async function fetchProfile() {
    loading.value = true
    try {
      const response = await api.get('/v1/customers/profile')
      profile.value = response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat profil.'
    } finally {
      loading.value = false
    }
  }

  async function updateProfile(data) {
    loading.value = true
    try {
      const response = await api.put('/v1/customers/profile', data)
      profile.value = response.data.customer
      return { success: true }
    } catch (err) {
      return { success: false, error: err.response?.data?.message || 'Gagal memperbarui profil.' }
    } finally {
      loading.value = false
    }
  }

  async function fetchBookings() {
    loading.value = true
    try {
      const response = await api.get('/v1/customers/bookings')
      bookings.value = response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat riwayat booking.'
    } finally {
      loading.value = false
    }
  }

  async function checkCustomerByWhatsapp(whatsapp) {
    try {
      const response = await api.post('/v1/customer/check', { whatsapp })
      return response.data
    } catch (err) {
      // Fallback post endpoint in routes is actually `/bookings/check-availability` or custom check
      const altResponse = await api.post('/v1/bookings/check-availability', { whatsapp })
      return altResponse.data
    }
  }

  return {
    profile,
    bookings,
    loading,
    error,
    fetchProfile,
    updateProfile,
    fetchBookings,
    checkCustomerByWhatsapp
  }
})
