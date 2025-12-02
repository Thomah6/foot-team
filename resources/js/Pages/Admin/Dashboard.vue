<script setup>
import AdminLayout from '@/Pages/Admin/AdminLayout.vue';
import Card from '@/Components/Card.vue';
import Button from '@/Components/Button.vue';

// Définir le layout persistant pour cette page
defineOptions({ layout: AdminLayout });

// Imaginons que le backend passe ces données à la page
const props = defineProps({
  stats: {
    type: Object,
    default: () => ({
      users: 150,
      sales: 42,
      articles: 89,
    }),
  },
});
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-lime-50 via-emerald-50 to-green-50 p-6">
    <div class="max-w-7xl mx-auto">
      <h2 class="mb-8 text-3xl font-bold text-lime-800 tracking-wide">Statistiques Agrégées</h2>
      <div class="stats-grid">
        <Card title="Nouveaux Utilisateurs" class="bg-gradient-to-br from-lime-100 to-emerald-100 border-lime-200 hover:shadow-lg transition-shadow">
          <div class="stat-number text-lime-600">{{ stats.users }}</div>
          <p class="text-lime-700">depuis le mois dernier</p>
        </Card>
        <Card title="Ventes du jour" class="bg-gradient-to-br from-emerald-100 to-green-100 border-emerald-200 hover:shadow-lg transition-shadow">
          <div class="stat-number text-emerald-600">{{ stats.sales }}</div>
          <p class="text-emerald-700">transactions enregistrées</p>
        </Card>
        <Card title="Articles Publiés" class="bg-gradient-to-br from-green-100 to-lime-100 border-green-200 hover:shadow-lg transition-shadow">
          <div class="stat-number text-green-600">{{ stats.articles }}</div>
          <p class="text-green-700">au total sur la plateforme</p>
        </Card>
        <Card title="Utilisateurs Inactifs" class="inactive-card bg-gradient-to-br from-amber-100 to-yellow-100 border-amber-200 hover:shadow-lg transition-shadow">
          <div class="stat-number text-amber-600">{{ stats.inactiveUsers }}</div>
          <p class="text-amber-700">en attente d'activation</p>
          <Button variant="primary" size="sm" @click="$inertia.visit('/admin/members')" class="bg-lime-500 hover:bg-lime-600 text-white border-lime-500">Gérer</Button>
        </Card>
      </div>

      <div class="actions mt-8 flex gap-4">
          <Button variant="primary" class="bg-gradient-to-r from-lime-500 to-emerald-500 hover:from-lime-600 hover:to-emerald-600 text-white px-6 py-3 rounded-lg shadow-lg transition-all">Générer un rapport</Button>
          <Button variant="secondary" class="bg-white hover:bg-lime-50 text-lime-700 border-lime-300 px-6 py-3 rounded-lg shadow-md transition-all">Voir les logs</Button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
}
.stat-number {
  font-size: 2.5rem;
  font-weight: bold;
  color: #1abc9c;
}
.mb-4 {
  margin-bottom: 1rem;
}
.mt-6 {
    margin-top: 1.5rem;
}
.actions button {
    margin-right: 0.5rem;
}
</style>