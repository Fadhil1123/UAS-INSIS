<template>
  <AuthenticatedLayout>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Persetujuan Peminjaman</h1>
        <p class="text-slate-500 text-sm mt-1">
          Tinjau dan proses pengajuan peminjaman ruangan yang masuk dari mahasiswa dan dosen.
        </p>
      </div>
      <!-- Counter Badge -->
      <div class="flex items-center gap-2 px-5 py-3 bg-amber-50 border border-amber-200 rounded-2xl">
        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 animate-pulse"></span>
        <span class="text-sm font-bold text-amber-700">
          {{ bookings.length }} Pengajuan Menunggu
        </span>
      </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-4 mb-5">
      <div class="relative">
        <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Cari nama peminjam, ruangan, atau keperluan..."
          class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 font-medium text-sm rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all placeholder:text-slate-400"
        />
      </div>
    </div>

    <!-- Booking List -->
    <div v-if="filteredBookings.length > 0" class="space-y-3">
      <div
        v-for="booking in filteredBookings"
        :key="booking.id"
        class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-indigo-100 transition-all duration-200 overflow-hidden"
      >
        <div class="p-5 flex flex-col lg:flex-row lg:items-center gap-5">

          <!-- User Info -->
          <div class="flex items-center gap-4 flex-shrink-0 lg:w-56">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center text-indigo-700 font-extrabold text-sm border border-white shadow-sm flex-shrink-0">
              {{ booking.user_name.charAt(0).toUpperCase() }}
            </div>
            <div class="min-w-0">
              <p class="text-sm font-bold text-slate-800 truncate">{{ booking.user_name }}</p>
              <p class="text-xs text-slate-400 font-medium truncate">{{ booking.user_nomor_induk }}</p>
            </div>
          </div>

          <!-- Booking Info -->
          <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
              <span class="text-sm font-bold text-slate-800">{{ booking.room_name }}</span>
              <span class="text-[10px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">
                {{ booking.room_code }}
              </span>
              <span :class="['text-[10px] font-bold px-2 py-0.5 rounded-md border', bookingTypeBadge(booking.booking_type).class]">
                {{ bookingTypeBadge(booking.booking_type).label }}
              </span>
            </div>
            <p class="text-xs text-slate-500 font-medium line-clamp-1 mb-2">{{ booking.purpose }}</p>
            <div class="flex flex-wrap items-center gap-3">
              <span class="text-xs text-slate-600 font-semibold flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                {{ formatDate(booking.booking_date) }}
              </span>
              <span class="text-xs text-slate-600 font-semibold flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg border border-slate-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ formatTime(booking.start_time) }} – {{ formatTime(booking.end_time) }} WITA
              </span>
              <span class="text-[11px] text-slate-400 font-medium">Masuk: {{ booking.created_at }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-2 flex-shrink-0">
            <!-- Detail Button -->
            <button
              @click="openDetail(booking)"
              class="p-2.5 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-600 rounded-xl transition-colors"
              title="Lihat Detail"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>

            <!-- Approve Button -->
            <button
              @click="openApproveConfirm(booking)"
              class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-all"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              Setujui
            </button>

            <!-- Reject Button -->
            <button
              @click="openRejectModal(booking)"
              class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-rose-50 hover:bg-rose-600 hover:text-white border border-rose-200 hover:border-rose-600 text-rose-600 font-bold rounded-xl text-xs transition-all"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
              Tolak
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-24 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
      <div class="relative w-20 h-20 mx-auto mb-5">
        <div class="absolute inset-0 bg-emerald-100 rounded-full animate-ping opacity-30"></div>
        <div class="relative w-20 h-20 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center text-emerald-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-1">
        {{ searchQuery ? 'Tidak ada hasil ditemukan' : 'Semua pengajuan sudah diproses!' }}
      </h3>
      <p class="text-sm text-slate-400 max-w-sm mx-auto">
        {{ searchQuery ? 'Coba kata kunci lain.' : 'Tidak ada pengajuan peminjaman yang menunggu persetujuan saat ini.' }}
      </p>
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
      <div v-if="detailTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detailTarget = null"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg relative z-10 overflow-hidden border border-slate-100">
          <!-- Header -->
          <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between">
            <div>
              <h3 class="text-lg font-extrabold text-slate-900">Detail Pengajuan</h3>
              <p class="text-xs text-slate-400 mt-0.5">ID #{{ detailTarget.id }} · Masuk {{ detailTarget.created_at }}</p>
            </div>
            <button @click="detailTarget = null" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>

          <!-- Body -->
          <div class="p-6 space-y-4">
            <!-- Peminjam Info -->
            <div class="flex items-center gap-4 p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
              <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-200 to-purple-200 flex items-center justify-center text-indigo-700 font-extrabold text-base flex-shrink-0">
                {{ detailTarget.user_name.charAt(0).toUpperCase() }}
              </div>
              <div>
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Peminjam</p>
                <p class="text-sm font-extrabold text-indigo-900 mt-0.5">{{ detailTarget.user_name }}</p>
                <p class="text-xs text-indigo-600 font-medium">{{ detailTarget.user_nomor_induk }}</p>
              </div>
            </div>

            <!-- Detail Grid -->
            <div class="grid grid-cols-2 gap-3">
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Ruangan</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ detailTarget.room_name }}</p>
                <p class="text-xs text-indigo-500 font-bold">{{ detailTarget.room_code }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis</p>
                <p class="text-sm font-bold text-slate-800 mt-1 capitalize">{{ detailTarget.booking_type }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ formatDate(detailTarget.booking_date) }}</p>
              </div>
              <div class="bg-slate-50 rounded-2xl p-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Waktu</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ formatTime(detailTarget.start_time) }} – {{ formatTime(detailTarget.end_time) }}</p>
              </div>
            </div>

            <div class="bg-slate-50 rounded-2xl p-4">
              <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">Keperluan Kegiatan</p>
              <p class="text-sm text-slate-700 leading-relaxed">{{ detailTarget.purpose }}</p>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
            <button @click="detailTarget = null" class="px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-50 transition-colors">
              Tutup
            </button>
            <button
              @click="openRejectModal(detailTarget); detailTarget = null"
              class="px-4 py-2 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 font-bold rounded-xl text-xs transition-colors"
            >
              Tolak
            </button>
            <button
              @click="openApproveConfirm(detailTarget); detailTarget = null"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs shadow-md shadow-emerald-600/20 transition-colors"
            >
              Setujui
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ==================== MODAL KONFIRMASI APPROVE ==================== -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="approveTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="approveTarget = null"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 p-6">
          <div class="text-center mb-6">
            <div class="w-14 h-14 bg-emerald-50 border border-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900">Setujui Pengajuan?</h3>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
              Pengajuan <strong>{{ approveTarget?.room_name }}</strong> oleh <strong>{{ approveTarget?.user_name }}</strong>
              pada <strong>{{ formatDate(approveTarget?.booking_date) }}</strong> akan disetujui.
              Notifikasi WhatsApp akan dikirim otomatis.
            </p>
          </div>
          <div class="flex gap-3">
            <button
              @click="approveTarget = null"
              class="flex-1 px-4 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-50 transition-colors"
            >
              Batal
            </button>
            <button
              @click="confirmApprove"
              :disabled="isProcessing"
              class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:bg-emerald-300 text-white font-bold rounded-xl text-sm shadow-md shadow-emerald-600/20 transition-all"
            >
              {{ isProcessing ? 'Memproses...' : 'Ya, Setujui' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- ==================== MODAL REJECT + ALASAN ==================== -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="rejectTarget" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeRejectModal"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden">
          <!-- Header -->
          <div class="p-6 pb-4 border-b border-slate-100 flex items-start justify-between">
            <div>
              <h3 class="text-lg font-extrabold text-slate-900">Tolak Pengajuan</h3>
              <p class="text-xs text-slate-400 mt-0.5">
                {{ rejectTarget.room_name }} · {{ rejectTarget.user_name }}
              </p>
            </div>
            <button @click="closeRejectModal" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition-colors">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>

          <!-- Body -->
          <div class="p-6 space-y-4">
            <div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              <p class="text-xs text-rose-700 font-medium leading-relaxed">
                Alasan penolakan <strong>wajib diisi</strong>. Alasan ini akan dikirimkan ke peminjam melalui notifikasi WhatsApp agar mereka dapat mengajukan kembali.
              </p>
            </div>

            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Alasan Penolakan <span class="text-rose-500">*</span>
              </label>
              <textarea
                v-model="rejectReason"
                rows="4"
                placeholder="Contoh: Ruangan sudah dialokasikan untuk ujian pada tanggal tersebut. Silakan pilih tanggal atau ruangan lain."
                class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-medium py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-all resize-none placeholder:text-slate-400 placeholder:font-normal text-sm"
                :class="{ 'border-rose-400 bg-rose-50/50': rejectError }"
              ></textarea>
              <div class="flex items-center justify-between mt-1.5">
                <p v-if="rejectError" class="text-xs font-semibold text-rose-600 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  Alasan penolakan wajib diisi.
                </p>
                <p class="text-xs text-slate-400 ml-auto">{{ rejectReason.length }}/500</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-2">
            <button @click="closeRejectModal" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-50 transition-colors">
              Batal
            </button>
            <button
              @click="confirmReject"
              :disabled="isProcessing"
              class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-600 hover:bg-rose-500 disabled:bg-rose-300 text-white font-bold rounded-xl text-sm shadow-md shadow-rose-600/20 transition-all"
            >
              <svg v-if="isProcessing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              {{ isProcessing ? 'Memproses...' : 'Tolak Pengajuan' }}
            </button>
          </div>
        </div>
      </div>
    </transition>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  bookings: {
    type: Array,
    default: () => []
  }
})

// ── State ──────────────────────────────────────────
const searchQuery   = ref('')
const detailTarget  = ref(null)
const approveTarget = ref(null)
const rejectTarget  = ref(null)
const rejectReason  = ref('')
const rejectError   = ref(false)
const isProcessing  = ref(false)

// ── Computed ────────────────────────────────────────
const filteredBookings = computed(() => {
  const q = searchQuery.value.toLowerCase()
  if (!q) return props.bookings
  return props.bookings.filter(b =>
    b.user_name.toLowerCase().includes(q) ||
    b.room_name.toLowerCase().includes(q) ||
    b.purpose.toLowerCase().includes(q) ||
    b.room_code.toLowerCase().includes(q)
  )
})

// ── Helpers ─────────────────────────────────────────
const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  })
}

const formatTime = (timeStr) => {
  if (!timeStr) return '--:--'
  return timeStr.substring(0, 5)
}

const bookingTypeBadge = (type) => {
  return type === 'perkuliahan'
    ? { label: 'Perkuliahan', class: 'bg-blue-50 text-blue-600 border-blue-100' }
    : { label: 'Organisasi',  class: 'bg-violet-50 text-violet-600 border-violet-100' }
}

// ── Modal Handlers ───────────────────────────────────
const openDetail = (booking) => { detailTarget.value = booking }

const openApproveConfirm = (booking) => { approveTarget.value = booking }

const openRejectModal = (booking) => {
  rejectTarget.value = booking
  rejectReason.value = ''
  rejectError.value  = false
}

const closeRejectModal = () => {
  rejectTarget.value = null
  rejectReason.value = ''
  rejectError.value  = false
}

// ── Actions ──────────────────────────────────────────
const confirmApprove = () => {
  if (!approveTarget.value) return
  isProcessing.value = true
  router.patch(
    `/admin/bookings/${approveTarget.value.id}/approve`,
    {},
    {
      onFinish: () => {
        isProcessing.value = false
        approveTarget.value = null
      }
    }
  )
}

const confirmReject = () => {
  if (!rejectReason.value.trim()) {
    rejectError.value = true
    return
  }
  rejectError.value  = false
  isProcessing.value = true
  router.patch(
    `/admin/bookings/${rejectTarget.value.id}/reject`,
    { rejection_reason: rejectReason.value },
    {
      onFinish: () => {
        isProcessing.value = false
        closeRejectModal()
      }
    }
  )
}
</script>
