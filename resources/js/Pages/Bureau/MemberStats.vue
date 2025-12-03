<script setup>
import { Link, useForm } from '@inertiajs/vue3'


import { ref } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  user: Object,
  stats: Array,
  totals: Object,
  chart: Object
})

const form = useForm({ month: '', year: '', from: '', to: '' })
</script>

<template>
 <AuthenticatedLayout>
   <div class="flex flex-col md:flex-row">
    <div class="min-h-screen w-full bg-background-light dark:bg-gray-900 px-4 md:px-8 py-6 md:py-8">
      <div class="max-w-[1100px] mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
          <div>
            <h1 class="text-2xl sm:text-2xl font-black text-center sm:text-left py-2 px-4 rounded-lg bg-gradient-to-r from-gray-900 to-blue-800 dark:from-white dark:to-blue-300 bg-clip-text text-transparent">
              Stats de {{ props.user.name }}
            </h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-3xl xl:text-4xl text-zinc-500 dark:text-gray-400 mt-1 text-center sm:text-left">
              Bilan et historique
            </p>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <Link href="/bureau/stats" class="py-2 px-3 rounded-lg bg-red-500 dark:bg-red-600 hover:bg-red-600 dark:hover:bg-red-700 text-white text-sm w-full sm:w-auto text-center transition-colors duration-200">
              ← Retour
            </Link>
          </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
          <!-- Résumé période -->
          <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl p-4 md:p-6 shadow-sm dark:shadow-gray-900/50">
            <p class="text-sm text-zinc-500 dark:text-gray-400">Résumé période</p>
            <div class="flex flex-wrap gap-3 md:gap-4 mt-3 items-center justify-center md:justify-start">
              <div class="p-3 md:p-4 bg-zinc-50 dark:bg-gray-700 rounded-lg min-w-[100px] text-center transition-transform hover:scale-105 duration-200">
                <p class="text-xs text-zinc-500 dark:text-gray-400">Buts</p>
                <p class="text-xl md:text-2xl font-bold text-orange-500 dark:text-orange-400">{{ props.totals.goals }}</p>
              </div>
              <div class="p-3 md:p-4 bg-zinc-50 dark:bg-gray-700 rounded-lg min-w-[100px] text-center transition-transform hover:scale-105 duration-200">
                <p class="text-xs text-zinc-500 dark:text-gray-400">Passes</p>
                <p class="text-xl md:text-2xl font-bold text-blue-500 dark:text-blue-400">{{ props.totals.assists }}</p>
              </div>
              <div class="p-3 md:p-4 bg-zinc-50 dark:bg-gray-700 rounded-lg min-w-[100px] text-center transition-transform hover:scale-105 duration-200">
                <p class="text-xs text-zinc-500 dark:text-gray-400">Matches</p>
                <p class="text-xl md:text-2xl font-bold text-emerald-500 dark:text-emerald-400">{{ props.totals.matches_played }}</p>
              </div>
            </div>
          </div>

          <!-- Filtres temporels -->
          <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl p-4 md:p-6 shadow-sm dark:shadow-gray-900/50">
            <p class="text-sm text-zinc-500 dark:text-gray-400">Filtres temporels</p>
            <form method="get" class="flex flex-col sm:flex-row gap-2 mt-3 flex-wrap">
              <div class="flex flex-wrap gap-2 w-full">
                <select v-model="form.month" name="month" 
                  class="px-3 py-2 border dark:border-gray-600 rounded-lg flex-1 min-w-[120px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
                  <option value="" class="text-gray-400 dark:text-gray-500">Mois</option>
                  <option v-for="m in 12" :value="m" class="text-gray-900 dark:text-gray-200">{{ m }}</option>
                </select>
                <select v-model="form.year" name="year" 
                  class="px-3 py-2 border dark:border-gray-600 rounded-lg flex-1 min-w-[120px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent">
                  <option value="" class="text-gray-400 dark:text-gray-500">Année</option>
                  <option v-for="y in [2023,2024,2025,2026]" :value="y" class="text-gray-900 dark:text-gray-200">{{ y }}</option>
                </select>
              </div>
              <div class="flex flex-wrap gap-2 w-full">
                <input v-model="form.from" name="from" type="date" 
                  class="px-3 py-2 border dark:border-gray-600 rounded-lg flex-1 min-w-[140px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent" />
                <input v-model="form.to" name="to" type="date" 
                  class="px-3 py-2 border dark:border-gray-600 rounded-lg flex-1 min-w-[140px] bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent" />
              </div>
              <button type="submit" class="px-4 py-2 bg-green-500 dark:bg-green-600 hover:bg-green-600 dark:hover:bg-green-700 text-white rounded-lg w-full sm:w-auto transition-colors duration-200 font-medium">
                Appliquer
              </button>
            </form>
            <p class="text-xs text-zinc-400 dark:text-gray-500 mt-2">Utilisez soit Mois+Année, soit plage de dates.</p>
          </div>
        </div>

        <!-- Evolution mensuelle -->
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl p-4 md:p-6 mb-6 shadow-sm dark:shadow-gray-900/50">
          <h3 class="text-lg md:text-xl font-semibold mb-4 text-gray-900 dark:text-white">Évolution mensuelle</h3>
          <div v-if="!props.chart || props.chart.labels.length === 0" class="text-zinc-500 dark:text-gray-400 p-4 text-center">
            Aucune donnée mensuelle disponible.
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div v-for="(label, idx) in props.chart.labels" :key="label" 
              class="p-3 md:p-4 border dark:border-gray-700 rounded-lg bg-zinc-50 dark:bg-gray-700 hover:bg-zinc-100 dark:hover:bg-gray-600 transition-colors duration-200">
              <p class="text-xs text-zinc-400 dark:text-gray-400 truncate">{{ label }}</p>
              <div class="mt-2 flex items-center justify-between">
                <div class="text-center flex-1">
                  <p class="text-xs sm:text-sm text-zinc-500 dark:text-gray-400">Buts</p>
                  <p class="font-bold text-lg sm:text-xl text-orange-500 dark:text-orange-400">{{ props.chart.goals[idx] ?? 0 }}</p>
                </div>
                <div class="text-center flex-1">
                  <p class="text-xs sm:text-sm text-zinc-500 dark:text-gray-400">Passes</p>
                  <p class="font-bold text-lg sm:text-xl text-blue-500 dark:text-blue-400">{{ props.chart.assists[idx] ?? 0 }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Historique détaillé -->
        <div class="bg-white dark:bg-gray-800 border dark:border-gray-700 rounded-xl p-4 md:p-6 shadow-sm dark:shadow-gray-900/50">
          <h3 class="text-lg md:text-xl font-semibold mb-4 text-gray-900 dark:text-white">Historique détaillé</h3>
          <div v-if="props.stats.length === 0" class="text-zinc-500 dark:text-gray-400 p-4 text-center">
            Aucune statistique trouvée pour cette période.
          </div>
          <div v-else class="space-y-3">
            <div v-for="s in props.stats" :key="s.id" 
                 class="p-3 md:p-4 border dark:border-gray-700 rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 hover:bg-zinc-50 dark:hover:bg-gray-700 transition-colors duration-200">
              <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-10 h-10 rounded-full bg-gradient-to-r from-purple-100 to-pink-100 dark:from-purple-900/30 dark:to-pink-900/30 flex items-center justify-center font-bold text-yellow-600 dark:text-yellow-400 flex-shrink-0">
                  {{ props.user.name.substring(0,1) }}
                </div>
                <div class="min-w-0">
                  <p class="font-medium truncate text-gray-900 dark:text-white">{{ props.user.name }}</p>
                  <p class="text-xs sm:text-sm text-zinc-500 dark:text-gray-400">{{ new Date(s.date).toLocaleDateString('fr-FR') }}</p>
                </div>
              </div>
              <div class="text-sm text-zinc-700 dark:text-gray-300 flex flex-wrap gap-3 sm:gap-4 items-center justify-start sm:justify-end w-full sm:w-auto">
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  <span class="text-gray-600 dark:text-gray-400">Buts:</span> 
                  <span class="font-semibold text-orange-500 dark:text-orange-400 ml-1">{{ s.goals }}</span>
                </div>
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  <span class="text-gray-600 dark:text-gray-400">Passes:</span> 
                  <span class="font-semibold text-blue-500 dark:text-blue-400 ml-1">{{ s.assists }}</span>
                </div>
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  <span class="text-gray-600 dark:text-gray-400">Matchs:</span> 
                  <span class="font-semibold text-emerald-500 dark:text-emerald-400 ml-1">{{ s.matches_played }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
 </AuthenticatedLayout>  
</template>


