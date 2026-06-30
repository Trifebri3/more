import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref, computed } from 'vue'

export const useServiceStore = defineStore('service', () => {
  const services = ref([])
  const categories = ref([])
  const loading = ref(false)
  const error = ref(null)
  const searchQuery = ref('')
  const selectedCategorySlug = ref('all')

  async function fetchServices() {
    loading.value = true
    error.value = null
    try {
      const response = await api.get('/v1/services')
      services.value = response.data.services || []
      categories.value = response.data.categories || []
    } catch (err) {
      error.value = err.response?.data?.message || 'Gagal memuat layanan.'
    } finally {
      loading.value = false
    }
  }

  const filteredServices = computed(() => {
    return services.value.filter(service => {
      const matchesSearch = service.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
                            (service.description && service.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
      
      const matchesCategory = selectedCategorySlug.value === 'all' || 
                              (service.category && service.category.slug === selectedCategorySlug.value)

      return matchesSearch && matchesCategory
    })
  })

  return {
    services,
    categories,
    loading,
    error,
    searchQuery,
    selectedCategorySlug,
    fetchServices,
    filteredServices
  }
})
