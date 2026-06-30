<template>
  <div class="min-h-screen bg-gray-50 flex font-body text-brand-tinta">
    <!-- Sidebar Drawer for Mobile / Sticky for Desktop -->
    <aside
      class="fixed inset-y-0 left-0 z-40 w-64 bg-brand-tinta text-white transform transition-transform duration-200 sm:translate-x-0 sm:static sm:flex sm:flex-col border-r border-gray-900"
      :class="[sidebarOpen ? 'translate-x-0' : '-translate-x-full']"
    >
      <!-- Logo block -->
      <div class="h-16 flex items-center justify-between px-6 border-b border-gray-900">
        <div class="flex items-center gap-2">
          <img src="/images/logo.png" class="h-6 w-auto object-contain" alt="MORE Logo" />
          <span class="font-heading font-black text-sm tracking-wider text-brand-vinto">ADMIN</span>
        </div>
        <button @click="sidebarOpen = false" class="sm:hidden p-1 hover:text-brand-vinto rounded-full">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation links -->
      <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto font-heading font-semibold text-sm">
        <router-link
          v-for="link in menuLinks"
          :key="link.to"
          :to="link.to"
          class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 hover:bg-gray-950 text-gray-400"
          active-class="bg-brand-vinto text-white hover:bg-brand-vinto"
        >
          <component :is="link.icon" class="w-5 h-5" />
          <span>{{ link.name }}</span>
        </router-link>
      </nav>

      <!-- Logout block -->
      <div class="p-4 border-t border-gray-900">
        <button @click="logout" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white rounded-xl transition-all font-heading font-semibold cursor-pointer">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span>Keluar</span>
        </button>
      </div>
    </aside>

    <!-- Overlay backdrop for mobile menu -->
    <div
      v-if="sidebarOpen"
      @click="sidebarOpen = false"
      class="fixed inset-0 z-30 bg-black/50 sm:hidden"
    ></div>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Top header bar -->
      <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <div class="flex items-center gap-3">
          <button @click="sidebarOpen = true" class="sm:hidden p-2 text-gray-500 hover:text-brand-vinto rounded-lg cursor-pointer">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          <h2 class="font-heading font-bold text-lg text-brand-tinta hidden sm:block">Panel Kontrol Utama</h2>
        </div>

        <div class="flex items-center gap-4">
          <span class="text-sm font-heading font-bold px-3 py-1 bg-brand-vinto/10 text-brand-vinto rounded-full">
            {{ authStore.user?.name || 'Administrator' }}
          </span>
        </div>
      </header>

      <!-- Main Slot -->
      <main class="flex-1 p-4 sm:p-6 overflow-y-auto max-w-6xl w-full mx-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, markRaw } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const router = useRouter()
const sidebarOpen = ref(false)

async function logout() {
  await authStore.logout()
  router.push('/')
}

// Icon schemas using raw SVG wrappers
const DashboardIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>`
}
const OutletIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>`
}
const ServiceIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.121 14.121L19 19m-7-7l7-7m-7 7a1 1 0 11-2 0 1 1 0 012 0zM3.879 3.879L8.8 8.8m0 0l-5.13-5.13M8.8 8.8a1 1 0 112 0 1 1 0 01-2 0z"/></svg>`
}
const StylistIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>`
}
const BookingIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>`
}
const PromoIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5a2 2 0 10-2 2h2zm0 0h4m-4 0H8m12 3a2 2 0 100-4h-4m-6 0H5a2 2 0 100 4h4m11 0a2 2 0 11-4 0v-5m-8 5a2 2 0 104 0v-5"/></svg>`
}
const CustomerIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
}
const AnalyticsIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>`
}
const SettingsIcon = {
  template: `<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`
}

const menuLinks = [
  { name: 'Dashboard', to: '/admin/dashboard', icon: markRaw(DashboardIcon) },
  { name: 'Outlets', to: '/admin/outlets', icon: markRaw(OutletIcon) },
  { name: 'Stylists', to: '/admin/stylists', icon: markRaw(StylistIcon) },
  { name: 'Services', to: '/admin/services', icon: markRaw(ServiceIcon) },
  { name: 'Bookings', to: '/admin/bookings', icon: markRaw(BookingIcon) },
  { name: 'Customers', to: '/admin/customers', icon: markRaw(CustomerIcon) },
  { name: 'Promotions', to: '/admin/promotions', icon: markRaw(PromoIcon) },
  { name: 'Analytics', to: '/admin/analytics', icon: markRaw(AnalyticsIcon) },
  { name: 'Settings', to: '/admin/settings', icon: markRaw(SettingsIcon) },
]
</script>
