<template>
  <AuthenticatedLayout>
    <!-- Header Section -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Daftar Ruangan</h1>
        <p class="text-slate-500 text-sm mt-1">Pilih ruangan yang tersedia untuk meninjau ketersediaan slot jadwal dan mengajukan peminjaman.</p>
      </div>
      <div v-if="userRole === 'admin'">
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

    <!-- Filters and Search Bar Section -->
    <div class="mb-8 flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs">
      <!-- Category Filter Tabs -->
      <div class="flex flex-wrap gap-1.5 w-full md:w-auto">
        <button 
          v-for="cat in ['semua', 'kelas', 'laboratorium',]" 
          :key="cat"
          @click="selectedCategory = cat"
          :class="[
            'px-4 py-2 text-xs font-bold rounded-xl transition-all capitalize cursor-pointer',
            selectedCategory === cat
              ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/10'
              : 'text-slate-600 hover:bg-slate-50'
          ]"
        >
          {{ cat }}
        </button>
      </div>

      <!-- Real-time Search Input -->
      <div class="relative w-full md:w-80">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input 
          type="text" 
          v-model="searchQuery"
          placeholder="Cari kode atau nama ruangan..."
          class="block w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:bg-white rounded-xl text-xs text-slate-700 transition-all font-medium"
        />
      </div>
    </div>

    <!-- Room List Cards Grid -->
    <div v-if="filteredRooms && filteredRooms.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div 
        v-for="room in filteredRooms" 
        :key="room.id"
        class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col justify-between group hover:shadow-md hover:border-indigo-200 transition-all duration-300"
      >
        <!-- Room Media -->
        <div class="aspect-video bg-slate-100 relative overflow-hidden">
          <img 
            v-if="room.photo_path" 
            :src="'/' + room.photo_path" 
            :alt="room.room_name" 
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
          />
          <div v-else class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-100 to-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
            </svg>
          </div>

          <!-- Room Status Tag -->
          <div class="absolute top-4 right-4">
            <span 
              :class="[
                'px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full shadow-xs border',
                room.status === 'aktif' || room.status === 1 || room.status === 'active'
                  ? 'bg-emerald-500/90 text-white border-emerald-400' 
                  : 'bg-rose-500/90 text-white border-rose-400'
              ]"
            >
              {{ room.status === 'aktif' || room.status === 1 || room.status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>
          </div>
          
          <!-- Category Tag -->
          <div class="absolute bottom-4 left-4">
            <span class="px-2.5 py-1 text-xs font-bold text-white bg-slate-900/60 backdrop-blur-md rounded-lg uppercase">
              {{ room.category || 'Ruangan' }}
            </span>
          </div>
        </div>

        <!-- Room Information -->
        <div class="p-6 flex-1 flex flex-col justify-between">
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-xs font-bold text-indigo-600 bg-indigo-50 border border-indigo-100/50 px-2 py-0.5 rounded-md uppercase tracking-wider">{{ room.room_code }}</span>
              <div class="flex items-center gap-1 text-slate-500 font-semibold text-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Kapasitas: {{ room.capacity }}
              </div>
            </div>
            <h3 class="text-lg font-bold text-slate-800 line-clamp-1 group-hover:text-indigo-600 transition-colors">{{ room.room_name }}</h3>
            <p class="text-slate-400 text-xs mt-2 line-clamp-2 leading-relaxed">
              {{ room.description || 'Ruangan perkuliahan dan laboratorium praktikum terintegrasi program studi Teknologi Informasi.' }}
            </p>
          </div>

          <!-- Action Buttons -->
          <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between gap-3">
            <button 
              @click="openDetailModal(room)"
              class="flex-1 px-3 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 font-bold rounded-xl text-xs transition-all border border-slate-200/50 hover:border-slate-300 flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap"
            >
              Detail Ruangan
            </button>
            <Link 
              v-if="userRole !== 'admin'"
              :href="'/calendar?room_id=' + room.room_code"
              class="flex-1 px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs shadow-md shadow-indigo-600/10 hover:shadow-lg transition-all flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap"
            >
              Pilih & Cek Jadwal
            </Link>
            <div v-else-if="userRole === 'admin'" class="flex items-center gap-2">
              <!-- Toggle Status -->
              <button
                @click="toggleStatus(room)"
                :class="[
                  'p-2 rounded-xl border transition-all cursor-pointer',
                  room.status === 'aktif'
                    ? 'bg-emerald-50 hover:bg-emerald-100 border-emerald-200/50 hover:border-emerald-300 text-emerald-700'
                    : 'bg-slate-50 hover:bg-slate-100 border-slate-200/50 hover:border-slate-300 text-slate-500'
                ]"
                :title="room.status === 'aktif' ? 'Nonaktifkan Ruangan' : 'Aktifkan Ruangan'"
              >
                <svg v-if="room.status === 'aktif'" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                </svg>
              </button>

              <!-- Edit -->
              <Link
                :href="'/admin/rooms/' + room.id + '/edit'"
                class="p-2 bg-amber-50 hover:bg-amber-100 border border-amber-200/50 hover:border-amber-300 text-amber-700 rounded-xl transition-all cursor-pointer"
                title="Edit Ruangan"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
              </Link>

              <!-- Delete -->
              <button
                @click="openDeleteModal(room)"
                class="p-2 bg-rose-50 hover:bg-rose-100 border border-rose-200/50 hover:border-rose-300 text-rose-700 rounded-xl transition-all cursor-pointer"
                title="Hapus Ruangan"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200/80 shadow-xs">
      <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mx-auto mb-4 border border-slate-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
        </svg>
      </div>
      <h3 class="text-lg font-bold text-slate-800">Tidak ada data ruangan</h3>
      <p class="text-slate-400 text-sm mt-1 max-w-sm mx-auto">Tidak ditemukan ruangan yang cocok dengan kriteria pencarian atau filter Anda.</p>
    </div>

    <!-- Detail Modal -->
    <transition
      enter-active-class="ease-out duration-300 transition"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="ease-in duration-200 transition"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="detailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <!-- Backdrop -->
        <div @click="closeDetailModal" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        
        <!-- Modal Content Card -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-2xl w-full max-w-2xl overflow-hidden z-10 flex flex-col relative max-h-[90vh]">
          <!-- Close Button -->
          <button @click="closeDetailModal" class="absolute top-4 right-4 z-20 w-8 h-8 bg-slate-900/20 hover:bg-slate-900/40 text-white rounded-full flex items-center justify-center transition-colors focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Banner Image -->
          <div class="h-64 bg-slate-100 relative">
            <img 
              v-if="selectedRoom.photo_path" 
              :src="'/' + selectedRoom.photo_path" 
              :alt="selectedRoom.room_name" 
              class="w-full h-full object-cover"
            />
            <div v-else class="w-full h-full flex items-center justify-center text-slate-400 bg-gradient-to-br from-slate-100 to-slate-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
              </svg>
            </div>
            
            <div class="absolute bottom-4 left-6 flex items-center gap-3">
              <span class="px-2.5 py-1 text-xs font-bold text-white bg-slate-900/60 backdrop-blur-md rounded-lg uppercase">
                {{ selectedRoom.category || 'Ruangan' }}
              </span>
              <span 
                :class="[
                  'px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-full shadow-xs border',
                  selectedRoom.status === 'aktif' || selectedRoom.status === 1 || selectedRoom.status === 'active'
                    ? 'bg-emerald-500/90 text-white border-emerald-400' 
                    : 'bg-rose-500/90 text-white border-rose-400'
                ]"
              >
                {{ selectedRoom.status === 'aktif' || selectedRoom.status === 1 || selectedRoom.status === 'active' ? 'Aktif' : 'Nonaktif' }}
              </span>
            </div>
          </div>

          <!-- Body Info -->
          <div class="p-6 overflow-y-auto space-y-6">
            <div>
              <div class="flex items-center gap-2 text-indigo-600 font-extrabold text-xs tracking-wider uppercase mb-1">
                <span>{{ selectedRoom.room_code }}</span>
                <span>•</span>
                <span>Kapasitas: {{ selectedRoom.capacity }} Orang</span>
              </div>
              <h2 class="text-2xl font-bold text-slate-900">{{ selectedRoom.room_name }}</h2>
            </div>

            <div>
              <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Deskripsi</h4>
              <p class="text-sm text-slate-600 leading-relaxed">
                {{ selectedRoom.description || 'Ruangan perkuliahan dan laboratorium praktikum terintegrasi program studi Teknologi Informasi. Dilengkapi pendingin ruangan (AC), proyektor multimedia, papan tulis, serta jaringan internet berkecepatan tinggi.' }}
              </p>
            </div>

            <!-- Facilities Grid (Dynamic) -->
            <div>
              <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Fasilitas & Kelengkapan</h4>
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div 
                  v-for="facility in getFacilities(selectedRoom.category)" 
                  :key="facility"
                  class="flex items-center gap-2.5 p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs font-semibold text-slate-700"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                  </svg>
                  {{ facility }}
                </div>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="p-6 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-3">
            <button @click="closeDetailModal" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-50 transition-colors focus:outline-none">
              Tutup
            </button>
            <Link 
              v-if="userRole !== 'admin'"
              :href="'/calendar?room_id=' + selectedRoom.room_code"
              class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/10 transition-colors"
            >
              Cek Slot Kalender
            </Link>
          </div>
        </div>
      </div>
    </transition>

    <!-- Delete Confirmation Modal -->
    <transition
      enter-active-class="ease-out duration-200 transition"
      enter-from-class="opacity-0 scale-95"
      enter-to-class="opacity-100 scale-100"
      leave-active-class="ease-in duration-150 transition"
      leave-from-class="opacity-100 scale-100"
      leave-to-class="opacity-0 scale-95"
    >
      <div v-if="deleteConfirmOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="deleteConfirmOpen = false" class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs"></div>
        <div class="bg-white rounded-3xl border border-slate-200 shadow-2xl w-full max-w-sm overflow-hidden z-10 p-6">
          <div class="w-14 h-14 rounded-2xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
          </div>
          <h3 class="text-lg font-bold text-slate-900 text-center mb-1">Hapus Ruangan?</h3>
          <p class="text-sm text-slate-500 text-center mb-1">Kamu akan menghapus ruangan:</p>
          <p class="text-sm font-bold text-slate-800 text-center mb-2">{{ roomToDelete?.room_name }}</p>
          <p class="text-xs text-slate-400 text-center mb-6 leading-relaxed">Tindakan ini tidak dapat dibatalkan. Semua data ruangan akan dihapus permanen.</p>
          <div class="flex gap-3">
            <button
              @click="deleteConfirmOpen = false"
              class="flex-1 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-bold text-sm transition-colors cursor-pointer"
            >
              Batal
            </button>
            <button
              @click="confirmDelete"
              :disabled="isDeleting"
              class="flex-1 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 disabled:bg-rose-300 text-white font-bold text-sm transition-colors flex items-center justify-center gap-2 cursor-pointer"
            >
              <svg v-if="isDeleting" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              {{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}
            </button>
          </div>
        </div>
      </div>
    </transition>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  rooms: {
    type: Array,
    default: () => []
  }
})

const page = usePage()
const userRole = computed(() => page.props.auth?.user?.role || 'guest')

// Filter & Search State
const searchQuery = ref('')
const selectedCategory = ref('semua')

// Real-time Search & Filter logic
const filteredRooms = computed(() => {
  return props.rooms.filter(room => {
    const matchesSearch = room.room_name.toLowerCase().includes(searchQuery.value.toLowerCase()) || 
                          room.room_code.toLowerCase().includes(searchQuery.value.toLowerCase());
    const matchesCategory = selectedCategory.value === 'semua' || room.category === selectedCategory.value;
    return matchesSearch && matchesCategory;
  });
});

// Dynamic Facilities generator based on category
const getFacilities = (category) => {
  const common = [
    'Air Conditioner (AC)',
    'Koneksi Wi-Fi Cepat',
  ];

  if (category === 'kelas') {
    return [
      ...common,
      'Proyektor Multimedia & Layar',
      'Papan Tulis Whiteboard & Spidol',
    ];
  } else if (category === 'laboratorium') {
    return [
      ...common,
      '30 Unit PC Client Spesifikasi Tinggi',
      'Jaringan LAN Gigabit & Switch',
      'Proyektor Layar Lebar',
    ];
  } else if (category === 'aula') {
    return [
      ...common,
      'Sound System & Mic Wireless',
      'Panggung, Podium & Mimbar',
      'Kursi Lipat Tambahan (Hingga 100 unit)',
    ];
  }
  return common;
}

// Detail Modal State
const detailModalOpen = ref(false)
const selectedRoom = ref({})

const openDetailModal = (room) => {
  selectedRoom.value = room
  detailModalOpen.value = true
}

const closeDetailModal = () => {
  detailModalOpen.value = false
}

// Delete Modal State
const deleteConfirmOpen = ref(false)
const roomToDelete = ref(null)
const isDeleting = ref(false)

const openDeleteModal = (room) => {
  roomToDelete.value = room
  deleteConfirmOpen.value = true
}

const confirmDelete = () => {
  if (!roomToDelete.value) return
  isDeleting.value = true
  router.delete(`/admin/rooms/${roomToDelete.value.id}`, {
    onFinish: () => {
      isDeleting.value = false
      deleteConfirmOpen.value = false
      roomToDelete.value = null
    }
  })
}

// Toggle Status
const toggleStatus = (room) => {
  router.patch(`/admin/rooms/${room.id}/toggle-status`, {}, {
    preserveScroll: true,
  })
}
</script>
