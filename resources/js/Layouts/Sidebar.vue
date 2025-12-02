<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Liste de tous les liens possibles dans l'application
const navLinks = [
  { name: 'Dashboard', href: '/admin/dashboard', permission: 'view dashboard', icon: '📊' },
  { name: 'Utilisateurs', href: '/admin/users', permission: 'view users', icon: '👥' },
  { name: 'Articles', href: '/admin/articles', permission: 'edit articles', icon: '📰' },
  { name: 'Classement', href: '/classements', permission: 'view dashboard', icon: '🏆' },
  { name: 'Paramètres', href: '/admin/settings', permission: 'edit settings', icon: '⚙️' },
];

const userPermissions = usePage().props.auth.permissions;

// On filtre les liens pour ne garder que ceux que l'utilisateur a la permission de voir
const filteredLinks = computed(() => {
  return navLinks.filter(link => userPermissions.includes(link.permission));
});
</script>

<template>
  <aside class="hidden lg:flex flex-col w-64 bg-gradient-to-b from-gray-900 to-gray-800 dark:from-gray-950 dark:to-gray-900 text-white flex-shrink-0">
    <!-- Logo -->
    <div class="px-4 sm:px-6 py-6 border-b border-gray-700 bg-gradient-to-r from-emerald-600 to-lime-600 flex items-center justify-center">
      <span class="text-lg sm:text-2xl font-black text-white">⚽ App</span>
    </div>
    
    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto">
      <ul class="space-y-2 p-4 sm:p-6">
        <li v-for="link in filteredLinks" :key="link.href">
          <Link
            :href="link.href"
            class="flex items-center gap-3 px-3 sm:px-4 py-3 sm:py-2.5 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-gray-700 dark:hover:bg-gray-600"
            :class="{
              'bg-gradient-to-r from-emerald-600 to-lime-600 text-white shadow-lg': $page.url.startsWith(link.href),
              'text-gray-300 hover:text-white': !$page.url.startsWith(link.href),
            }"
          >
            <span class="text-lg">{{ link.icon }}</span>
            <span class="flex-1">{{ link.name }}</span>
            <span v-if="$page.url.startsWith(link.href)" class="ml-2 text-emerald-200">→</span>
          </Link>
        </li>
      </ul>
    </nav>
  </aside>
</template>