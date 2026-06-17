<template>
  <AuthenticatedLayout>
    <!-- Page Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Ajukan Peminjaman</h1>
        <p class="text-slate-500 text-sm mt-1">Isi formulir di bawah untuk mengajukan peminjaman ruangan.</p>
      </div>
      <Link
        href="/bookings"
        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-xl text-sm border border-slate-200 transition-colors"
      >
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        Riwayat Booking
      </Link>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Form Card (2 cols) -->
      <div class="lg:col-span-2">
        <form @submit.prevent="submitForm">
          <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-8 space-y-6">

            <!-- Pilih Ruangan -->
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Pilih Ruangan <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <select
                  id="room_id"
                  v-model="form.room_id"
                  class="w-full appearance-none bg-slate-50 border border-slate-200 text-slate-800 font-semibold py-3 pl-4 pr-10 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  :class="{ 'border-rose-400 bg-rose-50': form.errors.room_id }"
                >
                  <option value="">-- Pilih Ruangan --</option>
                  <option v-for="room in rooms" :key="room.id" :value="room.id">
                    {{ room.room_name }} ({{ room.room_code }}) — Kap. {{ room.capacity }}
                  </option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
              </div>
              <p v-if="form.errors.room_id" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ form.errors.room_id }}
              </p>
            </div>

            <!-- Jenis Peminjaman -->
            <div>
              <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">
                Jenis Peminjaman <span class="text-rose-500">*</span>
              </label>
              <div class="grid grid-cols-2 gap-3">
                <label
                  v-for="type in bookingTypes"
                  :key="type.value"
                  :class="[
                    'flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all',
                    form.booking_type === type.value
                      ? 'border-indigo-500 bg-indigo-50'
                      : 'border-slate-200 hover:border-slate-300 bg-slate-50'
                  ]"
                >
                  <input
                    type="radio"
                    :value="type.value"
                    v-model="form.booking_type"
                    class="hidden"
                  />
                  <div :class="['w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 transition-colors', form.booking_type === type.value ? 'border-indigo-500' : 'border-slate-300']">
                    <div v-if="form.booking_type === type.value" class="w-2.5 h-2.5 rounded-full bg-indigo-500"></div>
                  </div>
                  <div>
                    <p :class="['text-sm font-bold transition-colors', form.booking_type === type.value ? 'text-indigo-700' : 'text-slate-700']">{{ type.label }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">{{ type.desc }}</p>
                  </div>
                </label>
              </div>
              <p v-if="form.errors.booking_type" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ form.errors.booking_type }}
              </p>
            </div>

            <!-- Tanggal -->
            <div>
              <label for="booking_date" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Tanggal Kegiatan <span class="text-rose-500">*</span>
              </label>
              <input
                id="booking_date"
                type="date"
                v-model="form.booking_date"
                :min="today"
                class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                :class="{ 'border-rose-400 bg-rose-50': form.errors.booking_date }"
              />
              <p v-if="form.errors.booking_date" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ form.errors.booking_date }}
              </p>
            </div>

            <!-- Jam Mulai & Selesai -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label for="start_time" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                  Jam Mulai <span class="text-rose-500">*</span>
                </label>
                <input
                  id="start_time"
                  type="time"
                  v-model="form.start_time"
                  min="07:00"
                  max="21:00"
                  class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  :class="{ 'border-rose-400 bg-rose-50': form.errors.start_time }"
                />
                <p v-if="form.errors.start_time" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ form.errors.start_time }}
                </p>
              </div>
              <div>
                <label for="end_time" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                  Jam Selesai <span class="text-rose-500">*</span>
                </label>
                <input
                  id="end_time"
                  type="time"
                  v-model="form.end_time"
                  min="07:00"
                  max="22:00"
                  class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-semibold py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all"
                  :class="{ 'border-rose-400 bg-rose-50': form.errors.end_time }"
                />
                <p v-if="form.errors.end_time" class="mt-1.5 text-xs font-semibold text-rose-600 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ form.errors.end_time }}
                </p>
              </div>
            </div>

            <!-- Keperluan / Tujuan -->
            <div>
              <label for="purpose" class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">
                Keperluan / Tujuan Kegiatan <span class="text-rose-500">*</span>
              </label>
              <textarea
                id="purpose"
                v-model="form.purpose"
                rows="4"
                placeholder="Contoh: Rapat koordinasi pengurus HMTI membahas agenda IT Fest 2025..."
                class="w-full bg-slate-50 border border-slate-200 text-slate-800 font-medium py-3 px-4 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all resize-none placeholder:text-slate-400 placeholder:font-normal"
                :class="{ 'border-rose-400 bg-rose-50': form.errors.purpose }"
              ></textarea>
              <div class="flex items-start justify-between mt-1.5">
                <p v-if="form.errors.purpose" class="text-xs font-semibold text-rose-600 flex items-center gap-1">
                  <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ form.errors.purpose }}
                </p>
                <p class="text-xs text-slate-400 ml-auto">{{ form.purpose.length }} karakter</p>
              </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 border-t border-slate-100 flex items-center justify-end gap-3">
              <Link href="/dashboard" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-50 transition-colors">
                Batal
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 hover:bg-indigo-500 disabled:bg-indigo-300 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/20 transition-all"
              >
                <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                {{ form.processing ? 'Mengirim...' : 'Kirim Pengajuan' }}
              </button>
            </div>
          </div>
        </form>
      </div>

      <!-- Info Sidebar (1 col) -->
      <div class="space-y-5">
        <!-- Duration Preview -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6">
          <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ringkasan Waktu
          </h3>
          <div class="space-y-3">
            <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
              <span class="text-xs font-semibold text-slate-400">Tanggal</span>
              <span class="text-xs font-bold text-slate-700">{{ formattedDate || '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
              <span class="text-xs font-semibold text-slate-400">Waktu</span>
              <span class="text-xs font-bold text-slate-700">{{ form.start_time || '--:--' }} – {{ form.end_time || '--:--' }}</span>
            </div>
            <div class="flex justify-between items-center py-2.5">
              <span class="text-xs font-semibold text-slate-400">Durasi</span>
              <span :class="['text-xs font-bold', durationMinutes > 0 ? 'text-indigo-600' : 'text-slate-400']">
                {{ durationText }}
              </span>
            </div>
          </div>
        </div>

        <!-- Rules Card -->
        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-3xl border border-indigo-100 p-6">
          <h3 class="text-sm font-bold text-indigo-800 mb-4 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Ketentuan Peminjaman
          </h3>
          <ul class="space-y-2.5">
            <li v-for="rule in rules" :key="rule" class="flex items-start gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-indigo-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              <span class="text-xs text-indigo-700 leading-relaxed">{{ rule }}</span>
            </li>
          </ul>
        </div>

        <!-- Cek Kalender Link -->
        <a
          href="/calendar"
          class="flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-200 hover:border-indigo-200 hover:bg-indigo-50/30 transition-all group"
        >
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
              </svg>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-700">Cek Ketersediaan</p>
              <p class="text-[11px] text-slate-400">Lihat kalender jadwal ruangan</p>
            </div>
          </div>
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 group-hover:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
          </svg>
        </a>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  rooms: {
    type: Array,
    default: () => []
  }
})

const form = useForm({
  room_id: '',
  booking_type: '',
  booking_date: '',
  start_time: '',
  end_time: '',
  purpose: '',
})

const bookingTypes = [
  { value: 'perkuliahan', label: 'Perkuliahan', desc: 'Kelas, praktikum, seminar' },
  { value: 'organisasi', label: 'Organisasi', desc: 'Rapat, kegiatan HMJ/HMTI' },
]

const rules = [
  'Peminjaman diajukan minimal 1 hari sebelum tanggal kegiatan.',
  'Jam operasional ruangan 07.00 – 22.00 WITA.',
  'Pengajuan hanya berlaku untuk 1 hari per sesi.',
  'Konflik dengan jadwal kuliah akan otomatis ditolak sistem.',
  'Pembatalan hanya dapat dilakukan selama status masih Pending.',
]

// Today date string for min attribute
const today = computed(() => new Date().toISOString().split('T')[0])

// Formatted date display
const formattedDate = computed(() => {
  if (!form.booking_date) return null
  return new Date(form.booking_date).toLocaleDateString('id-ID', {
    weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
  })
})

// Duration calculation
const durationMinutes = computed(() => {
  if (!form.start_time || !form.end_time) return 0
  const [sh, sm] = form.start_time.split(':').map(Number)
  const [eh, em] = form.end_time.split(':').map(Number)
  const diff = (eh * 60 + em) - (sh * 60 + sm)
  return diff > 0 ? diff : 0
})

const durationText = computed(() => {
  if (durationMinutes.value === 0) return '—'
  const h = Math.floor(durationMinutes.value / 60)
  const m = durationMinutes.value % 60
  if (h > 0 && m > 0) return `${h} jam ${m} menit`
  if (h > 0) return `${h} jam`
  return `${m} menit`
})

const submitForm = () => {
  form.post('/bookings')
}
</script>
