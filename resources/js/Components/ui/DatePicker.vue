<template>
  <div class="bg-white border border-gray-100 rounded-2xl p-4 shadow-xs">
    <!-- Month and Year selectors -->
    <div class="flex items-center justify-between mb-4">
      <button @click="prevMonth" type="button" class="p-2 hover:bg-gray-50 rounded-lg text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>
      <span class="font-heading font-semibold text-brand-tinta text-base select-none">
        {{ currentMonthName }} {{ currentYear }}
      </span>
      <button @click="nextMonth" type="button" class="p-2 hover:bg-gray-50 rounded-lg text-gray-600">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </div>

    <!-- Weekday headers -->
    <div class="grid grid-cols-7 gap-1 text-center text-xs font-heading font-bold text-gray-400 mb-2">
      <span v-for="day in weekdays" :key="day">{{ day }}</span>
    </div>

    <!-- Days Grid -->
    <div class="grid grid-cols-7 gap-1">
      <button
        v-for="(day, index) in calendarDays"
        :key="index"
        type="button"
        @click="selectDay(day)"
        :disabled="day.disabled"
        class="h-10 w-full flex flex-col items-center justify-center text-sm font-body font-medium rounded-xl select-none cursor-pointer relative"
        :class="[
          day.isCurrentMonth ? '' : 'text-gray-300 pointer-events-none',
          day.disabled ? 'text-gray-200 pointer-events-none' : '',
          day.selected ? 'bg-brand-vinto text-white' : 'hover:bg-neutral-light text-brand-tinta',
          day.isToday && !day.selected ? 'border border-brand-pudar text-brand-pudar' : ''
        ]"
      >
        <span>{{ day.date.getDate() }}</span>
        <!-- Small dot indicator for existing bookings on this day -->
        <span 
          v-if="day.hasBookings" 
          class="w-1.5 h-1.5 rounded-full absolute bottom-1"
          :class="[day.selected ? 'bg-white' : 'bg-brand-vinto']"
        ></span>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  bookedDates: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['update:modelValue'])

const weekdays = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
const monthNames = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
]

const currentDate = ref(new Date())

// Extract current Month and Year
const currentMonth = ref(currentDate.value.getMonth())
const currentYear = ref(currentDate.value.getFullYear())

const currentMonthName = computed(() => monthNames[currentMonth.value])

// Selected Date matching modelValue
const selectedDate = computed(() => {
  if (!props.modelValue) return null
  return new Date(props.modelValue)
})

function prevMonth() {
  if (currentMonth.value === 0) {
    currentMonth.value = 11
    currentYear.value--
  } else {
    currentMonth.value--
  }
}

function nextMonth() {
  if (currentMonth.value === 11) {
    currentMonth.value = 0
    currentYear.value++
  } else {
    currentMonth.value++
  }
}

const calendarDays = computed(() => {
  const days = []
  
  // First day of month
  const firstDay = new Date(currentYear.value, currentMonth.value, 1)
  const startDayOfWeek = firstDay.getDay() // 0 to 6

  // Total days in month
  const totalDays = new Date(currentYear.value, currentMonth.value + 1, 0).getDate()

  // Previous month offset days
  const prevMonthTotalDays = new Date(currentYear.value, currentMonth.value, 0).getDate()
  for (let i = startDayOfWeek - 1; i >= 0; i--) {
    const d = new Date(currentYear.value, currentMonth.value - 1, prevMonthTotalDays - i)
    days.push({
      date: d,
      isCurrentMonth: false,
      disabled: true,
      selected: false,
      isToday: false
    })
  }

  // Current month days
  const today = new Date()
  today.setHours(0,0,0,0)

  for (let i = 1; i <= totalDays; i++) {
    const d = new Date(currentYear.value, currentMonth.value, i)
    d.setHours(0,0,0,0)

    const isToday = d.getTime() === today.getTime()
    const disabled = d < today // Disable past dates

    const selected = selectedDate.value ? d.getTime() === selectedDate.value.getTime() : false

    const offset = d.getTimezoneOffset()
    const localDate = new Date(d.getTime() - (offset*60*1000))
    const formattedDateString = localDate.toISOString().split('T')[0]
    const hasBookings = props.bookedDates.includes(formattedDateString)

    days.push({
      date: d,
      isCurrentMonth: true,
      disabled,
      selected,
      isToday,
      hasBookings
    })
  }

  // Next month padding days to fill grid of 42
  const remaining = 42 - days.length
  for (let i = 1; i <= remaining; i++) {
    const d = new Date(currentYear.value, currentMonth.value + 1, i)
    days.push({
      date: d,
      isCurrentMonth: false,
      disabled: true,
      selected: false,
      isToday: false
    })
  }

  return days
})

function selectDay(day) {
  if (day.disabled || !day.isCurrentMonth) return
  
  // Format Date to YYYY-MM-DD
  const offset = day.date.getTimezoneOffset()
  const localDate = new Date(day.date.getTime() - (offset*60*1000))
  const formatted = localDate.toISOString().split('T')[0]
  
  emit('update:modelValue', formatted)
}
</script>
