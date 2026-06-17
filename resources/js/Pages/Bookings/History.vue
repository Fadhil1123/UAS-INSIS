<template>
  <AuthenticatedLayout>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Booking</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau status pengajuan peminjaman ruangan Anda.</p>
      </div>
      <Link href="/rooms" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/10 transition-colors">
        Ajukan Booking
      </Link>
    </div>

    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div v-if="bookings && bookings.length > 0" class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-slate-50 text-left text-xs uppercase tracking-wider text-slate-400">
            <tr>
              <th class="py-3 px-5">Ruangan</th>
              <th class="py-3 px-5">Tanggal</th>
              <th class="py-3 px-5">Waktu</th>
              <th class="py-3 px-5">Jenis</th>
              <th class="py-3 px-5">Status</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="booking in bookings" :key="booking.id" class="border-t border-slate-100">
              <td class="py-4 px-5">
                <div class="font-bold text-slate-800">{{ booking.room_name }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ booking.room_code }}</div>
              </td>
              <td class="py-4 px-5 text-slate-600">{{ formatDate(booking.booking_date) }}</td>
              <td class="py-4 px-5 text-slate-600">{{ formatTime(booking.start_time) }} - {{ formatTime(booking.end_time) }}</td>
              <td class="py-4 px-5 text-slate-600 capitalize">{{ booking.booking_type }}</td>
              <td class="py-4 px-5">
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold" :class="statusClass(booking.status)">{{ statusLabel(booking.status) }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="p-10 text-center">
        <h2 class="text-lg font-bold text-slate-800">Belum ada riwayat booking</h2>
        <p class="text-sm text-slate-500 mt-1">Mulai dari daftar ruangan lalu ajukan peminjaman pertama Anda.</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  bookings: {
    type: Array,
    default: () => []
  }
})

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatTime = (timeStr) => {
  if (!timeStr) return '00:00'
  return timeStr.substring(0, 5)
}

const statusLabel = (status) => {
  if (status === 'approved') return 'Disetujui'
  if (status === 'pending') return 'Menunggu'
  if (status === 'rejected') return 'Ditolak'
  if (status === 'cancelled') return 'Dibatalkan'
  return status || '-'
}

const statusClass = (status) => {
  if (status === 'approved') return 'bg-emerald-50 text-emerald-700'
  if (status === 'pending') return 'bg-amber-50 text-amber-700'
  if (status === 'rejected' || status === 'cancelled') return 'bg-rose-50 text-rose-700'
  return 'bg-slate-100 text-slate-600'
}
</script>