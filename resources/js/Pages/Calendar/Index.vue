<template>
  <AuthenticatedLayout>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kalender Jadwal</h1>
        <p class="text-slate-500 text-sm mt-1">Pilih ruangan untuk melihat jadwal kuliah dan slot yang sedang terpakai.</p>
      </div>
      <Link href="/rooms" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/10 transition-colors">
        Lihat Ruangan
      </Link>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-1 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5">
        <h2 class="text-base font-bold text-slate-900 mb-4">Ruangan Aktif</h2>
        <div v-if="rooms && rooms.length > 0" class="space-y-3 max-h-[28rem] overflow-y-auto pr-1">
          <Link
            v-for="room in rooms"
            :key="room.id"
            :href="'/calendar?room_id=' + room.room_code"
            class="block rounded-2xl border px-4 py-3 transition-all"
            :class="selectedRoomCode === room.room_code ? 'border-indigo-200 bg-indigo-50/70' : 'border-slate-200 hover:border-indigo-200 hover:bg-slate-50'"
          >
            <div class="flex items-center justify-between gap-3">
              <div>
                <p class="font-bold text-slate-800">{{ room.room_name }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ room.room_code }} • {{ room.category || 'Ruangan' }}</p>
              </div>
              <span class="text-[10px] font-bold uppercase px-2 py-1 rounded-full" :class="selectedRoomCode === room.room_code ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500'">{{ room.capacity }} org</span>
            </div>
          </Link>
        </div>
        <div v-else class="text-sm text-slate-500">Belum ada ruangan aktif.</div>
      </div>

      <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-xs p-5">
        <div v-if="selectedRoom" class="mb-5">
          <p class="text-xs font-bold uppercase tracking-wider text-indigo-600">{{ selectedRoom.room_code }}</p>
          <h2 class="text-2xl font-bold text-slate-900 mt-1">{{ selectedRoom.room_name }}</h2>
          <p class="text-sm text-slate-500 mt-1">{{ selectedRoom.description || 'Tidak ada deskripsi ruangan.' }}</p>
        </div>

        <div v-if="schedules && schedules.length > 0" class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead class="text-left text-slate-400 uppercase text-xs">
              <tr>
                <th class="py-3 pr-4">Tanggal</th>
                <th class="py-3 pr-4">Waktu</th>
                <th class="py-3 pr-4">Mata Kuliah</th>
                <th class="py-3 pr-4">Dosen</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="schedule in schedules" :key="schedule.id" class="border-t border-slate-100">
                <td class="py-3 pr-4 font-medium text-slate-700">{{ formatDate(schedule.schedule_date) }}</td>
                <td class="py-3 pr-4 text-slate-600">{{ formatTime(schedule.start_time) }} - {{ formatTime(schedule.end_time) }}</td>
                <td class="py-3 pr-4 text-slate-700">{{ schedule.course_name || '-' }}</td>
                <td class="py-3 pr-4 text-slate-600">{{ schedule.lecturer_name || '-' }}</td>
              </tr>
            </tbody>
          </table>
        </div>

        <div v-else class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/60 p-8 text-center text-slate-500">
          <p class="font-semibold">Belum ada jadwal yang ditampilkan.</p>
          <p class="text-sm mt-1">Pilih ruangan dari daftar di sebelah kiri untuk melihat jadwalnya.</p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  rooms: {
    type: Array,
    default: () => []
  },
  selectedRoom: {
    type: Object,
    default: null
  },
  schedules: {
    type: Array,
    default: () => []
  },
  selectedRoomCode: {
    type: String,
    default: ''
  }
})

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  return new Date(dateStr).toLocaleDateString('id-ID', {
    weekday: 'short',
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  })
}

const formatTime = (timeStr) => {
  if (!timeStr) return '00:00'
  return timeStr.substring(0, 5)
}
</script>