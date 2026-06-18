<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col font-sans">
    <!-- Top Navbar -->
    <header class="bg-white border-b border-slate-200/80 sticky top-0 z-40 backdrop-blur-md bg-white/95">
      <div class="px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Brand & Hamburger -->
        <div class="flex items-center gap-4">
          <button 
            @click="sidebarOpen = !sidebarOpen" 
            class="p-2 rounded-xl text-slate-500 hover:bg-slate-100 hover:text-slate-900 transition-colors lg:hidden focus:outline-none"
            aria-label="Toggle Sidebar"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>
          
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-md shadow-indigo-600/10">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="9" y1="3" x2="9" y2="21"></line>
                <line x1="3" y1="9" x2="21" y2="9"></line>
              </svg>
            </div>
            <div>
              <span class="font-extrabold text-lg tracking-tight bg-gradient-to-r from-slate-950 to-slate-700 bg-clip-text text-transparent">
                SIJAMAL
              </span>
              <span class="hidden sm:inline-block ml-2 px-2 py-0.5 text-[10px] font-bold tracking-wider uppercase bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                PRODI TI
              </span>
            </div>
          </div>
        </div>

        <!-- User Actions -->
        <div class="flex items-center gap-4">
          <!-- User Menu Dropdown -->
          <div class="relative">
            <button 
              @click="profileDropdownOpen = !profileDropdownOpen" 
              class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl hover:bg-slate-50 transition-all focus:outline-none border border-transparent hover:border-slate-200/50"
            >
              <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white font-bold flex items-center justify-center shadow-sm">
                {{ userInitials }}
              </div>
              <div class="hidden md:block text-left">
                <p class="text-xs font-semibold text-slate-700 leading-3">{{ userName }}</p>
                <span class="text-[10px] text-slate-400 font-medium capitalize">{{ userRole }}</span>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 hidden md:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Dropdown Menu -->
            <transition
              enter-active-class="transition ease-out duration-100"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div v-if="profileDropdownOpen" class="absolute right-0 mt-2 w-56 rounded-2xl bg-white border border-slate-200/80 shadow-xl py-2 z-50">
                <div class="px-4 py-3 border-b border-slate-100 md:hidden">
                  <p class="text-sm font-semibold text-slate-800">{{ userName }}</p>
                  <p class="text-xs text-slate-500 font-medium capitalize">{{ userRole }}</p>
                </div>
                <div class="px-4 py-2 border-b border-slate-100">
                  <p class="text-[10px] text-slate-400 font-semibold tracking-wider uppercase">Identitas</p>
                  <p class="text-xs font-bold text-slate-700 mt-0.5">{{ userNomorInduk }}</p>
                </div>
                <button 
                  @click="logout" 
                  class="w-full text-left px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50/50 flex items-center gap-2 transition-colors font-medium mt-1"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  Keluar Sistem
                </button>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </header>

    <div class="flex-1 flex relative">
      <!-- Backdrop for mobile sidebar -->
      <div 
        v-if="sidebarOpen" 
        @click="sidebarOpen = false" 
        class="fixed inset-0 bg-slate-900/40 z-30 lg:hidden backdrop-blur-xs transition-opacity duration-300"
      ></div>

      <!-- Sidebar -->
      <aside 
        :class="[
          'fixed inset-y-0 left-0 w-64 bg-white border-r border-slate-200/80 pt-20 lg:pt-0 z-30 transform lg:transform-none lg:static transition-transform duration-300 ease-in-out flex flex-col',
          sidebarOpen ? 'translate-x-0' : '-translate-x-0 lg:translate-x-0'
        ]"
      >
        <!-- Nav Links Section -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
          <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase px-3 mb-2">Main Menu</p>
          
          <Link 
            v-for="item in menuItems" 
            :key="item.name" 
            :href="item.href"
            :class="[
              'flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group',
              isCurrentUrl(item.href)
                ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' 
                : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
            ]"
            @click="sidebarOpen = false"
          >
            <component 
              :is="item.icon" 
              :class="[
                'w-5 h-5 transition-transform duration-300 group-hover:scale-110',
                isCurrentUrl(item.href) ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'
              ]" 
            />
            {{ item.name }}
          </Link>

          <!-- Admin Section -->
          <div v-if="userRole === 'admin'" class="pt-6 mt-6 border-t border-slate-100 space-y-1.5">
            <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase px-3 mb-2">Administrator</p>
            
            <Link 
              v-for="item in adminItems" 
              :key="item.name" 
              :href="item.href"
              :class="[
                'flex items-center gap-3 px-3.5 py-3 rounded-xl text-sm font-semibold transition-all duration-200 group',
                isCurrentUrl(item.href)
                  ? 'bg-indigo-50 text-indigo-700 shadow-sm border border-indigo-100/50' 
                  : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'
              ]"
              @click="sidebarOpen = false"
            >
              <component 
                :is="item.icon" 
                :class="[
                  'w-5 h-5 transition-transform duration-300 group-hover:scale-110',
                  isCurrentUrl(item.href) ? 'text-indigo-600' : 'text-slate-400 group-hover:text-slate-600'
                ]" 
              />
              {{ item.name }}
            </Link>
          </div>
        </nav>

        <!-- Sidebar User Card Footer -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-600 font-bold text-sm">
              {{ userInitials }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-xs font-bold text-slate-800 truncate">{{ userName }}</p>
              <p class="text-[10px] text-slate-400 font-semibold truncate">{{ userNomorInduk }}</p>
            </div>
          </div>
        </div>
      </aside>

      <!-- Main Content Area -->
      <main class="flex-1 min-w-0 flex flex-col">
        <!-- Content Container -->
        <div class="flex-1 p-4 sm:p-6 lg:p-8">
          <!-- Success Pop-up Modal -->
          <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
          >
            <div v-if="showSuccessAlert" class="fixed inset-0 z-50 flex items-center justify-center p-4">
              <!-- Backdrop -->
              <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showSuccessAlert = false"></div>
              
              <!-- Modal Card -->
              <div class="bg-white rounded-3xl shadow-2xl w-full max-w-sm relative z-10 overflow-hidden border border-emerald-100/50">
                <!-- Header/Icon -->
                <div class="p-8 pb-4 text-center">
                  <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-4 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <h3 class="text-lg font-extrabold text-slate-900">Pengajuan Berhasil</h3>
                  <p class="text-xs text-slate-500 font-semibold mt-1.5 leading-relaxed">{{ $page.props.flash?.success }}</p>
                </div>
                
                <!-- Footer -->
                <div class="p-6 text-center">
                  <button @click="showSuccessAlert = false" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-sm transition-colors shadow-md shadow-emerald-600/15 cursor-pointer">
                    Selesai & Tutup
                  </button>
                </div>
              </div>
            </div>
          </transition>

          <transition 
            enter-active-class="transform ease-out duration-300 transition"
            enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
            enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
            leave-active-class="transition ease-in duration-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
          >
            <div v-if="$page.props.flash && $page.props.flash.error" class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl flex items-center gap-3 shadow-xs">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-sm font-medium">{{ $page.props.flash.error }}</span>
            </div>
          </transition>

          <slot />
        </div>

        <!-- Page Footer -->
        <footer class="bg-white border-t border-slate-200/80 py-4 px-6 text-center text-xs text-slate-400 font-medium">
          &copy; {{ new Date().getFullYear() }} Program Studi Teknologi Informasi - Universitas Lambung Mangkurat. All rights reserved.
        </footer>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'

// Icons Components (inline svg functions)
import DashboardIcon from '@/Components/Icons/DashboardIcon.vue'
import RoomIcon from '@/Components/Icons/RoomIcon.vue'
import CalendarIcon from '@/Components/Icons/CalendarIcon.vue'
import HistoryIcon from '@/Components/Icons/HistoryIcon.vue'
import ApprovalIcon from '@/Components/Icons/ApprovalIcon.vue'

const page = usePage()

const sidebarOpen = ref(false)
const profileDropdownOpen = ref(false)

const showSuccessAlert = ref(false)

// Watch for flash success message
watch(
  () => page.props.flash?.success,
  (val) => {
    console.log('Flash success triggered:', val)
    if (val) {
      showSuccessAlert.value = true
    }
  },
  { immediate: true }
)

const userName = computed(() => page.props.auth?.user?.name || 'Guest')
const userRole = computed(() => page.props.auth?.user?.role || 'guest')
const userNomorInduk = computed(() => page.props.auth?.user?.nomor_induk || '-')

const userInitials = computed(() => {
  if (!userName.value) return 'U'
  return userName.value
    .split(' ')
    .map(word => word[0])
    .join('')
    .substring(0, 2)
    .toUpperCase()
})

// Menu Items based on user roles
const menuItems = computed(() => {
  const base = [
    {
      name: 'Dashboard',
      href: userRole.value === 'admin' ? '/admin/dashboard' : '/dashboard',
      icon: DashboardIcon
    },
    {
      name: 'Daftar Ruangan',
      href: '/rooms',
      icon: RoomIcon
    },
    {
      name: 'Kalender Jadwal',
      href: '/calendar',
      icon: CalendarIcon
    }
  ]

  // Add booking history and request form for mahasiswa/dosen
  if (userRole.value !== 'admin') {
    base.push({
      name: 'Riwayat Booking',
      href: '/bookings',
      icon: HistoryIcon
    })
  }

  return base
})

const adminItems = [
  {
    name: 'Persetujuan Peminjaman',
    href: '/admin/bookings',
    icon: ApprovalIcon
  },
  {
    name: 'Kelola Ruangan',
    href: '/admin/rooms',
    icon: RoomIcon
  }
]

const isCurrentUrl = (href) => {
  const url = page.url
  if (href === '/admin/dashboard') {
    return url === '/admin/dashboard'
  }
  if (href === '/dashboard') {
    return url === '/dashboard'
  }
  return url.startsWith(href)
}

const logout = () => {
  router.post('/logout')
}
</script>
