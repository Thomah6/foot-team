<script setup>
import { usePage } from '@inertiajs/vue3';

const user = usePage().props.auth.user;
</script>

<template>
  <header class="admin-header">
    <div>
      <!-- On pourrait mettre le titre de la page ici -->
      <h1 class="page-title">Dashboard</h1>
    </div>
    <div class="user-info">
      <span>Bonjour, {{ user.name }}</span>
      <!-- Un lien de déconnexion irait ici -->
    </div>
  </header>
</template>

<style scoped>
.admin-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 2rem;
  background-color: #ffffff;
  border-bottom: 1px solid #e0e0e0;
}
.page-title {
  font-size: 1.5rem;
  font-weight: 600;
  color: #333;
}
.user-info {
  font-weight: 500;
}
</style>