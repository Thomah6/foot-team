<script setup>
import { computed } from 'vue';

const props = defineProps({
  stats: {
    type: Array,
    required: true,
  },
});

const monthLabel = computed(() => {
  const now = new Date();
  return new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  }).format(now);
});

const getMedal = (index) => {
  const medals = ['🥇', '🥈', '🥉'];
  return medals[index] || '📍';
};
</script>

<template>
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
      <h2 class="text-2xl md:text-3xl font-bold text-white">
        🏆 Classement des Équipes
      </h2>
      <p class="text-blue-100 mt-1">
        {{ monthLabel }}
      </p>
    </div>

    <!-- Contenu -->
    <div v-if="stats.length > 0" class="overflow-x-auto">
      <!-- Version desktop -->
      <table class="w-full hidden md:table">
        <thead>
          <tr class="bg-gray-100 dark:bg-gray-800">
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white">
              Rang
            </th>
            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-900 dark:text-white">
              Équipe
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              V/D/N
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Matchs
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Points
            </th>
            <th class="px-6 py-3 text-center text-xs font-semibold text-gray-900 dark:text-white">
              Taux V.
            </th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(stat, index) in stats"
            :key="stat.team.id"
            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
            :class="{ 'bg-yellow-50 dark:bg-yellow-900': index === 0 }"
          >
            <td class="px-6 py-4">
              <div class="text-lg font-bold text-gray-900 dark:text-white">
                {{ getMedal(index) }} {{ index + 1 }}
              </div>
            </td>
            <td class="px-6 py-4 font-semibold text-gray-900 dark:text-white">
              {{ stat.team.name }}
            </td>
            <td class="px-6 py-4">
              <div class="flex justify-center gap-2 text-sm font-medium">
                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded">
                  {{ stat.wins }}V
                </span>
                <span class="px-2 py-1 bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 rounded">
                  {{ stat.losses }}D
                </span>
                <span class="px-2 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded">
                  {{ stat.draws }}N
                </span>
              </div>
            </td>
            <td class="px-6 py-4 text-center font-semibold text-gray-900 dark:text-white">
              {{ stat.total_matches }}
            </td>
            <td class="px-6 py-4 text-center font-bold text-blue-600 dark:text-blue-400">
              {{ stat.points }}
            </td>
            <td class="px-6 py-4 text-center">
              <div class="inline-block px-3 py-1 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full font-semibold">
                {{ stat.win_rate }}%
              </div>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Version mobile -->
      <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
        <div
          v-for="(stat, index) in stats"
          :key="stat.team.id"
          class="p-4"
          :class="{ 'bg-yellow-50 dark:bg-yellow-900': index === 0 }"
        >
          <div class="flex items-start justify-between mb-3">
            <div>
              <p class="text-2xl font-bold text-gray-900 dark:text-white">
                {{ getMedal(index) }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                Position {{ index + 1 }}
              </p>
            </div>
            <div class="text-right">
              <p class="text-xl font-bold text-blue-600 dark:text-blue-400">
                {{ stat.points }} pts
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                {{ stat.win_rate }}% V
              </p>
            </div>
          </div>

          <p class="font-bold text-gray-900 dark:text-white mb-3">
            {{ stat.team.name }}
          </p>

          <div class="grid grid-cols-3 gap-2 mb-3">
            <div class="bg-green-100 dark:bg-green-900 p-2 rounded text-center">
              <p class="text-xs text-green-700 dark:text-green-300">Victoires</p>
              <p class="text-lg font-bold text-green-900 dark:text-green-100">
                {{ stat.wins }}
              </p>
            </div>
            <div class="bg-red-100 dark:bg-red-900 p-2 rounded text-center">
              <p class="text-xs text-red-700 dark:text-red-300">Défaites</p>
              <p class="text-lg font-bold text-red-900 dark:text-red-100">
                {{ stat.losses }}
              </p>
            </div>
            <div class="bg-yellow-100 dark:bg-yellow-900 p-2 rounded text-center">
              <p class="text-xs text-yellow-700 dark:text-yellow-300">Nuls</p>
              <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">
                {{ stat.draws }}
              </p>
            </div>
          </div>

          <div class="text-xs text-gray-600 dark:text-gray-400">
            {{ stat.total_matches }} matchs joués
          </div>
        </div>
      </div>
    </div>

    <!-- Message vide -->
    <div v-else class="text-center py-12">
      <p class="text-gray-600 dark:text-gray-400">
        Aucune stat disponible pour ce mois
      </p>
    </div>
  </div>
</template>