<template>
  <div :class="[bookingStore.currentStep === 2 ? 'max-w-5xl' : 'max-w-3xl', 'mx-auto py-6 px-4 transition-all duration-300']">
    <!-- Progress Stepper (Hidden on Success screen / Step 7) -->
    <div v-if="bookingStore.currentStep <= 6" class="mb-8 px-2">
      <div class="flex items-center justify-between relative">
        <!-- Connecting lines -->
        <div class="absolute left-0 right-0 top-1/2 -translate-y-1/2 h-0.5 bg-gray-200 -z-10 rounded-full">
          <div 
            class="h-full bg-brand-vinto transition-all duration-300 rounded-full" 
            :style="{ width: ((bookingStore.currentStep - 1) / 5) * 100 + '%' }"
          ></div>
        </div>

        <!-- Step indicators -->
        <div 
          v-for="step in bookingStore.steps" 
          :key="step.id" 
          class="flex flex-col items-center gap-1.5"
        >
          <div 
            class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-heading font-bold transition-all duration-300 border shadow-xs"
            :class="[
              bookingStore.currentStep === step.id
                ? 'bg-brand-vinto text-white border-brand-vinto scale-110 ring-4 ring-brand-vinto/20'
                : bookingStore.currentStep > step.id
                  ? 'bg-brand-pudar text-white border-brand-pudar'
                  : 'bg-white text-gray-400 border-gray-200'
            ]"
          >
            <span v-if="bookingStore.currentStep > step.id">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
              </svg>
            </span>
            <span v-else>{{ step.id }}</span>
          </div>
          <span 
            class="text-[10px] sm:text-xs font-heading font-semibold hidden md:block"
            :class="bookingStore.currentStep === step.id ? 'text-brand-vinto' : 'text-gray-400'"
          >
            {{ step.name }}
          </span>
        </div>
      </div>
    </div>

    <!-- Multi-step Form Content -->
    <BaseCard padding="lg" class="shadow-sm bg-[#FAF8F5]/80 backdrop-blur-md border border-gray-100">
      
      <!-- ================= STEP 1: PILIH OUTLET ================= -->
      <div v-if="bookingStore.currentStep === 1" class="space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-heading font-bold text-brand-tinta uppercase tracking-wider">Pilih Outlet Barber</h2>
          <p class="text-xs text-gray-500 font-body">Silakan pilih outlet terdekat untuk sesi grooming Anda.</p>
        </div>

        <div class="flex justify-end mb-2">
          <BaseButton @click="detectNearestOutlet" variant="ghost" size="sm" :loading="geoLoading" class="border-gray-200 text-brand-tinta font-heading text-xs font-bold uppercase tracking-wider">
            Deteksi Lokasi Terdekat
          </BaseButton>
        </div>

        <div v-if="outletsLoading" class="space-y-3">
          <BaseLoader skeleton height="80px" v-for="i in 3" :key="i" />
        </div>
        <div v-else class="space-y-3">
          <div
            v-for="outlet in outletStore.outlets"
            :key="outlet.id"
            @click="selectOutlet(outlet)"
            class="flex items-center gap-4 p-4 border rounded-2xl active:scale-[0.98] transition-all duration-200 cursor-pointer select-none"
            :class="[
              bookingStore.bookingData.outlet_id === outlet.id
                ? 'border-brand-vinto bg-white shadow-md'
                : 'border-gray-200/60 bg-white/50 hover:border-brand-vinto'
            ]"
          >
            <img :src="outlet.image_url" class="w-16 h-16 rounded-xl object-cover" />
            <div class="flex-1 min-w-0">
              <h4 class="font-heading font-semibold text-brand-tinta truncate">{{ outlet.name }}</h4>
              <p class="text-xs text-gray-400 truncate mt-0.5 font-body">{{ outlet.address }}</p>
              <div class="flex items-center gap-2 mt-1">
                <span class="text-xs text-brand-vinto font-semibold">Rating: {{ outlet.rating }}</span>
                <span v-if="outlet.distance" class="text-[10px] font-heading font-bold text-brand-vinto bg-brand-vinto/10 px-2 py-0.5 rounded-full">
                  {{ outlet.distance.toFixed(1) }} km
                </span>
              </div>
            </div>
            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
              :class="bookingStore.bookingData.outlet_id === outlet.id ? 'border-brand-vinto bg-brand-vinto text-white' : 'border-gray-300'"
            >
              <span v-if="bookingStore.bookingData.outlet_id === outlet.id">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <div class="flex justify-end pt-4">
          <BaseButton @click="goToStep(2)" :disabled="!bookingStore.bookingData.outlet_id" variant="primary">
            Lanjut
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 2: PILIH LAYANAN ================= -->
      <div v-if="bookingStore.currentStep === 2" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start text-left">
          
          <!-- Left Column: Services Catalog List (Grouped by Category) -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Back navigation button -->
            <button 
              @click="goToStep(1)" 
              type="button" 
              class="flex items-center gap-1 text-sm font-heading font-bold text-gray-400 hover:text-brand-vinto mb-2 cursor-pointer select-none"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
              </svg>
              <span>Select a service</span>
            </button>

            <div class="space-y-1">
              <h2 class="text-2xl font-heading font-black text-brand-tinta uppercase tracking-wider">Services</h2>
            </div>

            <div v-if="servicesLoading" class="space-y-4">
              <BaseLoader skeleton height="80px" v-for="i in 3" :key="i" />
            </div>
            
            <div v-else class="space-y-6 max-h-[500px] overflow-y-auto pr-2">
              <div 
                v-for="category in groupedServices" 
                :key="category.id" 
                class="space-y-3"
              >
                <!-- Category Collapsible Header -->
                <div 
                  @click="toggleCategory(category.slug)" 
                  class="flex items-center justify-between py-3 border-b border-gray-150 cursor-pointer select-none"
                >
                  <span class="text-xs font-heading font-black text-gray-400 uppercase tracking-widest">{{ category.name }}</span>
                  <span class="text-gray-400">
                    <svg 
                      class="w-4 h-4 transition-transform duration-200" 
                      :class="[collapsedCategories[category.slug] ? '' : 'rotate-180']" 
                      fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                    </svg>
                  </span>
                </div>

                <!-- Services List in this Category -->
                <div v-if="!collapsedCategories[category.slug]" class="space-y-3">
                  <div
                    v-for="service in category.services"
                    :key="service.id"
                    class="p-5 border rounded-2xl transition-all duration-200 bg-white"
                    :class="[
                      bookingStore.bookingData.service_ids.includes(service.id)
                        ? 'border-brand-vinto ring-1 ring-brand-vinto/25 shadow-sm'
                        : 'border-gray-150 hover:border-brand-vinto hover:bg-neutral-light/5'
                    ]"
                  >
                    <div @click="toggleService(service)" class="flex items-start justify-between cursor-pointer">
                      <div class="space-y-1">
                        <h4 class="font-heading font-extrabold text-brand-tinta text-base leading-tight">{{ service.name }}</h4>
                        <!-- Meta row: Duration, details toggle, price -->
                        <div class="flex items-center gap-1.5 text-xs text-gray-500 font-body">
                          <span>{{ formatDuration(service.duration) }}</span>
                          <span>•</span>
                          <button 
                            @click.stop="toggleDetails(service.id)" 
                            type="button" 
                            class="text-brand-vinto font-semibold hover:underline cursor-pointer"
                          >
                            {{ expandedDetails[service.id] ? 'Hide details' : 'Details' }}
                          </button>
                          <span>•</span>
                          <span class="font-bold text-brand-tinta">{{ formatPrice(service.price) }}</span>
                        </div>
                      </div>
                      
                      <!-- Selection Chevron Icon -->
                      <div class="text-gray-300 mt-1">
                        <svg class="w-5 h-5 transition-transform duration-200" 
                          :class="[bookingStore.bookingData.service_ids.includes(service.id) ? 'text-brand-vinto translate-x-1' : '']"
                          fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        >
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7-7" />
                        </svg>
                      </div>
                    </div>

                    <!-- Expanded Details / Description block -->
                    <div 
                      v-if="expandedDetails[service.id]" 
                      class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-600 font-body leading-relaxed space-y-3"
                    >
                      <p>{{ service.description }}</p>
                      <div v-if="getServiceIncludes(service.name)" class="space-y-2">
                        <p class="font-heading font-bold text-brand-tinta text-[10px] uppercase tracking-wider">Service ini sudah termasuk:</p>
                        <ul class="space-y-1 pl-2 text-gray-500">
                          <li v-for="inc in getServiceIncludes(service.name)" :key="inc">• {{ inc }}</li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right Column: More Hair Studio Summary Card -->
          <div class="space-y-4">
            <div class="bg-white border border-gray-150 rounded-3xl p-6 shadow-sm flex flex-col items-center text-center">
              <!-- Logo block -->
              <div class="mb-4">
                <img src="/images/logo.png" class="h-10 w-auto object-contain mx-auto" alt="MORE Logo" />
              </div>
              
              <h3 class="font-heading font-black text-brand-tinta text-lg">More Hair Studio</h3>
              
              <!-- Rating -->
              <div class="flex items-center justify-center gap-1 mt-1 text-sm font-heading font-bold text-brand-tinta">
                <span>5.0</span>
                <div class="flex text-yellow-500">
                  <svg v-for="i in 5" :key="i" class="w-3.5 h-3.5 fill-current" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <span class="text-xs text-gray-400 font-body font-medium">229 reviews</span>
              </div>

              <p class="text-xs text-gray-400 font-body mt-2 leading-relaxed">
                {{ bookingStore.selectedOutlet?.address || 'Jl. Mangga 37A, Cihapit Bandung, Jawa Barat 40114' }}
              </p>
            </div>

            <!-- Running Subtotal Card -->
            <div class="bg-white/90 p-5 rounded-3xl border border-gray-150 shadow-sm space-y-4">
              <h4 class="font-heading font-black text-brand-tinta text-xs uppercase tracking-wider">Ringkasan Pemesanan</h4>
              <div class="space-y-2 text-xs font-body text-gray-500">
                <div class="flex justify-between">
                  <span>Selected Services:</span>
                  <span class="font-semibold text-brand-tinta">{{ bookingStore.bookingData.service_ids.length }}</span>
                </div>
                <div class="flex justify-between">
                  <span>Est. Duration:</span>
                  <span class="font-semibold text-brand-tinta">{{ bookingStore.totalDuration }} menit</span>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-2 text-sm">
                  <span class="font-bold text-brand-tinta">Total Price:</span>
                  <span class="font-heading font-black text-brand-vinto">{{ formatPrice(bookingStore.totalPrice) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="flex justify-between pt-6 border-t border-gray-100">
          <BaseButton @click="goToStep(1)" variant="ghost">Kembali</BaseButton>
          <BaseButton @click="goToStep(3)" :disabled="bookingStore.bookingData.service_ids.length === 0" variant="primary" class="px-8 shadow-sm">
            Lanjut
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 3: PILIH STYLIST ================= -->
      <div v-if="bookingStore.currentStep === 3" class="space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-heading font-bold text-brand-tinta uppercase tracking-wider">Pilih Hair Stylist</h2>
          <p class="text-xs text-gray-500 font-body">Pilih stylist profesional kami yang cocok dengan kebutuhan styling Anda.</p>
        </div>

        <div v-if="stylistsLoading" class="space-y-3">
          <BaseLoader skeleton height="80px" v-for="i in 3" :key="i" />
        </div>
        <div v-else class="space-y-3">
          <div
            v-for="stylist in stylistStore.stylists"
            :key="stylist.id"
            @click="selectStylist(stylist)"
            class="flex items-center gap-4 p-4 border rounded-2xl active:scale-[0.98] transition-all duration-200 cursor-pointer select-none"
            :class="[
              bookingStore.bookingData.stylist_id === stylist.id
                ? 'border-brand-vinto bg-white shadow-md'
                : 'border-gray-200/60 bg-white/50 hover:border-brand-vinto'
            ]"
          >
            <img :src="stylist.avatar_url" class="w-12 h-12 rounded-full object-cover" />
            <div class="flex-1 min-w-0">
              <h4 class="font-heading font-semibold text-brand-tinta truncate">{{ stylist.name }}</h4>
              <p class="text-xs text-gray-400 truncate font-body">{{ stylist.specialty }}</p>
              <div class="flex items-center gap-2 mt-1 text-xs text-gray-500">
                <span class="text-brand-vinto">Rating: {{ stylist.rating }}</span>
                <span>•</span>
                <span>{{ stylist.experience_years }} thn pengalaman</span>
              </div>
            </div>
            <div class="w-5 h-5 rounded-full border flex items-center justify-center"
              :class="bookingStore.bookingData.stylist_id === stylist.id ? 'border-brand-vinto bg-brand-vinto text-white' : 'border-gray-300'"
            >
              <span v-if="bookingStore.bookingData.stylist_id === stylist.id">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
              </span>
            </div>
          </div>
        </div>

        <div class="flex justify-between pt-4">
          <BaseButton @click="goToStep(2)" variant="ghost">Kembali</BaseButton>
          <BaseButton @click="goToStep(4)" :disabled="!bookingStore.bookingData.stylist_id" variant="primary">
            Lanjut
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 4: PILIH TANGGAL & JAM ================= -->
      <div v-if="bookingStore.currentStep === 4" class="space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-heading font-bold text-brand-tinta uppercase tracking-wider">Pilih Tanggal & Waktu</h2>
          <p class="text-xs text-gray-500 font-body">Tentukan kapan Anda ingin melakukan sesi grooming.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-xs font-heading font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih Tanggal</label>
            <DatePicker 
              v-model="bookingStore.bookingData.booking_date" 
              @update:modelValue="onDateSelected" 
              :booked-dates="bookedDates"
            />
          </div>

          <div>
            <label class="block text-xs font-heading font-bold text-gray-400 uppercase tracking-widest mb-2">Slot Jam Tersedia</label>
            <TimePicker 
              v-model="bookingStore.bookingData.booking_time" 
              :slots="stylistStore.slots" 
              :loading="slotsLoading" 
            />
          </div>
        </div>

        <div class="flex justify-between pt-4">
          <BaseButton @click="goToStep(3)" variant="ghost">Kembali</BaseButton>
          <BaseButton @click="goToStep(5)" :disabled="!bookingStore.bookingData.booking_date || !bookingStore.bookingData.booking_time" variant="primary">
            Lanjut
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 5: DATA DIRI CUSTOMER ================= -->
      <div v-if="bookingStore.currentStep === 5" class="space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-heading font-bold text-brand-tinta uppercase tracking-wider">Lengkapi Data Diri</h2>
          <p class="text-xs text-gray-500 font-body">Masukkan nomor WhatsApp Anda untuk konfirmasi pemesanan dan poin loyalitas.</p>
        </div>

        <!-- Loyalty recognition card -->
        <div v-if="existingCustomer" class="p-4 bg-brand-vinto/5 border border-brand-vinto/10 rounded-2xl flex items-center justify-between">
          <div>
            <h4 class="font-heading font-bold text-brand-tinta">Selamat Datang Kembali!</h4>
            <p class="text-xs text-gray-500 font-body mt-0.5">
              Halo {{ bookingStore.bookingData.customer.full_name }}. Tier Keanggotaan: <span class="font-bold text-brand-vinto capitalize">{{ existingCustomer.membership_status }}</span>
            </p>
            <p class="text-xs text-gray-400 mt-1 font-body">Loyalty Point: {{ existingCustomer.loyalty_points }} poin • Total Kunjungan: {{ existingCustomer.total_visits }}</p>
          </div>
          <div class="w-8 h-8 rounded-full bg-brand-vinto/10 flex items-center justify-center text-brand-vinto">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd" />
            </svg>
          </div>
        </div>

        <!-- Full Form fields -->
        <div class="space-y-4">
          <PhoneInput v-model="bookingStore.bookingData.customer.whatsapp" label="Nomor WhatsApp / Telepon" required />
          <BaseInput v-model="bookingStore.bookingData.customer.full_name" label="Nama Lengkap" :disabled="!!existingCustomer" required />
          <BaseInput v-model="bookingStore.bookingData.customer.email" label="Email Address" type="email" :disabled="!!existingCustomer" />
          <BaseInput v-model="bookingStore.bookingData.customer.date_of_birth" label="Tanggal Lahir (Untuk Promo Ultah)" type="date" :disabled="!!existingCustomer" />
          <BaseSelect v-model="bookingStore.bookingData.customer.gender" label="Jenis Kelamin" :disabled="!!existingCustomer">
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
          </BaseSelect>
        </div>

        <!-- Birthday Promo Announcement -->
        <div v-if="isBirthdayToday" class="p-4 bg-brand-vinto/10 border border-brand-vinto/25 rounded-2xl flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-brand-vinto/20 text-brand-vinto flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7.463 8.24c.797.15 1.54.498 2.2 1.01a6.002 6.002 0 008.526 0 6.002 6.002 0 008.526 0 6.002 6.002 0 002.2-1.01M3 9a2 2 0 012-2h14a2 2 0 012 2v11a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
            </svg>
          </div>
          <div>
            <h4 class="font-heading font-bold text-brand-tinta uppercase tracking-wider text-xs">Promo Ulang Tahun Aktif!</h4>
            <p class="text-[11px] text-gray-500 font-body mt-0.5">Diskon 20% otomatis digunakan pada total pemesanan Anda.</p>
          </div>
        </div>

        <div class="flex justify-between pt-4">
          <BaseButton @click="goToStep(4)" variant="ghost">Kembali</BaseButton>
          <BaseButton @click="goToStep(6)" :disabled="!bookingStore.bookingData.customer.full_name || !bookingStore.bookingData.customer.whatsapp" variant="primary">
            Lanjut
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 6: KONFIRMASI PEMESANAN ================= -->
      <div v-if="bookingStore.currentStep === 6" class="space-y-6">
        <div class="space-y-1">
          <h2 class="text-xl font-heading font-bold text-brand-tinta uppercase tracking-wider">Konfirmasi Pemesanan</h2>
          <p class="text-xs text-gray-500 font-body">Periksa kembali ringkasan pemesanan Anda sebelum melakukan konfirmasi.</p>
        </div>

        <!-- Detailed Summary Grid -->
        <div class="space-y-3">
          <div class="flex justify-between border-b border-gray-100 pb-2 text-sm">
            <span class="text-gray-400 font-body">Outlet</span>
            <span class="font-heading font-bold text-brand-tinta">{{ bookingStore.selectedOutlet?.name }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2 text-sm">
            <span class="text-gray-400 font-body">Stylist</span>
            <span class="font-heading font-bold text-brand-tinta">{{ bookingStore.selectedStylist?.name }}</span>
          </div>
          <div class="flex justify-between border-b border-gray-100 pb-2 text-sm">
            <span class="text-gray-400 font-body">Waktu</span>
            <span class="font-heading font-bold text-brand-tinta">
              {{ formatDate(bookingStore.bookingData.booking_date) }} @ {{ bookingStore.bookingData.booking_time }}
            </span>
          </div>
          <div class="border-b border-gray-100 pb-2 text-sm space-y-1.5">
            <span class="text-gray-400 font-body block">Layanan Pilihan</span>
            <div 
              v-for="service in bookingStore.selectedServices" 
              :key="service.id" 
              class="flex justify-between font-heading font-semibold pl-2"
            >
              <span>• {{ service.name }}</span>
              <span>{{ formatPrice(service.price) }}</span>
            </div>
          </div>
        </div>

        <!-- Promo Code Apply -->
        <div class="flex items-end gap-3">
          <div class="flex-1">
            <BaseInput v-model="promoInput" label="Masukkan Kode Promo" :error="promoError" />
          </div>
          <BaseButton @click="applyPromo" variant="ghost" :loading="promoLoading" class="h-12 border-gray-200">
            Gunakan
          </BaseButton>
        </div>
        <div v-if="promoSuccess" class="text-xs text-green-600 pl-1 font-body">
          {{ promoSuccess }}
        </div>

        <!-- Pricing details breakdown -->
        <div class="bg-white/60 p-6 rounded-2xl space-y-3 border border-gray-100">
          <div class="flex justify-between text-sm text-gray-500 font-body">
            <span>Subtotal</span>
            <span>{{ formatPrice(bookingStore.totalPrice) }}</span>
          </div>
          <div v-if="bookingStore.birthdayDiscount > 0" class="flex justify-between text-sm text-brand-vinto font-body">
            <span>Diskon Ulang Tahun (20%)</span>
            <span>-{{ formatPrice(bookingStore.birthdayDiscount) }}</span>
          </div>
          <div v-if="bookingStore.promoDiscount > 0" class="flex justify-between text-sm text-brand-vinto font-body">
            <span>Diskon Promo ({{ bookingStore.activePromo?.name }})</span>
            <span>-{{ formatPrice(bookingStore.promoDiscount) }}</span>
          </div>
          <div class="flex justify-between text-base font-heading font-black text-brand-tinta border-t border-gray-200 pt-3">
            <span>Total Pembayaran</span>
            <span class="text-xl text-brand-vinto">{{ formatPrice(bookingStore.finalPrice) }}</span>
          </div>
        </div>

        <!-- Add Notes -->
        <div>
          <label class="block text-xs font-heading font-bold text-gray-400 uppercase tracking-widest mb-2">Catatan Tambahan (Opsional)</label>
          <textarea
            v-model="bookingStore.bookingData.notes"
            rows="3"
            placeholder="Ada request khusus (misal: potongan model tertentu, dsb)?"
            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-base text-brand-tinta font-body focus:outline-none focus:ring-2 focus:ring-brand-vinto bg-white/50"
          ></textarea>
        </div>

        <div class="flex justify-between pt-4">
          <BaseButton @click="goToStep(5)" variant="ghost">Kembali</BaseButton>
          <BaseButton @click="submitBooking" variant="secondary" size="lg" :loading="submitLoading" class="px-8 shadow-md">
            Konfirmasi Booking
          </BaseButton>
        </div>
      </div>

      <!-- ================= STEP 7: SUCCESS BOOKING SCREEN ================= -->
      <div v-if="bookingStore.currentStep === 7" class="space-y-8 py-4 text-center">
        <div class="flex flex-col items-center no-print">
          <div class="w-16 h-16 bg-brand-vinto/15 text-brand-vinto rounded-full flex items-center justify-center mb-4">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
          </div>
          <h2 class="text-2xl font-heading font-black text-brand-tinta">Booking Berhasil</h2>
          <p class="text-sm text-gray-500 font-body max-w-sm mt-1">
            Kode booking Anda telah dibuat. Silakan tunjukkan kode QR ini ke resepsionis untuk melakukan check-in saat kedatangan.
          </p>
        </div>

        <!-- Checkin Card with QR code details -->
        <div id="print-ticket" class="max-w-xs mx-auto bg-white border border-gray-150 rounded-3xl p-6 shadow-md space-y-4">
          <div class="bg-brand-natural/30 py-2 rounded-xl text-center">
            <span class="text-[9px] text-gray-400 font-heading font-bold tracking-wider block">KODE BOOKING</span>
            <h3 class="text-xl font-heading font-black text-brand-vinto tracking-widest mt-0.5">
              {{ successDetails?.booking_code }}
            </h3>
          </div>

          <!-- Dynamic QR code generator simulation -->
          <div class="flex justify-center py-2">
            <div class="bg-neutral-light/20 p-4 rounded-2xl border border-gray-200">
              <img 
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + successDetails?.booking_code" 
                class="w-40 h-40 object-contain mx-auto" 
                alt="Booking QR Code" 
              />
            </div>
          </div>

          <div class="text-left space-y-2 text-xs font-body border-t border-gray-100 pt-4">
            <div class="flex justify-between">
              <span class="text-gray-400">Nama</span>
              <span class="font-semibold text-brand-tinta">{{ bookingStore.bookingData.customer.full_name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Outlet</span>
              <span class="font-semibold text-brand-tinta">{{ bookingStore.selectedOutlet?.name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Tanggal</span>
              <span class="font-semibold text-brand-tinta">{{ formatShortDate(bookingStore.bookingData.booking_date) }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-400">Waktu</span>
              <span class="font-semibold text-brand-tinta">{{ bookingStore.bookingData.booking_time }} WIB</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center max-w-sm mx-auto no-print">
          <BaseButton @click="printTicket" variant="ghost" block class="border-gray-200 text-brand-tinta flex items-center justify-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Cetak & Simpan PDF</span>
          </BaseButton>
          <BaseButton @click="finishBookingFlow" variant="primary" block>
            Kembali ke Beranda
          </BaseButton>
        </div>
      </div>

    </BaseCard>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useOutletStore } from '../stores/outlet'
import { useServiceStore } from '../stores/service'
import { useStylistStore } from '../stores/stylist'
import { useBookingStore } from '../stores/booking'
import { useCustomerStore } from '../stores/customer'
import BaseButton from '../Components/ui/BaseButton.vue'
import BaseCard from '../Components/ui/BaseCard.vue'
import BaseLoader from '../Components/ui/BaseLoader.vue'
import DatePicker from '../Components/ui/DatePicker.vue'
import TimePicker from '../Components/ui/TimePicker.vue'
import PhoneInput from '../Components/ui/PhoneInput.vue'
import BaseInput from '../Components/ui/BaseInput.vue'
import BaseSelect from '../Components/ui/BaseSelect.vue'
import Tabs from '../Components/ui/Tabs.vue'
import { formatPrice } from '../utils/formatter'
import { formatDate, formatShortDate } from '../utils/dateHelper'
import api from '../utils/api'

const outletStore = useOutletStore()
const serviceStore = useServiceStore()
const stylistStore = useStylistStore()
const bookingStore = useBookingStore()
const customerStore = useCustomerStore()
const router = useRouter()

// UI Loading states
const outletsLoading = ref(false)
const servicesLoading = ref(false)
const stylistsLoading = ref(false)
const slotsLoading = ref(false)
const geoLoading = ref(false)
const lookupLoading = ref(false)
const promoLoading = ref(false)
const submitLoading = ref(false)

// Customer Lookup
const whatsappLookup = ref('')
const lookupError = ref('')
const existingCustomer = ref(null)

// Promo Apply
const promoInput = ref('')
const promoError = ref('')
const promoSuccess = ref('')

// Booking success details
const successDetails = ref(null)
const bookedDates = ref([])

onMounted(async () => {
  if (bookingStore.currentStep === 1) {
    outletsLoading.value = true
    await outletStore.fetchOutlets()
    outletsLoading.value = false
  }
})

// Step switching logic
async function goToStep(step) {
  bookingStore.currentStep = step
  
  if (step === 1 && outletStore.outlets.length === 0) {
    outletsLoading.value = true
    await outletStore.fetchOutlets()
    outletsLoading.value = false
  }
  
  if (step === 2 && serviceStore.services.length === 0) {
    servicesLoading.value = true
    await serviceStore.fetchServices()
    servicesLoading.value = false
  }
  
  if (step === 3) {
    stylistsLoading.value = true
    await stylistStore.fetchStylists(bookingStore.bookingData.outlet_id)
    stylistsLoading.value = false
  }
}

// Outlet select wrapper
function selectOutlet(outlet) {
  bookingStore.bookingData.outlet_id = outlet.id
  bookingStore.selectedOutlet = outlet
}

// Geolocation trigger
function detectNearestOutlet() {
  geoLoading.value = true
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition((pos) => {
      outletStore.sortOutletsByDistance(pos.coords.latitude, pos.coords.longitude)
      geoLoading.value = false
    }, () => {
      geoLoading.value = false
      alert('Gagal mendeteksi lokasi.')
    })
  } else {
    geoLoading.value = false
  }
}

// Category tabs builder
const categoryTabs = computed(() => {
  const defaultTab = [{ label: 'Semua', value: 'all' }]
  const mapped = serviceStore.categories.map(cat => ({
    label: cat.name,
    value: cat.slug
  }))
  return [...defaultTab, ...mapped]
})

// Add/Remove services from list
function toggleService(service) {
  const index = bookingStore.bookingData.service_ids.indexOf(service.id)
  if (index === -1) {
    bookingStore.bookingData.service_ids.push(service.id)
    bookingStore.selectedServices.push(service)
  } else {
    bookingStore.bookingData.service_ids.splice(index, 1)
    bookingStore.selectedServices = bookingStore.selectedServices.filter(s => s.id !== service.id)
  }
}

// Stylist select wrapper
async function selectStylist(stylist) {
  bookingStore.bookingData.stylist_id = stylist.id
  bookingStore.selectedStylist = stylist
  // Reset date/time selection since stylist changed
  bookingStore.bookingData.booking_date = null
  bookingStore.bookingData.booking_time = null

  // Fetch busy dates for this stylist
  bookedDates.value = []
  try {
    const response = await api.get(`/v1/stylists/${stylist.id}/booked-dates`)
    bookedDates.value = response.data || []
  } catch (err) {
    console.error('Error fetching booked dates:', err)
  }
}

// Slots trigger on date chosen
async function onDateSelected(date) {
  if (!date) return
  slotsLoading.value = true
  await stylistStore.fetchAvailableSlots(bookingStore.bookingData.stylist_id, date)
  slotsLoading.value = false
}

// Customer account check watcher (Automatic Lookup)
watch(() => bookingStore.bookingData.customer.whatsapp, async (newVal) => {
  if (newVal && newVal.length >= 10) {
    lookupLoading.value = true
    try {
      const res = await customerStore.checkCustomerByWhatsapp(newVal)
      if (res.exists) {
        existingCustomer.value = res.customer
        bookingStore.bookingData.customer.full_name = res.customer.full_name
        bookingStore.bookingData.customer.phone = res.customer.phone
        bookingStore.bookingData.customer.email = res.customer.email || ''
        bookingStore.bookingData.customer.date_of_birth = res.customer.date_of_birth || ''
        bookingStore.bookingData.customer.gender = res.customer.gender || 'male'
      } else {
        existingCustomer.value = null
        bookingStore.bookingData.customer.phone = newVal
      }
    } catch (err) {
      console.error('Error auto lookup:', err)
    } finally {
      lookupLoading.value = false
    }
  } else {
    existingCustomer.value = null
  }
})

// Birthday promotion check
const isBirthdayToday = computed(() => {
  if (!bookingStore.bookingData.customer.date_of_birth || !bookingStore.bookingData.booking_date) return false
  const dob = new Date(bookingStore.bookingData.customer.date_of_birth)
  const bookDate = new Date(bookingStore.bookingData.booking_date)
  return dob.getMonth() === bookDate.getMonth() && dob.getDate() === bookDate.getDate()
})

// Promo verification
async function applyPromo() {
  if (!promoInput.value) {
    promoError.value = 'Kode promo tidak boleh kosong'
    return
  }
  promoLoading.value = true
  promoError.value = ''
  promoSuccess.value = ''

  const res = await bookingStore.applyPromoCode(promoInput.value)
  promoLoading.value = false
  if (res.success) {
    promoSuccess.value = res.message
  } else {
    promoError.value = res.message
  }
}

// Booking creation execution
async function submitBooking() {
  submitLoading.value = true
  
  // Assemble the customer profile parameters just in case
  if (!bookingStore.bookingData.customer.whatsapp) {
    bookingStore.bookingData.customer.whatsapp = whatsappLookup.value
    bookingStore.bookingData.customer.phone = whatsappLookup.value
  }

  const res = await bookingStore.submitBooking()
  submitLoading.value = false
  if (res.success) {
    successDetails.value = res.data.booking
    bookingStore.currentStep = 7
  } else {
    alert(res.error || 'Terjadi kesalahan saat memproses booking.')
  }
}

// Print/Save PDF handler
function printTicket() {
  window.print()
}

// Service list formatting and collapses
const collapsedCategories = ref({})
const expandedDetails = ref({})

function toggleCategory(slug) {
  collapsedCategories.value[slug] = !collapsedCategories.value[slug]
}

function toggleDetails(id) {
  expandedDetails.value[id] = !expandedDetails.value[id]
}

function formatDuration(minutes) {
  if (!minutes) return '0 min'
  if (minutes >= 60) {
    const hrs = minutes / 60
    return Number.isInteger(hrs) ? `${hrs} hr` : `${hrs.toFixed(1)} hr`
  }
  return `${minutes} mins`
}

function getServiceIncludes(name) {
  if (name.toLowerCase().includes('smoothflow') && name.toLowerCase().includes('haircut')) {
    return [
      'Consultation (menganalisis kondisi rambut dan penyesuaian dengan bentuk wajah, jenis rambut, dan hairstyle akhir)',
      'SmoothFlow (~2 Hours)',
      'Haircut (~1 Hour)',
      'Hair Wash',
      'Blow Dry & Styling',
      'Consultation After Treatment'
    ]
  }
  return null
}

const groupedServices = computed(() => {
  return serviceStore.categories.map(category => {
    const categoryServices = serviceStore.services.filter(s => s.category_id === category.id)
    return {
      ...category,
      services: categoryServices
    }
  })
})

function finishBookingFlow() {
  bookingStore.reset()
  router.push('/')
}
</script>
