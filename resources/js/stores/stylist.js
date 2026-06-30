import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref } from 'vue'

export const useStylistStore = defineStore('stylist', () => {
  const stylists = ref([])
  const slots = ref([])
  const loading = ref(false)
  const slotsLoading = ref(false)
  const error = ref(null)

  async function fetchStylists(outletId = null) {
    loading.value = true
    error.value = null
    try {
      const url = outletId ? `/v1/stylists?outlet_id=${outletId}` : '/v1/stylists'
      const response = await api.get(url)
      stylists.value = response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat stylist.'
    } finally {
      loading.value = false
    }
  }

  async function fetchAvailableSlots(stylistId, dateString) {
    slotsLoading.value = true
    slots.value = []
    try {
      const response = await api.get(`/v1/stylists/${stylistId}/availability`, {
        params: { date: dateString }
      })
      slots.value = response.data.all_slots || response.data.available_slots || []
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat slot waktu.'
    } finally {
      slotsLoading.value = false
    }
  }

  return {
    stylists,
    slots,
    loading,
    slotsLoading,
    error,
    fetchStylists,
    fetchAvailableSlots
  }
})
