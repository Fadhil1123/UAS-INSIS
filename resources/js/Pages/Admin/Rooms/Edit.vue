<template>
  <AuthenticatedLayout>
    <!-- Page Header -->
    <div class="mb-8">
      <div class="flex items-center gap-3 mb-1">
        <Link
          href="/admin/rooms"
          class="flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-indigo-600 transition-colors"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali ke Daftar Ruangan
        </Link>
      </div>
      <div class="flex items-center gap-3">
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Ruangan</h1>
        <span class="px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider bg-amber-50 text-amber-700 border border-amber-200 rounded-full">
          {{ room.room_code }}
        </span>
      </div>
      <p class="text-slate-500 text-sm mt-0.5">Perbarui informasi ruangan yang sudah ada.</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
      <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/60 shadow-xl p-8">

        <!-- Section Title -->
        <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
          <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
          <h2 class="text-sm font-bold text-slate-700">Informasi Ruangan</h2>
        </div>

        <form @submit.prevent="submit" class="space-y-5">

          <!-- Kode Ruangan -->
          <div>
            <label for="room_code" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
              Kode Ruangan <span class="text-rose-500">*</span>
            </label>
            <input
              id="room_code"
              v-model="form.room_code"
              type="text"
              placeholder="Contoh: A-101, LAB-01"
              :class="[
                'w-full px-4 py-3 rounded-xl border text-sm font-medium text-slate-800 placeholder-slate-300 transition-all duration-200 outline-none',
                errors.room_code
                  ? 'border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-2 focus:ring-rose-100'
                  : 'border-slate-200 bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100'
              ]"
            />
            <p v-if="errors.room_code" class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ errors.room_code }}
            </p>
          </div>

          <!-- Nama Ruangan -->
          <div>
            <label for="room_name" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
              Nama Ruangan <span class="text-rose-500">*</span>
            </label>
            <input
              id="room_name"
              v-model="form.room_name"
              type="text"
              placeholder="Contoh: Ruang Kelas A-101"
              :class="[
                'w-full px-4 py-3 rounded-xl border text-sm font-medium text-slate-800 placeholder-slate-300 transition-all duration-200 outline-none',
                errors.room_name
                  ? 'border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-2 focus:ring-rose-100'
                  : 'border-slate-200 bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100'
              ]"
            />
            <p v-if="errors.room_name" class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ errors.room_name }}
            </p>
          </div>

          <!-- Kategori & Kapasitas -->
          <div class="grid grid-cols-2 gap-4">
            <!-- Kategori -->
            <div>
              <label for="category" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Kategori <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <select
                  id="category"
                  v-model="form.category"
                  :class="[
                    'w-full px-4 py-3 rounded-xl border text-sm font-medium text-slate-800 transition-all duration-200 outline-none appearance-none pr-10 cursor-pointer',
                    errors.category
                      ? 'border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-2 focus:ring-rose-100'
                      : 'border-slate-200 bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100'
                  ]"
                >
                  <option value="" disabled>-- Pilih --</option>
                  <option value="kelas">🏫 Kelas</option>
                  <option value="laboratorium">🔬 Laboratorium</option>
                </select>
                <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                  <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                  </svg>
                </div>
              </div>
              <p v-if="errors.category" class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ errors.category }}
              </p>
            </div>

            <!-- Kapasitas -->
            <div>
              <label for="capacity" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
                Kapasitas <span class="text-rose-500">*</span>
              </label>
              <div class="relative">
                <input
                  id="capacity"
                  v-model="form.capacity"
                  type="number"
                  min="1"
                  placeholder="0"
                  :class="[
                    'w-full px-4 py-3 pr-16 rounded-xl border text-sm font-medium text-slate-800 placeholder-slate-300 transition-all duration-200 outline-none',
                    errors.capacity
                      ? 'border-rose-300 bg-rose-50/50 focus:border-rose-400 focus:ring-2 focus:ring-rose-100'
                      : 'border-slate-200 bg-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-100'
                  ]"
                />
                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">orang</span>
              </div>
              <p v-if="errors.capacity" class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ errors.capacity }}
              </p>
            </div>
          </div>

          <!-- Divider -->
          <div class="pt-2 border-t border-slate-100"></div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-3 pt-1">
            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex-1 flex items-center justify-center gap-2 py-3 bg-amber-500 hover:bg-amber-400 disabled:bg-amber-300 text-white font-bold rounded-xl text-sm shadow-md shadow-amber-500/20 transition-all duration-200 cursor-pointer disabled:cursor-not-allowed"
            >
              <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>

            <Link
              href="/admin/rooms"
              class="px-5 py-3 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-bold text-sm transition-all duration-200"
            >
              Batal
            </Link>
          </div>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  room: {
    type: Object,
    required: true,
  }
})

const form = reactive({
  room_code: props.room.room_code,
  room_name: props.room.room_name,
  category: props.room.category,
  capacity: props.room.capacity,
})

const errors = ref({})
const isSubmitting = ref(false)

function submit() {
  isSubmitting.value = true
  errors.value = {}

  router.put(`/admin/rooms/${props.room.id}`, form, {
    onError: (errs) => {
      errors.value = errs
      isSubmitting.value = false
    },
    onFinish: () => {
      isSubmitting.value = false
    },
  })
}
</script>
