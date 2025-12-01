<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue'
import { ref } from 'vue'

const props = defineProps({
  user: Object,
  stats: Array,
  totals: Object,
  chart: Object
})

const form = useForm({ month: '', year: '', from: '', to: '' })
</script>

<template>
  <div class="flex flex-col md:flex-row">
    <div class="md:w-auto">
      <AdminsideBar />
    </div>

    <div class="min-h-screen w-full bg-background-light px-4 md:px-8 py-6 md:py-8">
      <div class="max-w-[1100px] mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4 sm:gap-0">
          <div>
            <h1 class="text-2xl  sm:text-2xl font-black ml-4 text-center sm:ml-4 py-2 px-4 rounded-lg">Stats de {{ props.user.name }}</h1>
            <p class="text-base sm:text-lg md:text-xl lg:text-4xl text-zinc-500 mt-1 text-center sm:text-left">Bilan et historique</p>
          </div>
          <div class="flex items-center gap-3 w-full sm:w-auto">
            <Link href="/bureau/stats" class="py-2 px-3 rounded-lg bg-red-500 text-white text-sm w-full sm:w-auto text-center">← Retour</Link>
          </div>
        </div>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-6">
          <!-- Résumé période -->
          <div class="bg-white border rounded-xl p-4 md:p-6">
            <p class="text-sm text-zinc-500">Résumé période</p>
            <div class="flex flex-wrap gap-3 md:gap-4 mt-3 items-center justify-center md:justify-start">
              <div class="p-3 md:p-4 bg-zinc-50 rounded-lg min-w-[100px] text-center">
                <p class="text-xs text-zinc-500">Buts</p>
                <p class="text-xl md:text-2xl font-bold">{{ props.totals.goals }}</p>
              </div>
              <div class="p-3 md:p-4 bg-zinc-50 rounded-lg min-w-[100px] text-center">
                <p class="text-xs text-zinc-500">Passes</p>
                <p class="text-xl md:text-2xl font-bold">{{ props.totals.assists }}</p>
              </div>
              <div class="p-3 md:p-4 bg-zinc-50 rounded-lg min-w-[100px] text-center">
                <p class="text-xs text-zinc-500">Matches</p>
                <p class="text-xl md:text-2xl font-bold">{{ props.totals.matches_played }}</p>
              </div>
            </div>
          </div>

          <!-- Filtres temporels -->
          <div class="bg-white border rounded-xl p-4 md:p-6">
            <p class="text-sm text-zinc-500">Filtres temporels</p>
            <form method="get" class="flex flex-col sm:flex-row gap-2 mt-3 flex-wrap">
              <div class="flex flex-wrap gap-2 w-full">
                <select v-model="form.month" name="month" class="px-3 py-2 border rounded-lg flex-1 min-w-[120px]">
                  <option value=""> Mois </option>
                  <option v-for="m in 12" :value="m">{{ m }}</option>
                </select>
                <select v-model="form.year" name="year" class="px-3 py-2 border rounded-lg flex-1 min-w-[120px]">
                  <option value="">Année</option>
                  <option v-for="y in [2023,2024,2025,2026]" :value="y">{{ y }}</option>
                </select>
              </div>
              <div class="flex flex-wrap gap-2 w-full">
                <input v-model="form.from" name="from" type="date" class="px-3 py-2 border rounded-lg flex-1 min-w-[140px]" />
                <input v-model="form.to" name="to" type="date" class="px-3 py-2 border rounded-lg flex-1 min-w-[140px]" />
              </div>
              <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg w-full sm:w-auto">Appliquer</button>
            </form>
            <p class="text-xs text-zinc-400 mt-2">Utilisez soit Mois+Année, soit plage de dates.</p>
          </div>
        </div>

        <!-- Evolution mensuelle -->
        <div class="bg-white border rounded-xl p-4 md:p-6 mb-6">
          <h3 class="text-lg md:text-xl font-semibold mb-4">Évolution mensuelle</h3>
          <div v-if="!props.chart || props.chart.labels.length === 0" class="text-zinc-500 p-4 text-center">
            Aucune donnée mensuelle disponible.
          </div>
          <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div v-for="(label, idx) in props.chart.labels" :key="label" class="p-3 md:p-4 border rounded-lg bg-zinc-50">
              <p class="text-xs text-zinc-400 truncate">{{ label }}</p>
              <div class="mt-2 flex items-center justify-between">
                <div class="text-center flex-1">
                  <p class="text-xs sm:text-sm text-zinc-500">Buts</p>
                  <p class="font-bold text-lg sm:text-xl">{{ props.chart.goals[idx] ?? 0 }}</p>
                </div>
                <div class="text-center flex-1">
                  <p class="text-xs sm:text-sm text-zinc-500">Passes</p>
                  <p class="font-bold text-lg sm:text-xl">{{ props.chart.assists[idx] ?? 0 }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Historique détaillé -->
        <div class="bg-white border rounded-xl p-4 md:p-6">
          <h3 class="text-lg md:text-xl font-semibold mb-4">Historique détaillé</h3>
          <div v-if="props.stats.length === 0" class="text-zinc-500 p-4 text-center">
            Aucune statistique trouvée pour cette période.
          </div>
          <div v-else class="space-y-3">
            <div v-for="s in props.stats" :key="s.id" 
                 class="p-3 md:p-4 border rounded-lg flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
              <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center font-bold text-yellow-600 flex-shrink-0">
                  {{ props.user.name.substring(0,1) }}
                </div>
                <div class="min-w-0">
                  <p class="font-medium truncate">{{ props.user.name }}</p>
                  <p class="text-xs sm:text-sm text-zinc-500">{{ new Date(s.date).toLocaleDateString('fr-FR') }}</p>
                </div>
              </div>
              <div class="text-sm text-zinc-700 flex flex-wrap gap-3 sm:gap-4 items-center justify-start sm:justify-end w-full sm:w-auto">
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  Buts: <span class="font-semibold">{{ s.goals }}</span>
                </div>
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  Passes: <span class="font-semibold">{{ s.assists }}</span>
                </div>
                <div class="flex-1 sm:flex-none text-center sm:text-left">
                  Matchs: <span class="font-semibold">{{ s.matches_played }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
