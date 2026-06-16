<template>
  <AuthenticatedLayout>
    <!-- Welcome Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Dashboard Admin Prodi</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau ketersediaan, kelola ruangan, dan proses pengajuan peminjaman.</p>
      </div>
      <div class="flex items-center gap-3">
        <Link 
          href="/admin/rooms/create" 
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/10 transition-colors cursor-pointer"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Ruangan
        </Link>
      </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <!-- Stat Card 1 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Ruangan</p>
          <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ stats.rooms_count || 0 }}</h3>
        </div>
        <div class="w-11 h-11 bg-slate-50 rounded-xl text-slate-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 2 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Persetujuan Pending</p>
          <h3 class="text-3xl font-extrabold text-indigo-600 mt-2">{{ stats.pending_bookings_count || 0 }}</h3>
        </div>
        <div class="w-11 h-11 bg-indigo-50 rounded-xl text-indigo-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 3 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Booking Aktif</p>
          <h3 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ stats.approved_bookings_count || 0 }}</h3>
        </div>
        <div class="w-11 h-11 bg-emerald-50 rounded-xl text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>

      <!-- Stat Card 4 -->
      <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between group hover:border-indigo-200 transition-all duration-300">
        <div>
          <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Booking</p>
          <h3 class="text-3xl font-extrabold text-slate-900 mt-2">{{ stats.bookings_count || 0 }}</h3>
        </div>
        <div class="w-11 h-11 bg-slate-50 rounded-xl text-slate-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5.5 h-5.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
    </div>

    <!-- Bookings Pending Panel -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 mb-8">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2" />
          </svg>
          Pengajuan Menunggu Persetujuan
        </h2>
        <span class="px-2.5 py-0.5 text-xs font-bold text-indigo-700 bg-indigo-50 border border-indigo-100 rounded-full">
          {{ pendingBookings ? pendingBookings.length : 0 }} Pengajuan
        </span>
      </div>

      <!-- Pending Table -->
      <div v-if="pendingBookings && pendingBookings.length > 0" class="overflow-x-auto -mx-6">
        <div class="inline-block min-w-full align-middle px-6">
          <table class="min-w-full divide-y divide-slate-100">
            <thead>
              <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-wider">
                <th class="py-3 px-4">Peminjam</th>
                <th class="py-3 px-4">Ruangan</th>
                <th class="py-3 px-4">Tanggal Peminjaman</th>
                <th class="py-3 px-4">Waktu</th>
                <th class="py-3 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
              <tr 
                v-for="booking in pendingBookings" 
                :key="booking.id"
                class="hover:bg-slate-50/50 transition-colors"
              >
                <td class="py-4 px-4">
                  <div class="font-bold text-slate-800">{{ booking.user_name }}</div>
                  <div class="text-xs text-slate-400 mt-0.5">{{ booking.user_phone }}</div>
                </td>
                <td class="py-4 px-4">
                  <div class="font-semibold text-slate-700">{{ booking.room_name }}</div>
                  <div class="text-[10px] text-indigo-600 bg-indigo-50/50 border border-indigo-100/30 px-1.5 py-0.5 rounded-md inline-block mt-0.5 font-bold">{{ booking.room_id }}</div>
                </td>
                <td class="py-4 px-4 text-slate-600 font-medium">
                  {{ formatDate(booking.booking_date) }}
                </td>
                <td class="py-4 px-4 text-slate-600 font-medium">
                  {{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }} WIB
                </td>
                <td class="py-4 px-4 text-right">
                  <Link 
                    href="/admin/bookings"
                    class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold rounded-xl text-xs transition-colors cursor-pointer"
                  >
                    Tinjau
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
      <div v-else class="text-center py-12 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <p class="text-sm font-semibold text-slate-500">Tidak ada pengajuan peminjaman baru.</p>
        <p class="text-xs text-slate-400 mt-1">Seluruh pengajuan sudah diproses.</p>
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
