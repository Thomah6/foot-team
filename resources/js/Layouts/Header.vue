<script setup>
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
  <header class="admin-header bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
    <div class="flex items-center">
      <button @click="$emit('toggleSidebar')" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 mr-4 transition-colors lg:hidden">
        <i class="fas fa-bars text-gray-700 dark:text-gray-300"></i>
      </button>
      <h1 class="page-title text-gray-800 dark:text-gray-100 text-xl font-bold">
        {{ $slots.title ? $slots.title() : 'Dashboard' }}
      </h1>
    </div>
    <div class="user-info">
      <div class="flex items-center gap-3 px-4 py-2 rounded-lg bg-gray-50 dark:bg-gray-700">
        <div class="w-8 h-8 rounded-full bg-citron-500 flex items-center justify-center">
          <i class="fas fa-user text-white text-sm"></i>
        </div>
        <span class="text-gray-700 dark:text-gray-300 font-medium">{{ user.name }}</span>
      </div>
    </div>
  </header>
</template>

<style scoped>
.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.5rem;
  background-color: #ffffff;
}
.dark .admin-header {
  background-color: #1f2937;
}
</style>
