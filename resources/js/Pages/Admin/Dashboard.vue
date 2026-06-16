<template>
  <AuthenticatedLayout>
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 animate-fade-in-up stagger-1">
      <div class="relative">
        <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-500 blur-lg opacity-20 rounded-full"></div>
        <h1 class="relative text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
          Dashboard Admin Prodi
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-indigo-100 text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
            </svg>
          </span>
        </h1>
        <p class="relative text-slate-500 text-sm mt-1.5 font-medium">Pantau ketersediaan, kelola ruangan, dan proses pengajuan peminjaman.</p>
      </div>
      <div class="flex items-center gap-3">
        <Link 
          href="/admin/rooms/create" 
          class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl text-sm shadow-lg shadow-indigo-500/30 hover:shadow-indigo-500/50 hover:-translate-y-0.5 transition-all duration-300 cursor-pointer"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Ruangan
        </Link>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 animate-fade-in-up stagger-2">
      <!-- Stat Card 1 -->
      <div class="relative bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-indigo-500/10 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
          <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Ruangan</p>
          <h3 class="text-3xl font-black text-slate-900 mt-2 tracking-tight">{{ stats.rooms_count || 0 }}</h3>
        </div>
        <div class="relative z-10 w-12 h-12 bg-gradient-to-br from-slate-100 to-slate-200 rounded-xl text-slate-600 flex items-center justify-center group-hover:rotate-12 transition-transform duration-300 shadow-sm">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 2 -->
      <div class="relative bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-amber-500/10 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-amber-50 to-amber-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
          <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Persetujuan Pending</p>
          <h3 class="text-3xl font-black text-amber-500 mt-2 tracking-tight">{{ stats.pending_bookings_count || 0 }}</h3>
        </div>
        <div class="relative z-10 w-12 h-12 bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl text-amber-600 flex items-center justify-center group-hover:-rotate-12 transition-transform duration-300 shadow-sm border border-amber-200/50">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 3 -->
      <div class="relative bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-emerald-50 to-emerald-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
          <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Booking Aktif</p>
          <h3 class="text-3xl font-black text-emerald-600 mt-2 tracking-tight">{{ stats.approved_bookings_count || 0 }}</h3>
        </div>
        <div class="relative z-10 w-12 h-12 bg-gradient-to-br from-emerald-100 to-emerald-200 rounded-xl text-emerald-600 flex items-center justify-center group-hover:rotate-12 transition-transform duration-300 shadow-sm border border-emerald-200/50">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 4 -->
      <div class="relative bg-white p-6 rounded-2xl border border-slate-200/60 shadow-sm hover:shadow-xl hover:shadow-blue-500/10 flex items-center justify-between group hover:-translate-y-1 transition-all duration-300 overflow-hidden">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700 ease-out"></div>
        <div class="relative z-10">
          <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Booking</p>
          <h3 class="text-3xl font-black text-blue-600 mt-2 tracking-tight">{{ stats.bookings_count || 0 }}</h3>
        </div>
        <div class="relative z-10 w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl text-blue-600 flex items-center justify-center group-hover:-rotate-12 transition-transform duration-300 shadow-sm border border-blue-200/50">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Bookings Pending Panel -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-200/60 shadow-lg shadow-slate-200/40 p-6 mb-8 animate-fade-in-up stagger-3">
      <div class="flex items-center justify-between mb-6 border-b border-slate-100 pb-4">
        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          Pengajuan Menunggu Persetujuan
        </h2>
        <span class="px-3 py-1 text-xs font-bold text-amber-700 bg-amber-50 border border-amber-200/60 rounded-full shadow-sm flex items-center gap-1.5">
          <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
          {{ pendingBookings ? pendingBookings.length : 0 }} Pengajuan
        </span>
      </div>

      <!-- Pending Table -->
      <div v-if="pendingBookings && pendingBookings.length > 0" class="overflow-x-auto -mx-6">
        <div class="inline-block min-w-full align-middle px-6">
          <table class="min-w-full border-separate border-spacing-y-2">
            <thead>
              <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider">
                <th class="py-3 px-5 border-b border-slate-100 bg-slate-50/50 rounded-tl-xl rounded-bl-xl">Peminjam</th>
                <th class="py-3 px-5 border-b border-slate-100 bg-slate-50/50">Ruangan</th>
                <th class="py-3 px-5 border-b border-slate-100 bg-slate-50/50">Tanggal & Waktu</th>
                <th class="py-3 px-5 border-b border-slate-100 bg-slate-50/50 text-right rounded-tr-xl rounded-br-xl">Aksi</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              <tr 
                v-for="(booking, index) in pendingBookings" 
                :key="booking.id"
                class="bg-white hover:bg-indigo-50/30 transition-all duration-300 group shadow-[0_2px_10px_-4px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_20px_-8px_rgba(79,70,229,0.15)] rounded-2xl"
              >
                <td class="py-4 px-5 rounded-tl-xl rounded-bl-xl border-y border-l border-slate-100 group-hover:border-indigo-100">
                  <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-bold text-xs border border-white shadow-sm">
                      {{ booking.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                      <div class="font-bold text-slate-800">{{ booking.user_name }}</div>
                      <div class="text-xs text-slate-400 mt-0.5 font-medium flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ booking.user_phone }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="py-4 px-5 border-y border-slate-100 group-hover:border-indigo-100">
                  <div class="font-bold text-slate-700">{{ booking.room_name }}</div>
                  <div class="text-[10px] text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md inline-flex items-center gap-1 mt-1.5 font-bold shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1v1H9V7zm5 0h1v1h-1V7zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1zm-5 4h1v1H9v-1zm5 0h1v1h-1v-1z" />
                    </svg>
                    {{ booking.room_id }}
                  </div>
                </td>
                <td class="py-4 px-5 border-y border-slate-100 group-hover:border-indigo-100">
                  <div class="text-slate-700 font-bold">{{ formatDate(booking.booking_date) }}</div>
                  <div class="text-xs text-slate-500 mt-1 flex items-center gap-1 bg-slate-50 w-max px-2 py-1 rounded-md border border-slate-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }} WIB
                  </div>
                </td>
                <td class="py-4 px-5 text-right rounded-tr-xl rounded-br-xl border-y border-r border-slate-100 group-hover:border-indigo-100">
                  <Link 
                    href="/admin/bookings"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-50 hover:bg-indigo-600 text-slate-600 hover:text-white font-bold rounded-xl text-xs transition-all duration-300 shadow-sm hover:shadow-indigo-500/30 border border-slate-200 hover:border-indigo-500 group/btn"
                  >
                    Tinjau
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 group-hover/btn:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 px-4 bg-gradient-to-b from-slate-50/50 to-slate-100/30 rounded-2xl border border-dashed border-slate-200">
        <div class="relative w-20 h-20 mx-auto mb-5">
          <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-20"></div>
          <div class="relative w-20 h-20 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-indigo-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>
        <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak ada pengajuan baru</h3>
        <p class="text-sm font-medium text-slate-500 max-w-sm mx-auto">Seluruh pengajuan peminjaman ruangan sudah diproses. Anda bisa bernapas lega sekarang!</p>
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
      rooms_count: 0,
      pending_bookings_count: 0,
      approved_bookings_count: 0,
      bookings_count: 0
    })
  },
  pendingBookings: {
    type: Array,
    default: () => []
  }
})

// Date and Time Formatting Helpers
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const date = new Date(dateStr)
  return date.toLocaleDateString('id-ID', {
    weekday: 'long',
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatTime = (timeStr) => {
  if (!timeStr) return '00:00'
  return timeStr.substring(0, 5)
}
</script>
