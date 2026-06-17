<template>
  <GuestLayout>
    <div class="mb-6">
      <h2 class="text-2xl font-bold text-white tracking-tight">Selamat Datang Kembali</h2>
      <p class="text-slate-400 text-sm mt-1">Silakan masuk menggunakan nomor induk (NIM/NIP) Anda.</p>
    </div>

    <form @submit.prevent="submit" class="space-y-5">
      <!-- NIM / NIP Input -->
      <div>
        <label for="nomor_induk" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
          Nomor Induk (NIM / NIP)
        </label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
          <input
            id="nomor_induk"
            type="text"
            v-model="form.nomor_induk"
            required
            autofocus
            placeholder="Masukkan NIM atau NIP"
            class="block w-full pl-11 pr-4 py-3 bg-slate-950/50 border border-slate-800/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-slate-200 placeholder-slate-600 transition-all text-sm"
          />
        </div>
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
        >
          <span v-if="form.errors.nomor_induk" class="text-xs text-rose-500 font-medium mt-1.5 block">
            {{ form.errors.nomor_induk }}
          </span>
        </transition>
      </div>

      <!-- Password Input -->
      <div>
        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
          Password
        </label>
        <div class="relative">
          <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
          </div>
          <input
            id="password"
            :type="showPassword ? 'text' : 'password'"
            v-model="form.password"
            required
            placeholder="••••••••"
            class="block w-full pl-11 pr-12 py-3 bg-slate-950/50 border border-slate-800/80 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-slate-200 placeholder-slate-600 transition-all text-sm"
          />
          <!-- Show/Hide Password Button -->
          <button
            type="button"
            @click="showPassword = !showPassword"
            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-500 hover:text-slate-300 transition-colors focus:outline-none"
          >
            <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
          </button>
        </div>
        <transition
          enter-active-class="transition ease-out duration-200"
          enter-from-class="opacity-0 -translate-y-1"
          enter-to-class="opacity-100 translate-y-0"
        >
          <span v-if="form.errors.password" class="text-xs text-rose-500 font-medium mt-1.5 block">
            {{ form.errors.password }}
          </span>
        </transition>
      </div>

      <!-- General Errors (e.g. from backend) -->
      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-1"
        enter-to-class="opacity-100 translate-y-0"
      >
        <div v-if="form.errors.email" class="p-3 bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold rounded-xl flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5 text-rose-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          {{ form.errors.email }}
        </div>
      </transition>

      <!-- Submit Button -->
      <button
        type="submit"
        :disabled="form.processing"
        class="w-full py-3.5 px-4 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 disabled:opacity-50 text-white font-bold rounded-xl shadow-lg shadow-indigo-600/20 transition-all focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 text-sm flex items-center justify-center gap-2 cursor-pointer"
      >
        <span v-if="form.processing" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <span v-else>Masuk ke Akun</span>
      </button>
    </form>
  </GuestLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import GuestLayout from '@/Layouts/GuestLayout.vue'

const showPassword = ref(false)

const form = useForm({
  nomor_induk: '',
  password: ''
})

const submit = () => {
  // Clear previous errors
  form.clearErrors()

  // Basic client-side validation
  if (form.nomor_induk.trim().length < 5) {
    form.setError('nomor_induk', 'Nomor induk (NIM/NIP) minimal 5 karakter.')
    return
  }

  if (form.password.length < 8) {
    form.setError('password', 'Password minimal 8 karakter.')
    return
  }

  form.post('/login', {
    onFinish: () => form.reset('password'),
  })
}
</script>
