<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';

const props = defineProps({
  team: Object,
  stats: Array,
});

const totalWins = computed(() => props.stats.reduce((s, st) => s + st.wins, 0));
const totalLosses = computed(() => props.stats.reduce((s, st) => s + st.losses, 0));
const totalDraws = computed(() => props.stats.reduce((s, st) => s + st.draws, 0));
const totalPoints = computed(() => props.stats.reduce((s, st) => s + (st.wins * 3 + st.draws), 0));
</script>

<template>
  <AuthenticatedLayout>
    <div class="max-w-5xl mx-auto p-4">
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-sky-900">Historique — {{ team.name }}</h1>
          <p class="text-sm text-gray-600">Historique mensuel des performances</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded p-4 shadow text-center">
          <p class="text-xs text-gray-500">Victoires</p>
          <p class="text-2xl font-bold text-green-600">{{ totalWins }}</p>
        </div>
        <div class="bg-white rounded p-4 shadow text-center">
          <p class="text-xs text-gray-500">Défaites</p>
          <p class="text-2xl font-bold text-red-600">{{ totalLosses }}</p>
        </div>
        <div class="bg-white rounded p-4 shadow text-center">
          <p class="text-xs text-gray-500">Nuls</p>
          <p class="text-2xl font-bold text-yellow-600">{{ totalDraws }}</p>
        </div>
        <div class="bg-white rounded p-4 shadow text-center">
          <p class="text-xs text-gray-500">Points</p>
          <p class="text-2xl font-bold text-sky-900">{{ totalPoints }}</p>
        </div>
      </div>

      <div class="bg-white rounded shadow overflow-hidden">
        <table class="w-full table-auto">
          <thead>
            <tr class="bg-sky-900 text-white">
              <th class="px-4 py-3 text-left text-sm">Mois</th>
              <th class="px-4 py-3 text-center text-sm">V</th>
              <th class="px-4 py-3 text-center text-sm">D</th>
              <th class="px-4 py-3 text-center text-sm">N</th>
              <th class="px-4 py-3 text-center text-sm">Matchs</th>
              <th class="px-4 py-3 text-center text-sm">Points</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="stat in stats" :key="stat.id" class="border-t last:border-b-0">
              <td class="px-4 py-3 text-sm">{{ new Date(stat.month).toLocaleDateString('fr-FR', { month: 'long', year: 'numeric' }) }}</td>
              <td class="px-4 py-3 text-center">{{ stat.wins }}</td>
              <td class="px-4 py-3 text-center">{{ stat.losses }}</td>
              <td class="px-4 py-3 text-center">{{ stat.draws }}</td>
              <td class="px-4 py-3 text-center">{{ stat.wins + stat.losses + stat.draws }}</td>
              <td class="px-4 py-3 text-center">{{ (stat.wins * 3) + stat.draws }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
