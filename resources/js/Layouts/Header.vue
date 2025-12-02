<script setup>
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
  <header class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 px-4 sm:px-6 py-4 sm:py-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-16 sm:top-0 z-40">
    <div>
      <h1 class="text-xl sm:text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
        Dashboard
      </h1>
    </div>
    <div class="text-sm sm:text-base font-medium text-gray-700 dark:text-gray-300">
      <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900 text-emerald-800 dark:text-emerald-200">
        Bienvenue, {{ user.name }}
      </span>
    </div>
  </header>
</template>