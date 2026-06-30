<template>
  <div class="min-h-screen flex flex-col bg-gray-50 text-brand-tinta font-body pb-16 sm:pb-0">
    <!-- Desktop Header -->
    <header class="hidden sm:block bg-white border-b border-gray-100 sticky top-0 z-40">
      <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
        <router-link to="/" class="flex items-center gap-2">
          <img src="/images/logo.png" class="h-8 w-auto object-contain" alt="MORE Logo" />
        </router-link>
        <nav class="flex items-center gap-6 font-heading font-semibold text-sm">
          <router-link to="/" class="text-gray-600 hover:text-brand-vinto" active-class="text-brand-vinto">Home</router-link>
          <router-link to="/booking" class="text-gray-600 hover:text-brand-vinto" active-class="text-brand-vinto">Book Now</router-link>
          <router-link to="/outlets" class="text-gray-600 hover:text-brand-vinto" active-class="text-brand-vinto">Outlets</router-link>
          <router-link to="/services" class="text-gray-600 hover:text-brand-vinto" active-class="text-brand-vinto">Services</router-link>
          <router-link to="/contact" class="text-gray-600 hover:text-brand-vinto" active-class="text-brand-vinto">Contact</router-link>
          
          <template v-if="authStore.isAuthenticated">
            <router-link :to="getDashboardRoute()" class="px-4 py-2 bg-brand-vinto text-white rounded-xl hover:bg-primary-dark">
              Dashboard
            </router-link>
          </template>
        </nav>
      </div>
    </header>

    <!-- Mobile Header -->
    <header class="block sm:hidden bg-white border-b border-gray-100 sticky top-0 z-40 px-4 h-14 flex items-center justify-between">
      <img src="/images/logo.png" class="h-6 w-auto object-contain" alt="MORE Logo" />
      <div v-if="authStore.isAuthenticated" class="text-xs font-semibold px-3 py-1 bg-brand-vinto/10 text-brand-vinto rounded-full">
        Tier: {{ authStore.user?.role || 'Guest' }}
      </div>
    </header>

    <!-- Content slot -->
    <main class="flex-1 max-w-6xl w-full mx-auto p-4 sm:p-6">
      <router-view />
    </main>

    <!-- Mobile Bottom Navigation Bar -->
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

      <router-link to="/outlets" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <span class="text-[10px] mt-0.5 font-heading">Outlets</span>
      </router-link>

      <router-link to="/contact" class="flex flex-col items-center justify-center w-16 text-gray-400" active-class="text-brand-vinto font-semibold">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
        <span class="text-[10px] mt-0.5 font-heading">Contact</span>
      </router-link>
    </nav>

    <!-- Desktop Footer -->
    <footer class="hidden sm:block bg-brand-tinta text-white py-12 mt-12">
      <div class="max-w-6xl mx-auto px-4 grid grid-cols-3 gap-8">
        <div>
          <img src="/images/logoputih.png" class="h-8 w-auto object-contain" alt="MORE Logo" />
          <p class="text-sm text-gray-400 mt-4 leading-relaxed">
            Re-define your grooming experience with Indonesia's premier digital-first multi-outlet barbershop platform.
          </p>
        </div>
        <div>
          <h4 class="font-heading font-bold text-base mb-4 uppercase tracking-wider text-brand-natural">Hubungi Kami</h4>
          <ul class="text-sm text-gray-400 space-y-3 font-body">
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <a href="https://wa.me/6282298347730" target="_blank" class="hover:text-brand-vinto transition">
                +62 822-9834-7730
              </a>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <span>support@more.id</span>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <a 
                href="https://www.google.com/maps?cid=11617508386330832568&g_mp=CiVnb29nbGUubWFwcy5wbGFjZXMudjEuUGxhY2VzLkdldFBsYWNlEAMYASAF&hl=en-US&source=embed" 
                target="_blank" 
                class="hover:text-brand-vinto transition"
              >
                Google Maps Location
              </a>
            </li>
          </ul>
        </div>
        <div>
          <h4 class="font-heading font-bold text-base mb-4 uppercase tracking-wider text-brand-natural">Media Sosial</h4>
          <ul class="text-sm text-gray-400 space-y-3 font-body">
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
              <a href="https://www.instagram.com/morehairstudio?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="hover:text-brand-vinto transition">
                Instagram
              </a>
            </li>
            <li class="flex items-center gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.95-1.72-.1.08-.18.17-.27.25-.02 3.19-.01 6.38-.02 9.57-.02 1.91-.71 3.86-2.11 5.16-1.57 1.5-3.89 2.09-6.01 1.83-2.26-.23-4.43-1.63-5.32-3.75-.98-2.21-.69-4.96.79-6.84 1.34-1.75 3.65-2.61 5.8-2.31.01 1.42-.01 2.84 0 4.26-1.1-.22-2.35.03-3.15.84-.78.73-.97 1.94-.52 2.88.46 1.05 1.69 1.67 2.82 1.51 1.1-.1 2.05-.98 2.23-2.07.13-1.89.04-3.79.08-5.69.01-4.22.01-8.44.01-12.66z"/>
              </svg>
              <a href="https://www.tiktok.com/@morehairstudio" target="_blank" class="hover:text-brand-vinto transition">
                TikTok
              </a>
            </li>
          </ul>
          <div class="mt-6 pt-4 border-t border-zinc-800">
            <router-link to="/login" class="text-xs text-zinc-500 hover:text-brand-vinto transition-colors">
              Staff & Admin Portal →
            </router-link>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

function getDashboardRoute() {
  const role = authStore.user?.role
  if (role === 'admin') return '/admin/dashboard'
  if (role === 'staff') return '/staff/dashboard'
  if (role === 'stylist') return '/stylist/dashboard'
  return '/customer/dashboard'
}
</script>
