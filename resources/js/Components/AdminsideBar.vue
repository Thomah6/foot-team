<script setup>
import { Link } from '@inertiajs/vue3'

const menu = [
    { label: "Dashboard", icon: "fas fa-tachometer-alt", link: route('dashboard'), active: route().current('dashboard') },
    { label: "Profile", icon: "fas fa-user", link: route('profile.edit'), active: route().current('profile.edit') },
]

const bottomMenu = [
    { label: "Settings", icon: "fas fa-cog", link: route('profile.edit') },
]
</script>

<template>
  <!-- Font Awesome CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  
  <aside class="flex w-64 flex-col border-r border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-4 font-inter">

    <!-- Logo -->
    <div class="flex items-center gap-3 px-2 py-2">
      <div class="bg-center bg-cover rounded-lg size-10"
        style="background-image:url('https://lh3.googleusercontent.com/aida-public/AB6AXuBjeJUorb03AmubZfs-dGdxk-uIlSOG-IS4USL9V2y1gzr1QEN3a82Lf5Rflk_gAbyxL3atcRzZQ7fd84B5-oumbQU5rQl1mMGaRcd7JBprWoz447_FLP_r-0Kz8LpZSuIJYv4rjniixzQvbHNbgAKnlcpO-4tyXzzFwGg8RDU1ypjO6vHqdZK6LzDTAWEK0JAzHi4MNyupBCwRWsOvJ9NCQ9EPPjLa3BUrWsihxtKPBa7qGiDAVBC_e7pQRrYct0CwiraW1SQEdg');">
      </div>
      <div>
        <h1 class="text-base font-bold text-text-primary-light dark:text-text-primary-dark">Lumina Club</h1>
        <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">Admin Panel</p>
      </div>
    </div>

    <!-- Navigation Items -->
    <nav class="flex flex-col gap-2 mt-4">

      <Link :href="menu[0].link"
         class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors"
         :class="menu[0].active ? 'bg-blue-500/20 text-blue-600' : 'hover:bg-blue-500/10 text-text-primary-light dark:text-text-primary-dark'">
        <i :class="menu[0].icon" class="text-lg"></i>
        <p class="text-sm font-medium">{{ menu[0].label }}</p>
      </Link>

      <Link :href="menu[1].link"
         class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors"
         :class="menu[1].active ? 'bg-blue-500/20 text-blue-600' : 'hover:bg-blue-500/10 text-text-primary-light dark:text-text-primary-dark'">
        <i :class="menu[1].icon" class="text-lg"></i>
        <p class="text-sm font-medium">{{ menu[1].label }}</p>
      </Link>

    </nav>

    <!-- Bottom -->
    <div class="mt-auto flex flex-col gap-2">
      <Link :href="bottomMenu[0].link"
         class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-500/10 transition-colors text-text-primary-light dark:text-text-primary-dark">
        <i :class="bottomMenu[0].icon" class="text-lg"></i>
        <p class="text-sm font-medium">{{ bottomMenu[0].label }}</p>
      </Link>
    </div>

  </aside>
</template>

<style scoped>
.font-inter {
  font-family: 'Inter', sans-serif;
}

/* Animation pour les transitions fluides */
.transition-colors {
  transition: background-color 0.2s ease, color 0.2s ease;
}

/* Style pour les icônes Font Awesome */
i.fas {
  width: 1.25rem;
  text-align: center;
}
</style>

