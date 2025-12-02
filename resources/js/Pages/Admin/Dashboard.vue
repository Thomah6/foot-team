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
  <div class="min-h-screen bg-gradient-to-br from-lime-50 via-emerald-50 to-green-50 p-4 sm:p-6">
    <div class="max-w-7xl mx-auto">
      <h2 class="mb-6 sm:mb-8 text-2xl sm:text-3xl font-bold text-lime-800 tracking-wide">Statistiques Agrégées</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <Card title="Nouveaux Utilisateurs" class="bg-gradient-to-br from-lime-100 to-emerald-100 border-lime-200 hover:shadow-lg transition-shadow">
          <div class="text-3xl sm:text-4xl font-bold text-lime-600">{{ stats.users }}</div>
          <p class="text-xs sm:text-sm text-lime-700 mt-2">depuis le mois dernier</p>
        </Card>
        <Card title="Ventes du jour" class="bg-gradient-to-br from-emerald-100 to-green-100 border-emerald-200 hover:shadow-lg transition-shadow">
          <div class="text-3xl sm:text-4xl font-bold text-emerald-600">{{ stats.sales }}</div>
          <p class="text-xs sm:text-sm text-emerald-700 mt-2">transactions enregistrées</p>
        </Card>
        <Card title="Articles Publiés" class="bg-gradient-to-br from-green-100 to-lime-100 border-green-200 hover:shadow-lg transition-shadow">
          <div class="text-3xl sm:text-4xl font-bold text-green-600">{{ stats.articles }}</div>
          <p class="text-xs sm:text-sm text-green-700 mt-2">au total sur la plateforme</p>
        </Card>
        <Card title="Utilisateurs Inactifs" class="bg-gradient-to-br from-amber-100 to-yellow-100 border-amber-200 hover:shadow-lg transition-shadow">
          <div class="text-3xl sm:text-4xl font-bold text-amber-600">{{ stats.inactiveUsers }}</div>
          <p class="text-xs sm:text-sm text-amber-700 mt-2">en attente d'activation</p>
          <Button variant="primary" size="sm" @click="$inertia.visit('/admin/members')" class="mt-3 w-full sm:w-auto">Gérer</Button>
        </Card>
      </div>

      <div class="actions mt-6 sm:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
          <Button variant="primary" class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base w-full sm:w-auto">Générer un rapport</Button>
          <Button variant="secondary" class="px-4 sm:px-6 py-2 sm:py-3 text-sm sm:text-base w-full sm:w-auto">Voir les logs</Button>
      </div>
    </div>
  </div>
</template>
