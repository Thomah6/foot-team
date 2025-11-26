<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

// Liste de tous les liens possibles dans l'application
const navLinks = [
  { name: 'Dashboard', href: '/admin/dashboard', permission: 'view dashboard' },
  { name: 'Utilisateurs', href: '/admin/users', permission: 'view users' },
  { name: 'Articles', href: '/admin/articles', permission: 'edit articles' },
  { name: 'Paramètres', href: '/admin/settings', permission: 'edit settings' },
];

const userPermissions = usePage().props.auth.permissions;

// On filtre les liens pour ne garder que ceux que l'utilisateur a la permission de voir
const filteredLinks = computed(() => {
  return navLinks.filter(link => userPermissions.includes(link.permission));
});
</script>

<template>
  <aside class="sidebar">
    <div class="logo">Mon App</div>
    <nav class="navigation">
      <ul>
        <li v-for="link in filteredLinks" :key="link.href">
          <Link
            :href="link.href"
            :class="{ 'active': $page.url.startsWith(link.href) }"
          >
            {{ link.name }}
          </Link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 250px;
  background-color: #2c3e50;
  color: #ecf0f1;
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
}
.logo {
  padding: 1.5rem;
  font-size: 1.8rem;
  font-weight: bold;
  text-align: center;
  background-color: #34495e;
}
.navigation ul {
  list-style: none;
  padding: 0;
  margin: 1rem 0;
}
.navigation a {
  display: block;
  padding: 0.8rem 1.5rem;
  color: #ecf0f1;
  text-decoration: none;
  transition: background-color 0.3s;
}
.navigation a:hover {
  background-color: #34495e;
}
.navigation a.active {
  background-color: #1abc9c;
  font-weight: bold;
}
</style>