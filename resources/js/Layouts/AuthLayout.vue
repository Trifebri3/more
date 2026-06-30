<template>
  <div class="min-h-screen bg-gray-50 flex flex-col pb-16 sm:pb-0 font-body text-brand-tinta">
    <!-- Authenticated Layout -->
    <template v-if="authStore.isAuthenticated">
      <header class="bg-white border-b border-gray-100 sticky top-0 z-40 px-4 h-16 flex items-center justify-between">
        <router-link to="/" class="flex items-center">
          <img src="/images/logo.png" class="h-6 w-auto object-contain" alt="MORE Logo" />
        </router-link>
        <div class="flex items-center gap-3">
          <button @click="logout" class="text-sm font-heading font-semibold text-gray-500 hover:text-red-500 flex items-center gap-1 cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="hidden sm:inline">Logout</span>
          </button>
        </div>
      </header>

      <main class="flex-1 max-w-4xl w-full mx-auto p-4 sm:p-6">
        <router-view />
      </main>

      <!-- Bottom Nav for mobile -->
      <nav class="sm:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-100 z-40 flex justify-around py-2 h-16 shadow-lg">
        <router-link to="/" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="text-[10px] mt-0.5 font-heading">Home</span>
        </router-link>

        <router-link to="/booking" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
          <span class="text-[10px] mt-0.5 font-heading">Booking</span>
        </router-link>

        <router-link to="/customer/dashboard" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-[10px] mt-0.5 font-heading">Profile</span>
        </router-link>
      </nav>
    </template>

    <!-- Login View (Unauthenticated) -->
    <template v-else>
      <div class="flex-1 flex flex-col items-center justify-center p-6 min-h-screen">
        <div class="w-full max-w-md bg-white rounded-3xl border border-gray-150 p-8 shadow-sm">
          <div class="flex flex-col items-center mb-6">
            <router-link to="/" class="font-heading font-black text-3xl tracking-wider text-brand-vinto mb-2">MORE.</router-link>
            <p class="text-xs font-heading font-bold text-gray-500 uppercase tracking-widest">
              Staff & Admin Portal
            </p>
          </div>

          <!-- Alert error -->
          <div v-if="authError" class="mb-4 p-4 bg-red-50 text-red-600 rounded-xl text-sm font-body border border-red-100 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span>{{ authError }}</span>
          </div>

          <form @submit.prevent="handleLogin" class="space-y-4">
            <BaseInput v-model="loginForm.email" label="Email Address" type="email" required />
            <BaseInput v-model="loginForm.password" label="Password" type="password" required />
            
            <BaseButton type="submit" variant="primary" block :loading="authLoading">
              Masuk Portal
            </BaseButton>
          </form>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import BaseInput from '../Components/ui/BaseInput.vue'
import PhoneInput from '../Components/ui/PhoneInput.vue'
import BaseButton from '../Components/ui/BaseButton.vue'

const authStore = useAuthStore()
const router = useRouter()

const authLoading = ref(false)
const authError = ref('')

const loginForm = reactive({
  email: '',
  password: ''
})

async function handleLogin() {
  authLoading.value = true
  authError.value = ''
  const res = await authStore.login(loginForm)
  authLoading.value = false
  if (res.success) {
    if (authStore.user?.role === 'admin') router.push('/admin/dashboard')
    else if (authStore.user?.role === 'staff') router.push('/staff/dashboard')
    else if (authStore.user?.role === 'stylist') router.push('/stylist/dashboard')
    else router.push('/customer/dashboard')
  } else {
    authError.value = res.error || 'Login gagal.'
  }
}

async function logout() {
  await authStore.logout()
  router.push('/')
}
</script>
