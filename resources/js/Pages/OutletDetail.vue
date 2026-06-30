<template>
  <div v-if="loading" class="max-w-4xl mx-auto space-y-6 py-12">
    <BaseLoader skeleton height="260px" />
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <BaseLoader skeleton height="150px" v-for="i in 3" :key="i" />
    </div>
  </div>

  <div v-else-if="!outlet" class="max-w-xl mx-auto text-center py-16">
    <EmptyState title="Outlet Tidak Ditemukan" message="Maaf, detail informasi outlet yang Anda cari tidak tersedia." />
    <BaseButton @click="$router.push('/outlets')" variant="primary" class="mt-4">
      Kembali ke Daftar Outlet
    </BaseButton>
  </div>

  <div v-else class="space-y-12 pb-12 animate-fade-in">
    <!-- Outlet Header Banner -->
    <section class="relative bg-brand-tinta text-white rounded-3xl overflow-hidden min-h-[320px] flex items-end p-8 sm:p-12 shadow-sm">
      <div 
        class="absolute inset-0 bg-cover bg-center" 
        :style="{ backgroundImage: `url(${outlet.image_url || 'https://images.unsplash.com/photo-1585747860715-2ba37e788b70?q=80&w=1200&auto=format&fit=crop'})` }"
      ></div>
      <div class="absolute inset-0 bg-gradient-to-t from-brand-tinta via-brand-tinta/60 to-transparent"></div>
      
      <div class="relative z-10 max-w-xl space-y-3">
        <div class="flex items-center gap-2">
          <span class="text-[9px] uppercase tracking-widest font-heading font-black text-brand-vinto bg-brand-vinto/15 border border-brand-vinto/25 px-3 py-1 rounded-full">
            Outlet Utama
          </span>
          <div class="flex items-center gap-1 text-xs text-yellow-400 font-bold bg-black/40 px-2.5 py-0.5 rounded-full">
            <span>★</span>
            <span>{{ outlet.rating }}</span>
          </div>
        </div>
        <h1 class="text-3xl sm:text-5xl font-heading font-black uppercase tracking-tight text-white leading-tight">
          {{ outlet.name }}
        </h1>
        <p class="text-xs sm:text-sm text-gray-300 font-body leading-relaxed">
          {{ outlet.description || 'Definisikan ulang ketampanan Anda di studio potong rambut terdepan MORE.' }}
        </p>
      </div>
    </section>

    <!-- Content Split Layout -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-start">
      <!-- Left Column: Details -->
      <div class="md:col-span-2 space-y-8">
        <BaseCard class="p-6 space-y-6">
          <h3 class="text-lg font-heading font-black text-brand-tinta uppercase tracking-wide border-b border-gray-50 pb-3">
            Informasi Studio & Kontak
          </h3>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-sm leading-relaxed">
            <div class="space-y-4">
              <div>
                <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Alamat Lengkap</span>
                <p class="font-body text-brand-tinta mt-1">{{ outlet.address }}</p>
              </div>
              <div>
                <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Nomor Telepon</span>
                <p class="font-body text-brand-tinta mt-1">{{ outlet.phone || '-' }}</p>
              </div>
            </div>

            <div class="space-y-4">
              <div>
                <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Jam Operasional</span>
                <p class="font-body text-brand-tinta mt-1">10:00 - 22:00 WIB (Setiap Hari)</p>
              </div>
              <div>
                <span class="text-[10px] uppercase font-heading font-bold text-gray-400 tracking-wider">Email Kontak</span>
                <p class="font-body text-brand-tinta mt-1">support@more.id</p>
              </div>
            </div>
          </div>

          <!-- Social media -->
          <div class="pt-4 border-t border-gray-50 flex items-center gap-3">
            <span class="text-xs font-heading font-bold text-gray-400 uppercase tracking-wider">Sosial Media:</span>
            <a href="https://instagram.com" target="_blank" class="p-2 bg-gray-50 hover:bg-brand-vinto hover:text-white rounded-full text-gray-400 transition">
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            <a href="https://tiktok.com" target="_blank" class="p-2 bg-gray-50 hover:bg-brand-vinto hover:text-white rounded-full text-gray-400 transition">
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.95-1.72-.1.08-.18.17-.27.25-.02 3.19-.01 6.38-.02 9.57-.02 1.91-.71 3.86-2.11 5.16-1.57 1.5-3.89 2.09-6.01 1.83-2.26-.23-4.43-1.63-5.32-3.75-.98-2.21-.69-4.96.79-6.84 1.34-1.75 3.65-2.61 5.8-2.31.01 1.42-.01 2.84 0 4.26-1.1-.22-2.35.03-3.15.84-.78.73-.97 1.94-.52 2.88.46 1.05 1.69 1.67 2.82 1.51 1.1-.1 2.05-.98 2.23-2.07.13-1.89.04-3.79.08-5.69.01-4.22.01-8.44.01-12.66z"/>
              </svg>
            </a>
            <a :href="'https://wa.me/' + (outlet.phone ? outlet.phone.replace(/[^0-9]/g, '') : '')" target="_blank" class="p-2 bg-gray-50 hover:bg-brand-vinto hover:text-white rounded-full text-gray-400 transition">
              <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24zm6.59-4.846c1.6.95 2.68 1.49 4.78 1.491 5.485.002 9.947-4.461 9.95-9.95.002-2.657-1.03-5.155-2.91-7.037a9.9 9.9 0 00-6.99-2.913c-5.49 0-9.954 4.464-9.957 9.955-.001 1.986.517 3.926 1.503 5.64l-.99 3.61 3.704-.973z"/>
              </svg>
            </a>
          </div>
        </BaseCard>

        <!-- Rules and Guidelines -->
        <section class="bg-white border border-gray-150 rounded-3xl p-6 space-y-4">
          <div class="flex items-center gap-2 text-brand-orange">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <h4 class="text-sm font-heading font-black uppercase tracking-wider text-brand-tinta">Ketentuan Booking Outlet</h4>
          </div>
          <ul class="space-y-2 font-body text-xs text-gray-500 list-disc pl-5">
            <li>Sesi berdurasi tepat 1 jam untuk efisiensi antrean.</li>
            <li>Keterlambatan 15 menit tanpa check-in di outlet mengakibatkan tiket booking hangus secara otomatis.</li>
            <li>Silakan datang 5 menit sebelum jadwal pemesanan Anda.</li>
          </ul>
        </section>
      </div>

      <!-- Right Column: Stylists and Booking CTA -->
      <div class="space-y-6">
        <!-- Direct Booking Call To Action Card -->
        <BaseCard class="p-6 bg-brand-tinta text-white border-none shadow-lg space-y-4">
          <h4 class="text-sm font-heading font-black text-brand-vinto uppercase tracking-widest">Slay Your Style</h4>
          <p class="text-xs text-gray-400 font-body leading-relaxed">
            Pilih stylist andalan Anda dan pesan sesi grooming premium Anda sekarang di outlet ini.
          </p>
          <BaseButton @click="bookAtThisOutlet" variant="primary" block class="text-brand-tinta font-bold">
            Booking Di Outlet Ini
          </BaseButton>
        </BaseCard>

        <!-- Stylist Team List -->
        <div class="space-y-4">
          <h4 class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest">Tim Stylist Outlet</h4>
          
          <div v-if="!outlet.stylists || outlet.stylists.length === 0" class="text-xs text-gray-400 font-body italic">
            Belum ada stylist aktif di outlet ini.
          </div>

          <div v-else class="space-y-3">
            <div 
              v-for="s in outlet.stylists" 
              :key="s.id" 
              class="bg-white border border-gray-100 p-4 rounded-2xl shadow-xs flex items-center gap-3"
            >
              <img 
                :src="s.avatar_url || '/images/logo.png'" 
                class="w-12 h-12 rounded-full object-cover border border-gray-100 shrink-0" 
                alt="Avatar" 
              />
              <div class="space-y-0.5">
                <div class="flex items-center gap-1">
                  <h5 class="text-xs font-heading font-bold text-brand-tinta">{{ s.name }}</h5>
                  <span class="text-[10px] text-yellow-500">★ {{ s.rating }}</span>
                </div>
                <p class="text-[9px] text-brand-vinto font-body font-bold uppercase tracking-wider leading-none">{{ s.specialty }}</p>
                <p class="text-[9px] text-gray-400 font-body mt-0.5">Pengalaman: {{ s.experience_years }} Tahun</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBookingStore } from '../stores/booking'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseCard from '../Components/ui/BaseCard.vue'
import BaseLoader from '../Components/ui/BaseLoader.vue'
import EmptyState from '../Components/common/EmptyState.vue'

const route = useRoute()
const router = useRouter()
const bookingStore = useBookingStore()

const loading = ref(true)
const outlet = ref(null)

async function fetchOutletDetail() {
  loading.value = true
  try {
    const api = (await import('../utils/api')).default
    const response = await api.get(`/v1/outlets/${route.params.id}`)
    outlet.value = response.data
  } catch (error) {
    console.error('Failed to load outlet details:', error)
  } finally {
    loading.value = false
  }
}

function bookAtThisOutlet() {
  if (!outlet.value) return
  bookingStore.reset()
  bookingStore.bookingData.outlet_id = outlet.value.id
  bookingStore.selectedOutlet = outlet.value
  bookingStore.currentStep = 2 // Proceed straight to services list step
  router.push('/booking')
}

onMounted(() => {
  fetchOutletDetail()
})
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.25s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
</style>
