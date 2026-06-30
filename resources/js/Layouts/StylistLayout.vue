<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-body text-brand-tinta pb-16 sm:pb-0">
    <!-- Top header with actions -->
    <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
      <div class="flex items-center gap-3">
        <router-link to="/" class="flex items-center">
          <img src="/images/logo.png" class="h-6 w-auto object-contain" alt="MORE Logo" />
        </router-link>
        <span class="text-[10px] sm:text-xs font-heading font-bold uppercase tracking-wider px-2.5 py-1 bg-brand-natural/20 text-brand-tinta rounded-full">
          Stylist Portal
        </span>
      </div>

      <!-- Desktop Navigation links -->
      <nav class="hidden sm:flex items-center gap-4 text-sm font-heading font-semibold">
        <router-link to="/stylist/dashboard" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Today's Schedule</router-link>
        <router-link to="/stylist/schedule" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Calendar View</router-link>
        <router-link to="/stylist/leave" class="text-gray-500 hover:text-brand-vinto" active-class="text-brand-vinto">Cuti</router-link>
        
        <button @click="logout" class="ml-4 p-2 text-gray-400 hover:text-red-500 rounded-lg cursor-pointer transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </nav>

      <!-- Mobile Logout (Header) -->
      <button @click="logout" class="sm:hidden p-2 text-gray-400 hover:text-red-500 rounded-lg cursor-pointer">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
      </button>
    </header>

    <!-- Mobile Bottom Navigation Bar -->
    <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40 flex justify-around py-2 h-16 shadow-lg">
      <router-link to="/stylist/dashboard" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-[10px] mt-0.5 font-heading">Hari Ini</span>
      </router-link>
      
      <router-link to="/stylist/schedule" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="text-[10px] mt-0.5 font-heading">Jadwal</span>
      </router-link>

      <router-link @click="sidebarOpen = false" to="/stylist/leave" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="text-[10px] mt-0.5 font-heading">Cuti</span>
      </router-link>
    </nav>

    <!-- Content Area -->
    <main class="flex-1 p-4 sm:p-6 overflow-y-auto max-w-4xl w-full mx-auto">
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
