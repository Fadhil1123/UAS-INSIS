<template>
  <AuthenticatedLayout>
    <!-- Welcome Header -->
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-1">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Dosen</h1>
        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-violet-50 text-violet-700 border border-violet-200 rounded-full">
          Dosen
        </span>
      </div>
      <p class="text-slate-500 text-sm mt-1">Sistem Peminjaman Ruangan Terintegrasi — Universitas Lambung Mangkurat.</p>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <!-- Stat Card 1 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-violet-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Peminjaman Saya</p>
          <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ stats.my_bookings_count || 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-violet-50 rounded-xl text-violet-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 2 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-emerald-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Peminjaman Disetujui</p>
          <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ stats.approved_bookings_count || 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 3 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-amber-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Menunggu Persetujuan</p>
          <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ stats.pending_bookings_count || 0 }}</h3>
        </div>
        <div class="w-12 h-12 bg-amber-50 rounded-xl text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Main Dashboard Panels -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Upcoming Bookings Panel -->
      <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            Jadwal Peminjaman Terdekat
          </h2>
          <Link href="/bookings" class="text-xs font-bold text-violet-600 hover:text-violet-700 transition-colors">
            Lihat Semua Riwayat →
          </Link>
        </div>

        <!-- Bookings List -->
        <div v-if="upcomingBookings && upcomingBookings.length > 0" class="space-y-4">
          <div
            v-for="booking in upcomingBookings"
            :key="booking.id"
            class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200 transition-all duration-200 bg-slate-50/50"
          >
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-xl bg-violet-50 flex flex-col items-center justify-center text-violet-600">
                <span class="text-[10px] font-bold uppercase">{{ getMonthShort(booking.booking_date) }}</span>
                <span class="text-lg font-extrabold leading-3 mt-0.5">{{ getDay(booking.booking_date) }}</span>
              </div>
              <div>
                <h4 class="font-bold text-slate-800">{{ booking.room_name }}</h4>
                <p class="text-xs text-slate-400 mt-1 flex items-center gap-1.5 font-medium">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  {{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }} WIB
                </p>
              </div>
            </div>
            <div>
              <span
                :class="[
                  'px-3 py-1 text-xs font-bold rounded-full border',
                  booking.status === 'approved' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' :
                  booking.status === 'pending' ? 'bg-amber-50 text-amber-700 border-amber-100' :
                  'bg-rose-50 text-rose-700 border-rose-100'
                ]"
              >
                {{ booking.status === 'approved' ? 'Disetujui' : booking.status === 'pending' ? 'Pending' : 'Ditolak' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
          <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <p class="text-sm font-semibold text-slate-500">Belum ada peminjaman aktif terdekat.</p>
          <p class="text-xs text-slate-400 mt-1">Silakan ajukan peminjaman ruangan untuk kegiatan perkuliahan.</p>
          <Link href="/rooms" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-xl text-xs shadow-md shadow-violet-600/10 transition-colors">
            Cari Ruangan
          </Link>
        </div>
      </div>

      <!-- Info Sidebar -->
      <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 flex flex-col justify-between">
        <div>
          <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Panduan Peminjaman
          </h2>

          <ul class="space-y-4">
            <li class="flex gap-3">
              <span class="flex-shrink-0 w-6 h-6 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-xs">1</span>
              <div>
                <p class="text-xs font-bold text-slate-800">Cari Ruangan</p>
                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Pilih menu "Daftar Ruangan" lalu tentukan ruangan yang ingin digunakan untuk kegiatan perkuliahan.</p>
              </div>
            </li>
            <li class="flex gap-3">
              <span class="flex-shrink-0 w-6 h-6 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-xs">2</span>
              <div>
                <p class="text-xs font-bold text-slate-800">Cek Kalender Ketersediaan</p>
                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Pastikan slot waktu yang dipilih tidak bertabrakan dengan jadwal yang sudah ada.</p>
              </div>
            </li>
            <li class="flex gap-3">
              <span class="flex-shrink-0 w-6 h-6 rounded-full bg-violet-50 text-violet-600 flex items-center justify-center font-bold text-xs">3</span>
              <div>
                <p class="text-xs font-bold text-slate-800">Kirim Pengajuan</p>
                <p class="text-[11px] text-slate-400 mt-0.5 leading-relaxed">Isi form pengajuan dengan lengkap. Sebagai dosen, sertakan keperluan dan kegiatan yang akan dilakukan.</p>
              </div>
            </li>
          </ul>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 text-[11px] text-slate-400 leading-relaxed font-medium">
          <strong>Catatan:</strong> Status peminjaman akan diproses oleh admin dan notifikasi akan dikirimkan ke nomor yang terdaftar.
        </div>
      </div>
    </div>

    <!-- Ruangan Unggulan -->
    <div class="mt-8 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
          </svg>
          Ruangan Tersedia
        </h2>
        <Link href="/rooms" class="text-xs font-bold text-violet-600 hover:text-violet-700 transition-colors">
          Lihat Semua Ruangan →
        </Link>
      </div>

      <div v-if="featuredRooms && featuredRooms.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="room in featuredRooms"
          :key="room.id"
          class="bg-slate-50/50 rounded-2xl border border-slate-200/50 hover:border-violet-100 transition-all duration-300 overflow-hidden flex flex-col group"
        >
          <div class="aspect-video bg-slate-100 relative overflow-hidden">
            <img
              v-if="room.photo_path"
              :src="'/' + room.photo_path"
              :alt="room.room_name"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-100 to-slate-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
              </svg>
            </div>
            <div class="absolute bottom-3 left-3">
              <span class="px-2 py-0.5 text-[10px] font-bold text-white bg-slate-900/60 backdrop-blur-md rounded-md uppercase">
                {{ room.category }}
              </span>
            </div>
          </div>

          <div class="p-4 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <span class="text-[10px] font-extrabold text-violet-600 bg-violet-50 border border-violet-100/50 px-1.5 py-0.5 rounded-md">{{ room.room_code }}</span>
                <span class="text-xs font-semibold text-slate-500">Kapasitas: {{ room.capacity }} orang</span>
              </div>
              <h4 class="font-bold text-slate-800 line-clamp-1 group-hover:text-violet-600 transition-colors">{{ room.room_name }}</h4>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100">
              <Link
                :href="'/calendar?room_id=' + room.room_code"
                class="w-full py-2 bg-violet-600 hover:bg-violet-500 text-white font-bold rounded-xl text-[11px] shadow-sm transition-colors flex items-center justify-center gap-1 cursor-pointer"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Pilih & Cek Kalender
              </Link>
            </div>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-6 text-slate-400 text-xs font-semibold">
        Belum ada data ruangan tersedia.
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      my_bookings_count: 0,
      approved_bookings_count: 0,
      pending_bookings_count: 0
    })
  },
  upcomingBookings: {
    type: Array,
    default: () => []
  },
  featuredRooms: {
    type: Array,
    default: () => []
  }
})

const getMonthShort = (dateStr) => {
  if (!dateStr) return 'JUN'
  const date = new Date(dateStr)
  return date.toLocaleString('id-ID', { month: 'short' }).toUpperCase()
}

const getDay = (dateStr) => {
  if (!dateStr) return '01'
  const date = new Date(dateStr)
  return String(date.getDate()).padStart(2, '0')
}

const formatTime = (timeStr) => {
  if (!timeStr) return '00:00'
  return timeStr.substring(0, 5)
}
</script>
