<template>
  <AuthenticatedLayout>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Riwayat Peminjaman</h1>
        <p class="text-slate-500 text-sm mt-1">Pantau status seluruh pengajuan peminjaman ruangan Anda.</p>
      </div>
      <Link
        href="/bookings/create"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/20 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
        </svg>
        Ajukan Peminjaman
      </Link>
    </div>

    <!-- Status Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
      <div
        v-for="stat in summaryStats"
        :key="stat.label"
        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 flex items-center gap-3"
      >
        <div :class="['w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0', stat.bgColor]">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" :class="stat.iconColor" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" :d="stat.icon" />
          </svg>
        </div>
        <div>
          <p class="text-xs font-semibold text-slate-400">{{ stat.label }}</p>
          <p class="text-xl font-extrabold text-slate-900">{{ stat.count }}</p>
        </div>
      </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-5 flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Cari ruangan atau keperluan..."
          class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 font-medium text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400"
        />
      </div>
      <div class="relative">
        <select
          v-model="filterStatus"
          class="appearance-none w-full sm:w-44 bg-slate-50 border border-slate-200 text-slate-700 font-semibold py-2.5 pl-4 pr-9 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all"
        >
          <option value="">Semua Status</option>
          <option value="pending">Pending</option>
          <option value="approved">Disetujui</option>
          <option value="rejected">Ditolak</option>
          <option value="cancelled">Dibatalkan</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
      </div>
    </div>

    <!-- Booking List -->
    <div v-if="filteredBookings.length > 0" class="space-y-3">
      <div
        v-for="booking in filteredBookings"
        :key="booking.id"
        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-200 overflow-hidden"
      >
        <div class="p-5 flex flex-col sm:flex-row sm:items-center gap-4">
          <!-- Date Block -->
          <div class="flex-shrink-0 flex items-center gap-4">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 border border-indigo-100 flex flex-col items-center justify-center text-indigo-600">
              <span class="text-[10px] font-bold uppercase">{{ getMonthShort(booking.booking_date) }}</span>
              <span class="text-xl font-extrabold leading-tight">{{ getDay(booking.booking_date) }}</span>
            </div>
          </div>

          <!-- Info -->
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
              <h3 class="text-sm font-bold text-slate-900 truncate">{{ booking.room_name }}</h3>
              <span class="text-[10px] font-bold text-slate-400 bg-slate-100 rounded-md px-2 py-0.5">{{ booking.room_code }}</span>
              <span :class="['text-[10px] font-bold px-2 py-0.5 rounded-md border', bookingTypeBadge(booking.booking_type).class]">
                {{ bookingTypeBadge(booking.booking_type).label }}
              </span>
            </div>
            <p class="text-xs text-slate-500 font-medium line-clamp-1">{{ booking.purpose }}</p>
            <div class="flex flex-wrap items-center gap-3 mt-2">
              <span class="text-xs text-slate-400 flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ formatTime(booking.start_time) }} – {{ formatTime(booking.end_time) }} WITA
              </span>
              <span class="text-xs text-slate-300">•</span>
              <span class="text-xs text-slate-400">Diajukan: {{ booking.created_at }}</span>
            </div>
          </div>

          <!-- Status & Actions -->
          <div class="flex items-center gap-3 flex-shrink-0">
            <span :class="['px-3 py-1.5 text-xs font-bold rounded-xl border', statusBadge(booking.status).class]">
              {{ statusBadge(booking.status).label }}
            </span>
            <div class="flex items-center gap-2">
              <!-- Detail Button -->
              <button
                @click="openDetail(booking)"
                class="p-2 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl transition-colors"
                title="Lihat Detail"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              <!-- Cancel Button (only if pending) -->
              <button
                v-if="booking.status === 'pending'"
                @click="openCancelConfirm(booking)"
                class="p-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 rounded-xl transition-colors"
                title="Batalkan Booking"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Rejection Reason Bar -->
        <div
          v-if="booking.status === 'rejected' && booking.rejection_reason"
          class="px-5 pb-4"
        >
          <div class="flex items-start gap-2 bg-rose-50 border border-rose-100 rounded-xl px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs text-rose-700 font-medium"><strong>Alasan Penolakan:</strong> {{ booking.rejection_reason }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-20 bg-white rounded-3xl border border-slate-200/80 shadow-xs border-dashed">
      <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mx-auto mb-4 border border-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
      </div>
      <p class="text-slate-500 font-bold text-sm">Belum ada riwayat peminjaman</p>
      <p class="text-slate-400 text-xs mt-1">{{ searchQuery || filterStatus ? 'Tidak ada hasil yang cocok dengan filter.' : 'Ajukan peminjaman pertama Anda sekarang.' }}</p>
      <Link v-if="!searchQuery && !filterStatus" href="/bookings/create" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-600/10 transition-colors">
        Ajukan Sekarang
      </Link>
    </div>

    <!-- ==================== MODAL DETAIL ==================== -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="detailModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detailModal = null"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-slate-100">
          <!-- Modal Header -->
          <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between">
            <div>
              <h3 class="text-lg font-extrabold text-slate-900">Detail Peminjaman</h3>
              <p class="text-xs text-slate-400 mt-0.5">ID #{{ detailModal.id }}</p>
            </div>
            <button @click="detailModal = null" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 space-y-4">
            <!-- Status Badge -->
            <div class="flex justify-center">
              <span :class="['px-5 py-2 text-sm font-extrabold rounded-2xl border-2', statusBadge(detailModal.status).class]">
                {{ statusBadge(detailModal.status).label }}
              </span>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ruangan</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ detailModal.room_name }}</p>
                <p class="text-xs text-slate-500">{{ detailModal.room_code }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis</p>
                <p class="text-sm font-bold text-slate-800 mt-1 capitalize">{{ detailModal.booking_type }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ formatDateFull(detailModal.booking_date) }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ formatTime(detailModal.start_time) }} – {{ formatTime(detailModal.end_time) }}</p>
              </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Keperluan</p>
              <p class="text-sm text-slate-700 leading-relaxed">{{ detailModal.purpose }}</p>
            </div>

            <div
              v-if="detailModal.status === 'rejected' && detailModal.rejection_reason"
              class="bg-rose-50 border border-rose-100 rounded-2xl p-4"
            >
              <p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider mb-1">Alasan Penolakan</p>
              <p class="text-sm text-rose-700 leading-relaxed">{{ detailModal.rejection_reason }}</p>
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs text-slate-400">Diajukan: {{ detailModal.created_at }}</p>
            <div class="flex gap-2">
              <button
                v-if="detailModal.status === 'pending'"
                @click="openCancelConfirm(detailModal); detailModal = null"
                class="px-4 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-bold rounded-xl text-xs transition-colors"
              >
                Batalkan
              </button>
              <button
                @click="detailModal = null"
                class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-50 transition-colors"
              >
                Tutup
              </button>
            </div>
          </div>
        </div>
      </div>
    </transition>

    <!-- ==================== MODAL KONFIRMASI CANCEL ==================== -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="cancelTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="cancelTarget = null"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 overflow-hidden border border-slate-100 p-6">
          <div class="text-center mb-6">
            <div class="w-14 h-14 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-rose-100">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Batalkan Peminjaman?</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
              Booking <strong>{{ cancelTarget?.room_name }}</strong> pada
              <strong>{{ formatDateFull(cancelTarget?.booking_date) }}</strong> akan dibatalkan dan tidak dapat dikembalikan.
            </p>
          </div>
          <div class="flex gap-3">
            <button
              @click="cancelTarget = null"
              class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-50 transition-colors"
            >
              Kembali
            </button>
            <button
              @click="confirmCancel"
              :disabled="isCancelling"
              class="flex-1 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 disabled:bg-rose-300 text-white font-bold rounded-xl text-sm shadow-md shadow-rose-600/20 transition-all"
            >
              {{ isCancelling ? 'Membatalkan...' : 'Ya, Batalkan' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  bookings: {
    type: Array,
    default: () => []
  }
})

// Filter state
const searchQuery = ref('')
const filterStatus = ref('')

// Modal state
const detailModal = ref(null)
const cancelTarget = ref(null)
const isCancelling = ref(false)

// Summary Stats
const summaryStats = computed(() => [
  {
    label: 'Total',
    count: props.bookings.length,
    bgColor: 'bg-indigo-50',
    iconColor: 'text-indigo-500',
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'
  },
  {
    label: 'Pending',
    count: props.bookings.filter(b => b.status === 'pending').length,
    bgColor: 'bg-amber-50',
    iconColor: 'text-amber-500',
    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'
  },
  {
    label: 'Disetujui',
    count: props.bookings.filter(b => b.status === 'approved').length,
    bgColor: 'bg-emerald-50',
    iconColor: 'text-emerald-500',
    icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
  },
  {
    label: 'Ditolak',
    count: props.bookings.filter(b => b.status === 'rejected').length,
    bgColor: 'bg-rose-50',
    iconColor: 'text-rose-500',
    icon: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'
  },
])

// Filtered bookings
const filteredBookings = computed(() => {
  return props.bookings.filter(b => {
    const matchSearch = searchQuery.value === '' ||
      b.room_name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      b.purpose.toLowerCase().includes(searchQuery.value.toLowerCase())
    const matchStatus = filterStatus.value === '' || b.status === filterStatus.value
    return matchSearch && matchStatus
  })
})

// Status badge helper
const statusBadge = (status) => {
  const map = {
    approved:  { label: 'Disetujui',   class: 'bg-emerald-50 text-emerald-700 border-emerald-200' },
    pending:   { label: 'Menunggu',    class: 'bg-amber-50 text-amber-700 border-amber-200' },
    rejected:  { label: 'Ditolak',     class: 'bg-rose-50 text-rose-700 border-rose-200' },
    cancelled: { label: 'Dibatalkan',  class: 'bg-slate-100 text-slate-500 border-slate-200' },
  }
  return map[status] || { label: status, class: 'bg-slate-100 text-slate-500 border-slate-200' }
}

// Booking type badge helper
const bookingTypeBadge = (type) => {
  return type === 'perkuliahan'
    ? { label: 'Perkuliahan', class: 'bg-blue-50 text-blue-600 border-blue-100' }
    : { label: 'Organisasi', class: 'bg-violet-50 text-violet-600 border-violet-100' }
}

// Date helpers
const getMonthShort = (dateStr) => {
  if (!dateStr) return '---'
  return new Date(dateStr).toLocaleString('id-ID', { month: 'short' }).toUpperCase()
}
const getDay = (dateStr) => {
  if (!dateStr) return '--'
  return String(new Date(dateStr).getDate()).padStart(2, '0')
}
const formatDateFull = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })
}
const formatTime = (timeStr) => {
  if (!timeStr) return '--:--'
  return timeStr.substring(0, 5)
}

// Modal handlers
const openDetail = (booking) => {
  detailModal.value = booking
}
const openCancelConfirm = (booking) => {
  cancelTarget.value = booking
}
const confirmCancel = () => {
  if (!cancelTarget.value) return
  isCancelling.value = true
  router.patch(`/bookings/${cancelTarget.value.id}/cancel`, {}, {
    onFinish: () => {
      isCancelling.value = false
      cancelTarget.value = null
    }
  })
}
</script>
