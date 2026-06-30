<template>
  <div class="space-y-20 pb-12">
    <!-- Redesigned Luxury Grey Hero with Subtle Grey Accent & Gen-Z Vibe -->
    <section class="relative bg-[#2A2A2A] text-white rounded-3xl overflow-hidden py-16 px-8 sm:px-16 flex flex-col md:flex-row items-center justify-between gap-12 min-h-[460px] shadow-sm border-t-4 border-zinc-700">
      <!-- Muted background visual pattern -->
      <div class="absolute inset-0 opacity-5 bg-[url('https://images.unsplash.com/photo-1585747860715-2ba37e788b70?q=80&w=1200&auto=format&fit=crop')] bg-cover bg-center"></div>
      
      <div class="max-w-lg space-y-6 z-10 text-center md:text-left">
        <span class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-widest font-heading font-black text-brand-vinto bg-brand-vinto/10 border border-brand-vinto/20 px-3.5 py-1.5 rounded-full">
            Premium Barber Experience
        </span>
        
        <h1 class="text-3xl sm:text-5xl font-heading font-black leading-tight text-white uppercase tracking-tight">
          Level Up Your <br>
          <span class="text-brand-vinto">Hair Game</span>. Period.
        </h1>
        
        <p class="text-xs sm:text-sm font-body text-gray-300 leading-relaxed max-w-md">
          Barber premium anti-gagal buat lo yang pengen looks maksimal. Ditangani langsung sama stylist berpengalaman yang paham tren paling hype. Slay your style, booking sat-set no debat.
        </p>

        <div class="flex flex-col sm:flex-row justify-center md:justify-start gap-4 pt-2">
          <BaseButton @click="$router.push('/booking')" variant="primary" size="lg" class="shadow-lg hover:shadow-brand-vinto/25">
            Booking Sekarang
          </BaseButton>
          <BaseButton @click="$router.push('/outlets')" variant="ghost" size="lg" class="border-white/10 text-white hover:bg-white/5 backdrop-blur-xs">
            Cari Outlet
          </BaseButton>
        </div>
      </div>

      <!-- Stylist Image in Luxury Frame -->
      <div class="w-full md:w-1/2 flex justify-center relative z-10">
        <div class="relative w-64 h-64 sm:w-80 sm:h-80 rounded-2xl overflow-hidden shadow-2xl border border-white/10 p-2 bg-white/5 backdrop-blur-md">
          <img src="https://images.unsplash.com/photo-1503951914875-452162b0f3f1?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover rounded-xl" alt="Barbershop" />
        </div>
      </div>
    </section>

    <!-- Standard Pillars with Gen-Z Copywriting -->
    <section class="space-y-12">
      <div class="text-center max-w-xl mx-auto space-y-3">
        <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight uppercase">Estetika Maksimal</h2>
        <p class="text-xs sm:text-sm text-gray-500 font-body">Komitmen kenyamanan dan ketepatan potongan di setiap sudut outlet kami.</p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-[#FAF8F5]/85 border border-gray-150 rounded-3xl p-6 shadow-sm hover:border-brand-vinto transition-all duration-300 space-y-4">
          <div class="w-12 h-12 bg-brand-vinto/10 text-brand-vinto rounded-xl flex items-center justify-center">
            <!-- Razor Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-xs font-heading font-black text-brand-tinta uppercase tracking-widest">Looks Anti-Gagal</h3>
          <p class="text-xs text-gray-500 font-body leading-relaxed">
            Standardisasi internasional untuk semua jenis potongan rambut. Konsisten rapi di mana pun lo mampir.
          </p>
        </div>

        <div class="bg-[#FAF8F5]/85 border border-gray-150 rounded-3xl p-6 shadow-sm hover:border-brand-vinto transition-all duration-300 space-y-4">
          <div class="w-12 h-12 bg-brand-vinto/10 text-brand-vinto rounded-xl flex items-center justify-center">
            <!-- Scissors Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7a1 1 0 11-2 0 1 1 0 012 0zM3.879 3.879L8.8 8.8m0 0l-5.13-5.13M8.8 8.8a1 1 0 112 0 1 1 0 01-2 0z" />
            </svg>
          </div>
          <h3 class="text-xs font-heading font-black text-brand-tinta uppercase tracking-widest">Slay Your Style</h3>
          <p class="text-xs text-gray-500 font-body leading-relaxed">
            Bukan sekadar potong rambut biasa. Kami merancang gaya yang presisi sesuai bentuk kepala dan karakter lo.
          </p>
        </div>

        <div class="bg-[#FAF8F5]/85 border border-gray-150 rounded-3xl p-6 shadow-sm hover:border-brand-vinto transition-all duration-300 space-y-4">
          <div class="w-12 h-12 bg-brand-vinto/10 text-brand-vinto rounded-xl flex items-center justify-center">
            <!-- Coffee/Cup Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m0-12.728l.707.707m12.728 12.728l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z" />
            </svg>
          </div>
          <h3 class="text-xs font-heading font-black text-brand-tinta uppercase tracking-widest">Vibe Check & Kopi</h3>
          <p class="text-xs text-gray-500 font-body leading-relaxed">
            Konsultasi santai 1-on-1 bareng stylist profesional sambil menikmati racikan kopi premium gratis.
          </p>
        </div>

        <div class="bg-[#FAF8F5]/85 border border-gray-150 rounded-3xl p-6 shadow-sm hover:border-brand-vinto transition-all duration-300 space-y-4">
          <div class="w-12 h-12 bg-brand-vinto/10 text-brand-vinto rounded-xl flex items-center justify-center">
            <!-- Chair/Ergonomics Icon -->
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h3 class="text-xs font-heading font-black text-brand-tinta uppercase tracking-widest">Comfy Throne</h3>
          <p class="text-xs text-gray-500 font-body leading-relaxed">
            Fasilitas kursi barber ergonomis terbaik untuk memastikan lo bisa rileks dan santai maksimal selama treatment.
          </p>
        </div>
      </div>
    </section>

    <!-- Popular Services (Classic Shimmer Overlay cards) -->
    <section class="space-y-12">
      <div class="flex items-end justify-between">
        <div>
          <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight uppercase">Signature Ritual</h2>
          <p class="text-xs sm:text-sm text-gray-500 font-body">Layanan terlaris pilihan pelanggan setia MORE.</p>
        </div>
        <BaseButton @click="$router.push('/services')" variant="ghost" size="sm" class="border-gray-200 text-xs">
          Lihat Semua
        </BaseButton>
      </div>

      <div v-if="loading" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <BaseLoader skeleton height="220px" v-for="i in 3" :key="i" />
      </div>
      <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="service in popularServices" :key="service.id" class="bg-white border border-gray-150 rounded-3xl overflow-hidden flex flex-col h-full hover:-translate-y-1 hover:shadow-md transition-all duration-300">
          <div class="h-56 overflow-hidden bg-gray-150 relative">
            <img :src="service.image_url" class="w-full h-full object-cover" alt="Service" />
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
          </div>
          <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
            <div class="space-y-2">
              <span class="text-[9px] font-heading font-bold text-brand-vinto uppercase tracking-widest bg-brand-vinto/10 px-3 py-1 rounded-full">
                {{ service.category?.name || 'Layanan' }}
              </span>
              <h3 class="text-base font-heading font-bold text-brand-tinta">{{ service.name }}</h3>
              <p class="text-xs text-gray-400 font-body">{{ service.duration }} menit</p>
              <p class="text-xs text-gray-500 font-body leading-relaxed">{{ service.description }}</p>
            </div>
            <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-2">
              <span class="text-base font-heading font-black text-brand-tinta">{{ formatPrice(service.price) }}</span>
              <BaseButton @click="bookService(service)" variant="primary" size="sm" class="text-brand-tinta">
                Pilih Sesi
              </BaseButton>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Testimonials Slider (Luxury Warm Grey background with blur) -->
    <section class="bg-brand-natural rounded-3xl p-8 sm:p-16 space-y-12 border border-gray-200">
      <div class="text-center max-w-xl mx-auto space-y-3">
        <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight uppercase">Vibe Check Dari Mereka</h2>
        <p class="text-xs sm:text-sm text-gray-600 font-body">Review jujur dari pelanggan setia kami.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div v-for="(testi, idx) in testimonials" :key="idx" class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white/20 shadow-sm space-y-4 relative">
          <!-- Star SVG Row -->
          <div class="flex items-center gap-1 text-brand-vinto">
            <svg v-for="star in 5" :key="star" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <p class="text-xs text-gray-600 font-body leading-relaxed italic">
            "{{ testi.content }}"
          </p>
          <div class="flex items-center gap-3 pt-4 border-t border-gray-150">
            <div class="w-9 h-9 rounded-full bg-brand-orange text-white flex items-center justify-center font-heading font-black text-[10px]">
              {{ testi.initials }}
            </div>
            <div>
              <h4 class="text-xs font-heading font-bold text-brand-tinta">{{ testi.name }}</h4>
              <p class="text-[10px] text-gray-400 font-body">{{ testi.role }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- About MORE (Tentang Kami) -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
      <div class="space-y-6">
        <span class="text-[10px] uppercase tracking-widest font-heading font-black text-brand-vinto bg-brand-vinto/10 border border-brand-vinto/20 px-3.5 py-1.5 rounded-full inline-block">
          Tentang Kami
        </span>
        <h2 class="text-3xl font-heading font-black uppercase text-brand-tinta leading-tight">
          Re-define Your <br>Grooming Experience
        </h2>
        <p class="text-sm font-body text-gray-500 leading-relaxed">
          MORE Barber lahir untuk menghadirkan standar potong rambut pria ke level tertinggi. Kami memadukan keahlian teknik mencukur kelas dunia dengan kenyamanan modern, kopi premium, dan kemudahan booking digital berbasis web. 
        </p>
        <p class="text-sm font-body text-gray-500 leading-relaxed">
          Setiap kunjungan dijamin presisi, nyaman, dan disesuaikan khusus untuk mengekspresikan jati diri Anda yang sesungguhnya.
        </p>
      </div>
      <div class="relative rounded-3xl overflow-hidden shadow-lg h-72">
        <img src="https://images.unsplash.com/photo-1621605815971-fbc98d665033?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover" alt="Barber Tools" />
      </div>
    </section>

    <!-- Stylist Team Grouped by Outlet -->
    <section class="space-y-12">
      <div class="text-center max-w-xl mx-auto space-y-3">
        <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight uppercase">Tim Stylist & Rating</h2>
        <p class="text-xs sm:text-sm text-gray-500 font-body">Pilih pemotong andalan Anda di outlet terdekat.</p>
      </div>

      <div class="space-y-12">
        <div v-for="group in stylistsByOutlet" :key="group.outlet.id" class="space-y-6">
          <div class="flex items-center gap-4">
            <router-link :to="'/outlets/' + group.outlet.id" class="hover:text-brand-vinto transition">
              <h3 class="text-lg font-heading font-black uppercase tracking-wide">
                {{ group.outlet.name }}
              </h3>
            </router-link>
            <span class="h-[1px] bg-gray-200 flex-1"></span>
          </div>

          <div v-if="group.stylists.length === 0" class="text-xs text-gray-400 font-body italic">
            Belum ada stylist aktif untuk outlet ini.
          </div>

          <div v-else class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div 
              v-for="s in group.stylists" 
              :key="s.id" 
              class="bg-white border border-gray-100 rounded-3xl p-5 shadow-xs flex items-center gap-4 hover:border-brand-vinto/30 transition-all duration-200"
            >
              <img 
                :src="s.avatar_url || '/images/logo.png'" 
                class="w-14 h-14 rounded-full object-cover border border-gray-100 shrink-0" 
                alt="Avatar" 
              />
              <div class="space-y-1">
                <div class="flex items-center gap-1.5 flex-wrap">
                  <h4 class="text-sm font-heading font-bold text-brand-tinta leading-tight">{{ s.name }}</h4>
                  <div class="flex items-center gap-0.5 text-xs text-yellow-500 shrink-0">
                    <span>★</span>
                    <span class="font-bold">{{ s.rating }}</span>
                  </div>
                </div>
                <p class="text-[10px] text-brand-vinto font-body font-bold uppercase tracking-wider">{{ s.specialty }}</p>
                <p class="text-[10px] text-gray-400 font-body">Pengalaman: {{ s.experience_years }} Tahun</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contacts & Location Details -->
    <section class="space-y-12">
      <div class="text-center max-w-xl mx-auto space-y-3">
        <h2 class="text-2xl sm:text-3xl font-heading font-black tracking-tight uppercase">Hubungi & Kunjungi Kami</h2>
        <p class="text-xs sm:text-sm text-gray-500 font-body">Lokasi fisik, kontak langsung, dan media sosial resmi MORE Barber.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div 
          v-for="o in outlets" 
          :key="o.id" 
          class="bg-white border border-gray-100 p-6 rounded-3xl shadow-xs space-y-4"
        >
          <router-link :to="'/outlets/' + o.id" class="hover:text-brand-vinto transition block border-b border-gray-50 pb-3">
            <h3 class="text-base font-heading font-black uppercase tracking-wide">
              {{ o.name }}
            </h3>
          </router-link>
          <div class="space-y-3 font-body text-xs text-gray-500">
            <div class="flex gap-2">
              <svg class="w-4 h-4 text-brand-vinto shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <div>
                <strong class="text-brand-tinta">Alamat:</strong>
                <p class="mt-0.5">{{ o.address }}</p>
              </div>
            </div>
            
            <div class="flex gap-2 items-center">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.94.725l.548 2.2a1 1 0 01-.321.988l-1.305.98a10.582 10.582 0 004.872 4.872l.98-1.305a1 1 0 01.988-.321l2.2.548a1 1 0 01.725.94V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
              <div>
                <strong class="text-brand-tinta">Telepon:</strong> {{ o.phone }}
              </div>
            </div>

            <div class="flex gap-2 items-center">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
              <div>
                <strong class="text-brand-tinta">Email:</strong> support@more.id
              </div>
            </div>

            <div class="flex gap-2 items-center">
              <svg class="w-4 h-4 text-brand-vinto shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <div>
                <strong class="text-brand-tinta">Jam Buka:</strong> 10:00 - 22:00 WIB
              </div>
            </div>
          </div>
          
          <!-- Social media indicators -->
          <div class="flex items-center gap-3 pt-3 border-t border-gray-50 text-gray-400">
            <a href="https://instagram.com" target="_blank" class="hover:text-brand-vinto transition">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
              </svg>
            </a>
            <a href="https://tiktok.com" target="_blank" class="hover:text-brand-vinto transition">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.17-2.86-.74-3.95-1.72-.1.08-.18.17-.27.25-.02 3.19-.01 6.38-.02 9.57-.02 1.91-.71 3.86-2.11 5.16-1.57 1.5-3.89 2.09-6.01 1.83-2.26-.23-4.43-1.63-5.32-3.75-.98-2.21-.69-4.96.79-6.84 1.34-1.75 3.65-2.61 5.8-2.31.01 1.42-.01 2.84 0 4.26-1.1-.22-2.35.03-3.15.84-.78.73-.97 1.94-.52 2.88.46 1.05 1.69 1.67 2.82 1.51 1.1-.1 2.05-.98 2.23-2.07.13-1.89.04-3.79.08-5.69.01-4.22.01-8.44.01-12.66z"/>
              </svg>
            </a>
            <a :href="'https://wa.me/' + o.phone.replace(/[^0-9]/g, '')" target="_blank" class="hover:text-brand-vinto transition">
              <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.73-1.455L0 24zm6.59-4.846c1.6.95 2.68 1.49 4.78 1.491 5.485.002 9.947-4.461 9.95-9.95.002-2.657-1.03-5.155-2.91-7.037a9.9 9.9 0 00-6.99-2.913c-5.49 0-9.954 4.464-9.957 9.955-.001 1.986.517 3.926 1.503 5.64l-.99 3.61 3.704-.973z"/>
              </svg>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- Rules / Terms & Conditions -->
    <section class="bg-white border border-gray-150 rounded-3xl p-8 sm:p-12 space-y-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-brand-orange/10 text-brand-orange flex items-center justify-center">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        </div>
        <h3 class="text-lg font-heading font-black text-brand-tinta uppercase tracking-wide">
          Syarat & Ketentuan Booking
        </h3>
      </div>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 font-body text-sm text-gray-500 leading-relaxed">
        <ul class="space-y-3 list-disc pl-5">
          <li><strong>Batas Sesi 1 Jam</strong>: Setiap pelanggan mendapatkan alokasi waktu maksimal 1 jam per sesi pemotongan demi menjaga kelancaran antrean.</li>
          <li><strong>Kebijakan Hangus 15 Menit</strong>: Jika Anda tidak melakukan check-in di outlet dalam waktu 15 menit dari jadwal booking, tiket Anda akan hangus otomatis dan slot akan dibuka kembali untuk pelanggan lain.</li>
          <li><strong>Nomor WhatsApp Aktif</strong>: Gunakan nomor WhatsApp yang valid untuk mempermudah pengecekan status tiket dan riwayat kunjungan loyalitas Anda.</li>
        </ul>
        <ul class="space-y-3 list-disc pl-5">
          <li><strong>Nomor Internasional</strong>: Kami mendukung pendaftaran menggunakan nomor luar negeri dengan kode negara masing-masing (Indonesia menggunakan default +62).</li>
          <li><strong>Pembatalan Sesi</strong>: Anda dapat melakukan pembatalan mandiri melalui menu cek tiket sebelum waktu booking dimulai agar slot Anda bisa digunakan orang lain.</li>
          <li><strong>Reward & Loyalitas</strong>: Setiap transaksi sukses akan otomatis tercatat untuk meningkatkan membership tier Anda (Regular, Silver, Gold, VIP) untuk diskon mendatang.</li>
        </ul>
      </div>
    </section>

    <!-- Reviews and Feedback Form (Terhubung dengan Database Pelanggan) -->
    <section class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
      <!-- Left: Form -->
      <div class="lg:col-span-2 bg-white border border-gray-150 rounded-3xl p-6 sm:p-8 space-y-6">
        <div class="space-y-2">
          <span class="text-[9px] uppercase tracking-widest font-heading font-black text-brand-vinto bg-brand-vinto/10 border border-brand-vinto/20 px-3 py-1.5 rounded-full inline-block">
            Ulasan Terverifikasi
          </span>
          <h3 class="text-xl font-heading font-black text-brand-tinta uppercase tracking-wide">
            Berikan Ulasan & Feedback
          </h3>
          <p class="text-xs text-gray-500 font-body">
            Ulasan Anda hanya akan tersimpan jika Email atau nomor WhatsApp Anda telah terdaftar dalam sistem kami.
          </p>
        </div>

        <form @submit.prevent="submitReview" class="space-y-4 font-body text-xs text-gray-500">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-1.5">
              <label class="block text-[10px] uppercase font-heading font-bold text-gray-400">Email / No WhatsApp Terdaftar</label>
              <input 
                v-model="reviewForm.identifier" 
                type="text" 
                placeholder="misal: 62858xxx atau email@domain.com"
                class="w-full px-4 py-3 border border-gray-150 rounded-xl text-xs text-brand-tinta focus:outline-none focus:ring-2 focus:ring-brand-vinto bg-white"
                required
              />
            </div>
            
            <div class="space-y-1.5">
              <label class="block text-[10px] uppercase font-heading font-bold text-gray-400">Stylist Pemotong (Opsional)</label>
              <select 
                v-model="reviewForm.stylist_id"
                class="w-full px-4 py-3 border border-gray-150 rounded-xl text-xs text-brand-tinta focus:outline-none focus:ring-2 focus:ring-brand-vinto bg-white cursor-pointer"
              >
                <option :value="null">Umum (Tidak Ada Stylist Khusus)</option>
                <option v-for="sty in stylists" :key="sty.id" :value="sty.id">
                  {{ sty.name }} ({{ sty.outlet?.name }})
                </option>
              </select>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="block text-[10px] uppercase font-heading font-bold text-gray-400">Rating Bintang</label>
            <div class="flex items-center gap-1.5 text-2xl text-gray-250">
              <button 
                v-for="star in 5" 
                :key="star"
                type="button"
                @click="reviewForm.rating = star"
                class="hover:scale-110 transition cursor-pointer"
                :class="[star <= reviewForm.rating ? 'text-yellow-500' : 'text-gray-200']"
              >
                ★
              </button>
            </div>
          </div>

          <div class="space-y-1.5">
            <label class="block text-[10px] uppercase font-heading font-bold text-gray-400">Komentar / Pengalaman</label>
            <textarea 
              v-model="reviewForm.comment"
              rows="3"
              placeholder="Tulis ulasan jujur lo di sini..."
              class="w-full px-4 py-3 border border-gray-150 rounded-xl text-xs text-brand-tinta focus:outline-none focus:ring-2 focus:ring-brand-vinto bg-white"
            ></textarea>
          </div>

          <div class="flex items-center justify-between gap-4 pt-2">
            <p v-if="reviewStatus.message" :class="[reviewStatus.success ? 'text-green-600' : 'text-red-500']" class="text-xs font-bold leading-normal">
              {{ reviewStatus.message }}
            </p>
            <span v-else></span>
            
            <BaseButton type="submit" variant="primary" size="sm" :loading="submittingReview">
              Kirim Ulasan
            </BaseButton>
          </div>
        </form>
      </div>

      <!-- Right: Latest Reviews List -->
      <div class="bg-brand-natural border border-gray-150 rounded-3xl p-6 space-y-6">
        <h4 class="text-xs font-heading font-black text-brand-tinta uppercase tracking-widest">Review Terbaru</h4>
        
        <div v-if="publicReviews.length === 0" class="text-xs text-gray-400 font-body italic py-4">
          Belum ada ulasan terkirim. Jadilah yang pertama memberikan feedback!
        </div>

        <div v-else class="space-y-4">
          <div 
            v-for="rev in publicReviews" 
            :key="rev.id" 
            class="bg-white p-4 rounded-2xl border border-gray-100 shadow-2xs space-y-2"
          >
            <div class="flex items-center justify-between gap-2">
              <div class="flex items-center gap-1 text-xs text-yellow-500">
                <span v-for="s in rev.rating" :key="s">★</span>
              </div>
              <span class="text-[9px] text-gray-400 font-body">{{ new Date(rev.created_at).toLocaleDateString('id-ID') }}</span>
            </div>
            
            <p class="text-xs font-body text-gray-600 italic">"{{ rev.comment || 'Ulasan bintang ' + rev.rating }}"</p>
            
            <div class="flex justify-between items-center text-[10px] pt-1.5 border-t border-gray-50 text-gray-400 font-body">
              <span>Oleh: {{ maskName(rev.customer?.full_name || 'Guest') }}</span>
              <span v-if="rev.stylist" class="text-brand-vinto font-bold uppercase tracking-wider text-[9px]">Stylist: {{ rev.stylist.name }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useServiceStore } from '../stores/service'
import { useBookingStore } from '../stores/booking'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseLoader from '../Components/ui/BaseLoader.vue'
import { formatPrice } from '../utils/formatter'

const serviceStore = useServiceStore()
const bookingStore = useBookingStore()
const router = useRouter()

const loading = ref(true)
const popularServices = ref([])
const outlets = ref([])
const stylists = ref([])

const stylistsByOutlet = computed(() => {
  const groups = {}
  outlets.value.forEach(o => {
    groups[o.id] = {
      outlet: o,
      stylists: []
    }
  })
  
  stylists.value.forEach(s => {
    const outletId = s.outlet_id
    if (groups[outletId]) {
      groups[outletId].stylists.push(s)
    }
  })
  
  return Object.values(groups)
})

onMounted(async () => {
  // Capture URL parameters for booking acquisition tracking (e.g. ?ref=ig or ?utm_source=tiktok)
  const urlParams = new URLSearchParams(window.location.search)
  const refSource = urlParams.get('ref') || urlParams.get('utm_source') || urlParams.get('source')
  if (refSource) {
    localStorage.setItem('booking_acquisition_source', refSource)
  }

  try {
    await serviceStore.fetchServices()
    popularServices.value = serviceStore.services.slice(0, 3)
    
    const api = (await import('../utils/api')).default
    const resOutlets = await api.get('/v1/outlets')
    outlets.value = resOutlets.data || []
    
    const resStylists = await api.get('/v1/stylists')
    stylists.value = resStylists.data || []

    const resReviews = await api.get('/v1/reviews')
    publicReviews.value = resReviews.data || []
  } catch (err) {
    console.error('Failed to load landing page data:', err)
  } finally {
    loading.value = false
  }
})

function bookService(service) {
  bookingStore.reset()
  bookingStore.bookingData.service_ids.push(service.id)
  bookingStore.selectedServices.push(service)
  bookingStore.currentStep = 1
  router.push('/booking')
}

const testimonials = [
  {
    name: 'Andra Ramadhan',
    role: 'Tamu Tetap, GI Mall',
    initials: 'AR',
    content: 'Vibe check & kopi premium-nya gokil banget! Beneran nemu model rambut yang presisi sama bentuk muka gua sambil ngopi santai. Stylist gokil parah.'
  },
  {
    name: 'Budi Santoso',
    role: 'Creative Director',
    initials: 'BS',
    content: 'Grooming standard internasional yang konsisten parah. Stylist-nya teliti banget, service premium no debat!'
  },
  {
    name: 'Kevin Wijaya',
    role: 'Executive Broker',
    initials: 'KW',
    content: 'Sistem booking-nya sat-set banget. Tinggal scan QR pas dateng, ga pake antre lama langsung dieksekusi stylist.'
  }
]

const publicReviews = ref([])
const submittingReview = ref(false)
const reviewForm = ref({
  identifier: '',
  stylist_id: null,
  rating: 5,
  comment: ''
})
const reviewStatus = ref({
  success: false,
  message: ''
})

async function fetchPublicReviews() {
  try {
    const api = (await import('../utils/api')).default
    const response = await api.get('/v1/reviews')
    publicReviews.value = response.data || []
  } catch (err) {
    console.error('Failed to load reviews list:', err)
  }
}

async function submitReview() {
  submittingReview.value = true
  reviewStatus.value.message = ''
  try {
    const api = (await import('../utils/api')).default
    const response = await api.post('/v1/reviews', reviewForm.value)
    
    reviewStatus.value = {
      success: true,
      message: response.data.message
    }
    
    reviewForm.value.comment = ''
    reviewForm.value.identifier = ''
    reviewForm.value.rating = 5
    
    await fetchPublicReviews()
    
    const resStylists = await api.get('/v1/stylists')
    stylists.value = resStylists.data || []
  } catch (err) {
    reviewStatus.value = {
      success: false,
      message: err.response?.data?.message || 'Gagal mengirimkan ulasan.'
    }
  } finally {
    submittingReview.value = false
  }
}

function maskName(name) {
  if (!name) return 'Guest'
  const parts = name.split(' ')
  return parts.map(p => p[0] + '*'.repeat(Math.max(0, p.length - 1))).join(' ')
}
</script>
