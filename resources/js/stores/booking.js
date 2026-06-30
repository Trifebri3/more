import { defineStore } from 'pinia'
import api from '../utils/api'
import { ref, reactive, computed } from 'vue'

export const useBookingStore = defineStore('booking', () => {
  const currentStep = ref(1)
  const bookingData = reactive({
    outlet_id: null,
    service_ids: [],
    stylist_id: null,
    booking_date: null,
    booking_time: null,
    customer: {
      full_name: '',
      phone: '',
      whatsapp: '',
      email: '',
      date_of_birth: '',
      gender: 'male'
    },
    notes: ''
  })

  // Selected entities for display
  const selectedOutlet = ref(null)
  const selectedServices = ref([])
  const selectedStylist = ref(null)

  const steps = [
    { id: 1, name: 'Pilih Outlet' },
    { id: 2, name: 'Pilih Layanan' },
    { id: 3, name: 'Pilih Stylist' },
    { id: 4, name: 'Pilih Waktu' },
    { id: 5, name: 'Data Diri' },
    { id: 6, name: 'Konfirmasi' }
  ]

  // Calculated properties
  const totalPrice = computed(() => {
    return selectedServices.value.reduce((sum, item) => sum + parseFloat(item.price), 0)
  })

  const totalDuration = computed(() => {
    return selectedServices.value.reduce((sum, item) => sum + parseInt(item.duration), 0)
  })

  const birthdayDiscount = computed(() => {
    if (!bookingData.customer.date_of_birth || !bookingData.booking_date) return 0
    const dob = new Date(bookingData.customer.date_of_birth)
    const bookingDate = new Date(bookingData.booking_date)
    if (dob.getMonth() === bookingDate.getMonth() && dob.getDate() === bookingDate.getDate()) {
      return totalPrice.value * 0.20 // 20% birthday discount
    }
    return 0
  })

  const promoDiscount = ref(0)
  const promoCode = ref('')
  const activePromo = ref(null)

  const totalDiscount = computed(() => {
    return birthdayDiscount.value + promoDiscount.value
  })

  const finalPrice = computed(() => {
    const price = totalPrice.value - totalDiscount.value
    return price < 0 ? 0 : price
  })

  function nextStep() {
    if (currentStep.value < steps.length) {
      currentStep.value++
    }
  }

  function previousStep() {
    if (currentStep.value > 1) {
      currentStep.value--
    }
  }

  async function applyPromoCode(code) {
    try {
      const response = await api.get(`/v1/promotions`)
      const promo = response.data.find(p => p.code.toUpperCase() === code.toUpperCase() && p.is_active)
      if (promo) {
        activePromo.value = promo
        promoCode.value = promo.code
        if (promo.discount_type === 'percentage') {
          promoDiscount.value = totalPrice.value * (parseFloat(promo.discount_value) / 100)
        } else {
          promoDiscount.value = parseFloat(promo.discount_value)
        }
        return { success: true, message: `Promo ${promo.name} berhasil digunakan!` }
      }
      return { success: false, message: 'Kode promo tidak valid atau kedaluwarsa.' }
    } catch {
      return { success: false, message: 'Gagal memverifikasi kode promo.' }
    }
  }

  async function submitBooking() {
    try {
      const acqSource = localStorage.getItem('booking_acquisition_source') || 'online'
      const payload = {
        outlet_id: bookingData.outlet_id,
        service_ids: bookingData.service_ids,
        stylist_id: bookingData.stylist_id,
        booking_date: bookingData.booking_date,
        booking_time: bookingData.booking_time,
        customer: bookingData.customer,
        notes: bookingData.notes,
        source: acqSource
      }
      const response = await api.post('/v1/bookings', payload)
      return { success: true, data: response.data }
    } catch (error) {
      const errorMsg = error.response?.data?.errors 
        ? Object.values(error.response.data.errors).flat().join('\n') 
        : (error.response?.data?.message || 'Gagal mengirim booking.')
      return { success: false, error: errorMsg }
    }
  }

  function reset() {
    currentStep.value = 1
    selectedOutlet.value = null
    selectedServices.value = []
    selectedStylist.value = null
    activePromo.value = null
    promoCode.value = ''
    promoDiscount.value = 0

    Object.assign(bookingData, {
      outlet_id: null,
      service_ids: [],
      stylist_id: null,
      booking_date: null,
      booking_time: null,
      customer: {
        full_name: '',
        phone: '',
        whatsapp: '',
        email: '',
        date_of_birth: '',
        gender: 'male'
      },
      notes: ''
    })
  }

  return {
    currentStep,
    bookingData,
    steps,
    selectedOutlet,
    selectedServices,
    selectedStylist,
    totalPrice,
    totalDuration,
    birthdayDiscount,
    promoDiscount,
    promoCode,
    activePromo,
    totalDiscount,
    finalPrice,
    nextStep,
    previousStep,
    applyPromoCode,
    submitBooking,
    reset
  }
})
