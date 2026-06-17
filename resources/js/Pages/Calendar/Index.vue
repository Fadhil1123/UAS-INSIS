<template>
  <AuthenticatedLayout>
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-6 animate-fade-in-up stagger-1">
      <div class="relative">
        <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-500 blur-lg opacity-20 rounded-full"></div>
        <h1 class="relative text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
          Kalender Jadwal
          <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </span>
        </h1>
        <p class="relative text-slate-500 text-sm mt-1.5 font-medium">Pantau jadwal kuliah dan ketersediaan ruangan secara real-time.</p>
      </div>

      <!-- Room Filter Dropdown & Peminjaman Button -->
      <div class="flex items-center gap-3 relative z-20 w-full md:w-auto">
        <Link 
          v-if="userRole !== 'admin'"
          href="/bookings/create" 
          class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/10 transition-colors cursor-pointer"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
          </svg>
          Ajukan Peminjaman
        </Link>
        <div class="relative min-w-[240px] flex-1 md:flex-initial">
          <select 
            v-model="selectedRoom" 
            @change="fetchEvents"
            class="w-full appearance-none bg-white border border-slate-200/80 text-slate-700 font-semibold py-2.5 pl-4 pr-10 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer transition-all"
          >
            <option value="">Semua Ruangan</option>
            <option v-for="room in rooms" :key="room.id" :value="room.id">
              {{ room.room_name }} ({{ room.room_code }})
            </option>
          </select>
          <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar Container -->
    <div class="bg-white/80 backdrop-blur-xl rounded-3xl border border-slate-200/60 shadow-lg shadow-slate-200/40 p-2 sm:p-6 mb-8 animate-fade-in-up stagger-2 overflow-hidden">
      <!-- Loading Indicator -->
      <div v-if="isLoading" class="absolute inset-0 bg-white/70 backdrop-blur-sm z-30 flex items-center justify-center rounded-3xl">
        <div class="flex flex-col items-center">
          <div class="w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
          <p class="text-sm font-bold text-slate-600">Memuat Jadwal...</p>
        </div>
      </div>
      
      <!-- FullCalendar Component -->
      <FullCalendar class="fc-premium-theme" :options="calendarOptions" />
    </div>

    <!-- Event Detail Modal -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="selectedEvent" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="closeModal"></div>
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md relative z-10 overflow-hidden border border-slate-100">
          <!-- Modal Header based on Event Type/Color -->
          <div class="p-6 pb-4" :style="{ backgroundColor: selectedEvent.backgroundColor }">
            <h3 class="text-xl font-extrabold text-white pr-8">{{ selectedEvent.title }}</h3>
            <button @click="closeModal" class="absolute top-4 right-4 w-8 h-8 flex items-center justify-center rounded-full bg-white/20 hover:bg-white/30 text-white transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
          </div>
          
          <!-- Modal Body -->
          <div class="p-6 space-y-4">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
              <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu Pelaksanaan</p>
                <p class="text-sm font-semibold text-slate-800">{{ formatEventTime(selectedEvent.start, selectedEvent.end) }}</p>
              </div>
            </div>

            <!-- Optional additional info block here if you add more props from backend -->
          </div>
          
          <!-- Modal Footer -->
          <div class="p-4 bg-slate-50 border-t border-slate-100 text-right">
            <button @click="closeModal" class="px-5 py-2 bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-sm transition-colors shadow-sm">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </transition>

  </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// FullCalendar Imports
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'

const page = usePage()
const userRole = computed(() => page.props.auth?.user?.role || 'guest')
const rooms = page.props.rooms || []

const selectedRoom = ref('')
const events = ref([])
const isLoading = ref(false)
const selectedEvent = ref(null)

const calendarOptions = ref({
  plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
  initialView: 'dayGridMonth',
  headerToolbar: {
    left: 'prev,next today',
    center: 'title',
    right: 'dayGridMonth,timeGridWeek,timeGridDay'
  },
  events: events.value,
  eventClick: handleEventClick,
  height: 'auto',
  buttonText: {
    today: 'Hari Ini',
    month: 'Bulan',
    week: 'Minggu',
    day: 'Hari'
  },
  locale: 'id',
  firstDay: 1, // Start on Monday
  slotMinTime: '07:00:00', // Typical campus start
  slotMaxTime: '22:00:00',
  allDaySlot: false,
  eventTimeFormat: {
    hour: '2-digit',
    minute: '2-digit',
    meridiem: false,
    hour12: false
  }
})

const fetchEvents = async () => {
  isLoading.value = true
  try {
    const url = selectedRoom.value 
      ? `/calendar/events?room_id=${selectedRoom.value}`
      : '/calendar/events'
    
    const response = await fetch(url)
    const data = await response.json()
    
    // Update the reactive events array and the calendar options
    events.value = data
    calendarOptions.value.events = events.value
  } catch (error) {
    console.error('Failed to fetch events:', error)
  } finally {
    isLoading.value = false
  }
}

function handleEventClick(info) {
  info.jsEvent.preventDefault() // don't let the browser navigate
  selectedEvent.value = info.event
}

function closeModal() {
  selectedEvent.value = null
}

function formatEventTime(start, end) {
  if (!start) return ''
  const opt = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' }
  const startStr = start.toLocaleDateString('id-ID', opt)
  
  if (end) {
    const endOpt = { hour: '2-digit', minute: '2-digit' }
    const endStr = end.toLocaleTimeString('id-ID', endOpt)
    return `${startStr} s/d ${endStr}`
  }
  return startStr
}

onMounted(() => {
  fetchEvents()
})
</script>

<style>
/* FullCalendar Premium Theme Overrides */
.fc-premium-theme {
  font-family: inherit;
}
.fc-premium-theme .fc-toolbar-title {
  font-weight: 800;
  color: #0f172a;
  font-size: 1.5rem;
}
.fc-premium-theme .fc-button-primary {
  background-color: #f8fafc;
  border-color: #e2e8f0;
  color: #475569;
  font-weight: 700;
  text-transform: capitalize;
  border-radius: 0.5rem;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: all 0.2s;
}
.fc-premium-theme .fc-button-primary:not(:disabled):active,
.fc-premium-theme .fc-button-primary:not(:disabled).fc-button-active {
  background-color: #eff6ff;
  border-color: #bfdbfe;
  color: #1d4ed8;
}
.fc-premium-theme .fc-button-primary:hover {
  background-color: #f1f5f9;
  color: #334155;
}
.fc-premium-theme .fc-theme-standard td, 
.fc-premium-theme .fc-theme-standard th {
  border-color: #f1f5f9;
}
.fc-premium-theme .fc-col-header-cell-cushion {
  color: #64748b;
  font-weight: 700;
  text-transform: uppercase;
  font-size: 0.75rem;
  padding: 0.5rem;
}
.fc-premium-theme .fc-daygrid-day-number {
  color: #475569;
  font-weight: 600;
  padding: 0.5rem;
}
.fc-premium-theme .fc-day-today {
  background-color: #f0fdf4 !important;
}
.fc-premium-theme .fc-event {
  border: none;
  border-radius: 0.375rem;
  padding: 0.1rem 0.25rem;
  font-weight: 600;
  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
  transition: transform 0.2s;
  cursor: pointer;
}
.fc-premium-theme .fc-event:hover {
  transform: translateY(-1px) scale(1.02);
  z-index: 10;
  position: relative;
}
.fc-premium-theme .fc-timegrid-slot-label-cushion {
  color: #94a3b8;
  font-weight: 600;
  font-size: 0.75rem;
}
</style>
