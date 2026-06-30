<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-body text-brand-tinta">
    <!-- Top header with actions -->
    <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
      <div class="flex items-center gap-4">
        <router-link to="/" class="flex items-center">
          <img src="/images/logo.png" class="h-6 w-auto object-contain" alt="MORE Logo" />
        </router-link>
        <span class="text-xs font-heading font-bold uppercase tracking-wider px-3 py-1 bg-brand-pudar/10 text-brand-pudar rounded-full">
          Staff Portal
        </span>
      </div>

      <nav class="flex items-center gap-4 text-sm font-heading font-semibold">
        <router-link to="/staff/dashboard" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Overview</router-link>
        <router-link to="/staff/queue" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Queue Board</router-link>
        <router-link to="/staff/walk-in" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Walk-In</router-link>
        <router-link to="/staff/check-in" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Check-In</router-link>
        
        <button @click="logout" class="ml-4 p-2 text-gray-400 hover:text-red-500 rounded-lg cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </nav>
    </header>

    <!-- Content Area -->
    <main class="flex-1 p-4 sm:p-6 overflow-y-auto max-w-6xl w-full mx-auto">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

async function logout() {
  await authStore.logout()
  router.push('/')
}
</script>
