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
  <div>
    <h2 class="mb-4">Statistiques Agrégées</h2>
    <div class="stats-grid">
      <Card title="Nouveaux Utilisateurs">
        <div class="stat-number">{{ stats.users }}</div>
        <p>depuis le mois dernier</p>
      </Card>
      <Card title="Ventes du jour">
        <div class="stat-number">{{ stats.sales }}</div>
        <p>transactions enregistrées</p>
      </Card>
      <Card title="Articles Publiés">
        <div class="stat-number">{{ stats.articles }}</div>
        <p>au total sur la plateforme</p>
      </Card>
    </div>

    <div class="actions mt-6">
        <Button variant="primary">Générer un rapport</Button>
        <Button variant="secondary">Voir les logs</Button>
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