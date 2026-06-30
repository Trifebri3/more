<template>
  <div id="app">
    <router-view />
    <AccessibilityWidget />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'
import AccessibilityWidget from './Components/AccessibilityWidget.vue'

const authStore = useAuthStore()

onMounted(() => {
  authStore.initAuth()
})
</script>
