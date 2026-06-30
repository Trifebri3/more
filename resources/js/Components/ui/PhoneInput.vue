<template>
  <div class="relative w-full mb-4">
    <!-- Input wrapper with country code dropdown prepended -->
    <div class="relative flex items-center border rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-brand-vinto bg-white"
      :class="[
        error ? 'border-red-500' : 'border-gray-200'
      ]"
    >
      <!-- Country Code Selector Dropdown -->
      <div class="relative flex items-center bg-gray-50 border-r border-gray-200 text-gray-500 font-heading font-semibold text-sm">
        <select
          :value="selectedCountryCode"
          @change="onCountryChange"
          :disabled="disabled"
          class="h-full pl-3 pr-8 py-4 bg-transparent outline-none cursor-pointer appearance-none text-brand-tinta font-heading font-bold"
        >
          <option v-for="c in countries" :key="c.code" :value="c.code">
            {{ c.flag }} {{ c.code }}
          </option>
        </select>
        <!-- Arrow down indicator icon -->
        <span class="absolute right-2.5 pointer-events-none text-gray-400">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
          </svg>
        </span>
      </div>
      
      <input
        type="tel"
        :value="rawInput"
        @input="onInput"
        placeholder="812-3456-7890"
        :disabled="disabled"
        class="peer w-full px-4 pt-6 pb-2 text-base text-brand-tinta font-body placeholder-gray-300 focus:outline-none disabled:bg-gray-50 disabled:opacity-70 transition-all duration-200"
      />

      <label
        class="absolute left-[125px] top-2 text-xs text-gray-400 font-heading font-medium transition-all duration-200 pointer-events-none origin-[0_0] peer-placeholder-shown:text-base peer-placeholder-shown:top-4 peer-focus:top-2 peer-focus:text-xs peer-focus:text-brand-vinto"
        :class="{ 
          'text-red-500': error,
          'top-2 text-xs': rawInput
        }"
      >
        {{ label }}
      </label>
    </div>

    <!-- Error message text -->
    <p v-if="error" class="text-xs text-red-500 mt-1 pl-1 font-body">
      {{ error }}
    </p>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  label: {
    type: String,
    required: true
  },
  disabled: {
    type: Boolean,
    default: false
  },
  error: {
    type: String,
    default: ''
  }
})

const emit = defineEmits(['update:modelValue'])

const selectedCountryCode = ref('+62')
const rawInput = ref('')

const countries = [
  { flag: '🇮🇩', code: '+62', name: 'Indonesia' },
  { flag: '🇲🇾', code: '+60', name: 'Malaysia' },
  { flag: '🇸🇬', code: '+65', name: 'Singapore' },
  { flag: '🇺🇸', code: '+1', name: 'United States' },
  { flag: '🇬🇧', code: '+44', name: 'United Kingdom' },
  { flag: '🇦🇺', code: '+61', name: 'Australia' },
  { flag: '🇯🇵', code: '+81', name: 'Japan' },
  { flag: '🇹🇭', code: '+66', name: 'Thailand' },
]

// Initialize and sync
function syncFromModel() {
  if (!props.modelValue) {
    rawInput.value = ''
    return
  }
  // Find which country code match
  const matchedCountry = countries.find(c => props.modelValue.startsWith(c.code))
  if (matchedCountry) {
    selectedCountryCode.value = matchedCountry.code
    rawInput.value = props.modelValue.slice(matchedCountry.code.length)
  } else if (props.modelValue.startsWith('+')) {
    const match = props.modelValue.match(/^\+(\d{1,4})/)
    if (match) {
      selectedCountryCode.value = `+${match[1]}`
      rawInput.value = props.modelValue.slice(match[0].length)
    } else {
      selectedCountryCode.value = '+62'
      rawInput.value = props.modelValue
    }
  } else {
    selectedCountryCode.value = '+62'
    rawInput.value = props.modelValue
  }
}

onMounted(() => {
  syncFromModel()
})

// Watch model changes from outside (e.g. store resets or auto-lookup)
watch(() => props.modelValue, () => {
  const currentCombined = getCombinedValue()
  if (props.modelValue !== currentCombined) {
    syncFromModel()
  }
})

function getCombinedValue() {
  let val = rawInput.value.replace(/\D/g, '') // remove non-digits
  if (val.startsWith('0')) {
    val = val.slice(1)
  }
  return val ? `${selectedCountryCode.value}${val}` : ''
}

function onInput(event) {
  rawInput.value = event.target.value
  updateModel()
}

function onCountryChange(event) {
  selectedCountryCode.value = event.target.value
  updateModel()
}

function updateModel() {
  const combined = getCombinedValue()
  emit('update:modelValue', combined)
}
</script>
