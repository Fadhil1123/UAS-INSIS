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
      <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Tambah Ruangan Baru</h1>
      <p class="text-slate-500 text-sm mt-0.5">Isi data ruangan yang akan ditambahkan ke sistem.</p>
    </div>

    <!-- Form Card -->
    <div class="max-w-2xl">
      <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/60 shadow-xl p-8">
        <!-- Section Title -->
        <div class="flex items-center gap-2.5 mb-6 pb-4 border-b border-slate-100">
          <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5" />
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
          <div class="pt-1 border-t border-slate-100"></div>

          <!-- Upload Foto -->
          <div>
            <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-1.5">
              Foto Ruangan <span class="text-slate-400 font-normal">(Opsional)</span>
            </label>
            <!-- Preview -->
            <div v-if="photoPreview" class="mb-3 relative">
              <img :src="photoPreview" alt="Preview Foto" class="w-full h-44 object-cover rounded-xl border border-slate-200" />
              <button
                type="button"
                @click="removePhoto"
                class="absolute top-2 right-2 w-7 h-7 bg-slate-900/60 hover:bg-slate-900/80 text-white rounded-full flex items-center justify-center transition-colors"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
            <!-- Dropzone -->
            <label class="flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer bg-slate-50/50 hover:bg-slate-100/50 hover:border-indigo-300 transition-all duration-200">
              <div class="flex flex-col items-center justify-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <p class="text-xs font-semibold text-slate-500">Klik untuk pilih foto</p>
                <p class="text-[10px] text-slate-400">JPG, PNG, WEBP — Maks. 2MB</p>
              </div>
              <input type="file" class="hidden" accept="image/jpeg,image/png,image/webp" @change="handlePhotoChange" />
            </label>
            <p v-if="errors.photo" class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{ errors.photo }}
            </p>
          </div>

          <!-- Divider -->
          <div class="pt-1 border-t border-slate-100"></div>

          <!-- Action Buttons -->
          <div class="flex items-center gap-3 pt-1">
            <button
              type="submit"
              :disabled="isSubmitting"
              class="flex-1 flex items-center justify-center gap-2 py-3 bg-indigo-600 hover:bg-indigo-500 disabled:bg-indigo-300 text-white font-bold rounded-xl text-sm shadow-md shadow-indigo-600/20 transition-all duration-200 cursor-pointer disabled:cursor-not-allowed"
            >
              <svg v-if="isSubmitting" class="w-4 h-4 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
              </svg>
              {{ isSubmitting ? 'Menyimpan...' : 'Simpan Ruangan' }}
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

const form = reactive({
  room_code: '',
  room_name: '',
  category: '',
  capacity: '',
  photo: null,
})

const errors = ref({})
const isSubmitting = ref(false)
const photoPreview = ref(null)

const handlePhotoChange = (event) => {
  const file = event.target.files[0]
  if (!file) return
  form.photo = file
  const reader = new FileReader()
  reader.onload = (e) => { photoPreview.value = e.target.result }
  reader.readAsDataURL(file)
}

const removePhoto = () => {
  form.photo = null
  photoPreview.value = null
}

function submit() {
  isSubmitting.value = true
  errors.value = {}

  const data = new FormData()
  data.append('room_code', form.room_code)
  data.append('room_name', form.room_name)
  data.append('category', form.category)
  data.append('capacity', form.capacity)
  if (form.photo) data.append('photo', form.photo)

  router.post('/admin/rooms', data, {
    forceFormData: true,
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
