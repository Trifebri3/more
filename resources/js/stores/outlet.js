import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref } from 'vue'

export const useOutletStore = defineStore('outlet', () => {
  const outlets = ref([])
  const loading = ref(false)
  const error = ref(null)
  const currentOutlet = ref(null)

  async function fetchOutlets() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/v1/outlets')
      outlets.value = response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat outlet.'
    } finally {
      loading.value = false
    }
  }

  async function fetchOutletById(id) {
    loading.value = true
    try {
      const response = await api.get(`/v1/outlets/${id}`)
      currentOutlet.value = response.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat detail outlet.'
    } finally {
      loading.value = false
    }
  }

  // Calculate distance between two coordinates in km (Haversine formula)
  function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371 // Radius of earth in km
    const dLat = (lat2 - lat1) * Math.PI / 180
    const dLon = (lon2 - lon1) * Math.PI / 180
    const a = 
      Math.sin(dLat/2) * Math.sin(dLat/2) +
      Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * 
      Math.sin(dLon/2) * Math.sin(dLon/2)
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a))
    return R * c
  }

  function sortOutletsByDistance(userLat, userLon) {
    if (!outlets.value.length) return
    outlets.value = outlets.value.map(outlet => {
      if (outlet.latitude && outlet.longitude) {
        outlet.distance = getDistance(userLat, userLon, parseFloat(outlet.latitude), parseFloat(outlet.longitude))
      } else {
        outlet.distance = null
      }
      return outlet
    }).sort((a, b) => {
      if (a.distance === null) return 1
      if (b.distance === null) return -1
      return a.distance - b.distance
    })
  }

  return {
    outlets,
    loading,
    error,
    currentOutlet,
    fetchOutlets,
    fetchOutletById,
    sortOutletsByDistance
  }
})
