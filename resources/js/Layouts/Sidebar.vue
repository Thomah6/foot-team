<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Liste de tous les liens possibles dans l'application
const navLinks = [
  { name: 'Dashboard', href: '/admin/dashboard', permission: 'view dashboard', icon: 'fas fa-tachometer-alt' },
  { name: 'Utilisateurs', href: '/admin/users', permission: 'view users', icon: 'fas fa-users' },
  { name: 'Articles', href: '/admin/articles', permission: 'edit articles', icon: 'fas fa-newspaper' },
  { name: 'Classement', href: '/classements', permission: 'view dashboard', icon: 'fas fa-trophy' },
  { name: 'Paramètres', href: '/admin/settings', permission: 'edit settings', icon: 'fas fa-cog' },
];

const userPermissions = usePage().props.auth.permissions;

// On filtre les liens pour ne garder que ceux que l'utilisateur a la permission de voir
const filteredLinks = computed(() => {
  return navLinks.filter(link => userPermissions.includes(link.permission));
});

const user = usePage().props.auth.user;
</script>

<template>
  <aside class="sidebar bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700">
    <div class="logo p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-citron-500 flex items-center justify-center">
          <i class="fas fa-home text-white"></i>
        </div>
        <span class="text-lg font-bold text-gray-800 dark:text-gray-100">Mon App</span>
      </div>
    </div>
    
    <div class="user-info p-4 border-b border-gray-200 dark:border-gray-700">
      <div class="flex items-center gap-3">
        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-citron-400 to-green-500 flex items-center justify-center">
          <i class="fas fa-user text-white text-sm"></i>
        </div>
        <div>
          <p class="text-sm font-medium text-gray-800 dark:text-gray-100">{{ user.name }}</p>
          <p class="text-xs text-gray-500 dark:text-gray-400">{{ user.email }}</p>
        </div>
      </div>
    </div>
    
    <nav class="navigation p-2">
      <ul class="space-y-1">
        <li v-for="link in filteredLinks" :key="link.href">
          <Link
            :href="link.href"
            :class="{ 
              'active bg-citron-50 dark:bg-citron-900/20 text-citron-700 dark:text-citron-300': $page.url.startsWith(link.href),
              'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700': !$page.url.startsWith(link.href)
            }"
            class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150"
          >
            <i :class="link.icon" class="w-5 text-center text-citron-500 dark:text-citron-400"></i>
            {{ link.name }}
          </Link>
        </li>
      </ul>
    </nav>
    
    <div class="mt-auto p-4 border-t border-gray-200 dark:border-gray-700">
      <Link 
        href="/logout" 
        method="post" 
        as="button"
        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-150 w-full"
      >
        <i class="fas fa-sign-out-alt w-5 text-center"></i>
        Déconnexion
      </Link>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 250px;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  height: 100vh;
  position: sticky;
  top: 0;
}
</style>
