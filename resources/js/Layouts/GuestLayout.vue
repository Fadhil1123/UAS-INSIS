<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 text-slate-100 selection:bg-indigo-500 selection:text-white">
    <!-- Background subtle lights -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
      <div class="absolute -top-40 -left-40 w-96 h-96 bg-indigo-500/10 rounded-full blur-3xl"></div>
      <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Header Logo/Branding -->
    <div class="z-10 text-center mb-8">
      <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-indigo-600/20 border border-indigo-500/30 shadow-xl shadow-indigo-500/10 mb-4 backdrop-blur-md">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
          <line x1="9" y1="3" x2="9" y2="21"></line>
          <line x1="15" y1="3" x2="15" y2="21"></line>
          <line x1="3" y1="9" x2="21" y2="9"></line>
          <line x1="3" y1="15" x2="21" y2="15"></line>
        </svg>
      </div>
      <h1 class="text-3xl font-extrabold tracking-tight bg-gradient-to-r from-indigo-200 via-white to-purple-200 bg-clip-text text-transparent">
        SIMARI ROOMS
      </h1>
      <p class="text-xs text-slate-400 mt-2 font-medium tracking-widest uppercase">
        Sistem Peminjaman Ruangan Terintegrasi
      </p>
    </div>

    <!-- Main Card -->
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-slate-900/60 border border-slate-800/80 backdrop-blur-xl shadow-2xl rounded-3xl z-10 transition-all duration-300">
      <slot />
    </div>

    <!-- Footer -->
    <div class="mt-8 text-center text-xs text-slate-500 z-10 font-medium">
      &copy; {{ new Date().getFullYear() }} TI Universitas Lambung Mangkurat. All rights reserved.
    </div>
  </div>
</template>

<script setup>
// Guest Layout
</script>
