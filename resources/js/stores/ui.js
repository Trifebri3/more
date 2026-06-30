import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useUiStore = defineStore('ui', () => {
  const sidebarOpen = ref(false)
  const isDarkMode = ref(false)
  const activeKioskTimeout = ref(null)

  function toggleSidebar() {
    sidebarOpen.value = !sidebarOpen.value
  }

  function setSidebar(val) {
    sidebarOpen.value = val
  }

  function toggleDarkMode() {
    isDarkMode.value = !isDarkMode.value
  }

  return {
    sidebarOpen,
    isDarkMode,
    activeKioskTimeout,
    toggleSidebar,
    setSidebar,
    toggleDarkMode
  }
})
