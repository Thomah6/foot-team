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
  <div class="flex">
    <div>
      <AdminsideBar />
    </div>

    <div class="min-h-screen w-full bg-background-light px-8 py-8">
      <div class="max-w-[1100px] mx-auto">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-2xl font-black text-primary py-2 px-4 rounded-lg ">Stats de {{ props.user.name }}</h1>
            <p class="text-sm text-zinc-500 text-center mt-1 text-4xl">Bilan et historique </p>
          </div>
          <div class="flex items-center gap-3">
            <Link href="/bureau/stats" class="py-2 px-3 rounded-lg bg-primary/10 text-primary">Retour</Link>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4 mb-6">
          <div class="bg-white border rounded-xl p-4">
            <p class="text-sm text-zinc-500">Résumé période</p>
            <div class="flex gap-4 mt-3 items-center">
              <div class="p-4 bg-zinc-50 rounded-lg">
                <p class="text-xs text-zinc-500">Buts</p>
                <p class="text-2xl font-bold">{{ props.totals.goals }}</p>
              </div>
              <div class="p-4 bg-zinc-50 rounded-lg">
                <p class="text-xs text-zinc-500">Passes</p>
                <p class="text-2xl font-bold">{{ props.totals.assists }}</p>
              </div>
              <div class="p-4 bg-zinc-50 rounded-lg">
                <p class="text-xs text-zinc-500">Matches</p>
                <p class="text-2xl font-bold">{{ props.totals.matches_played }}</p>
              </div>
            </div>
          </div>

          <div class="bg-white border rounded-xl p-4">
            <p class="text-sm text-zinc-500">Filtres temporels</p>
            <form method="get" class="flex gap-2 mt-3 flex-wrap">
              <select v-model="form.month" name="month" class="px-3 py-2 border rounded-lg">
                <option value="">— Mois —</option>
                <option v-for="m in 12" :value="m">{{ m }}</option>
              </select>
              <select v-model="form.year" name="year" class="px-3 py-2 border rounded-lg">
                <option value="">— Année —</option>
                <option v-for="y in [2023,2024,2025,2026]" :value="y">{{ y }}</option>
              </select>
              <input v-model="form.from" name="from" type="date" class="px-3 py-2 border rounded-lg" />
              <input v-model="form.to" name="to" type="date" class="px-3 py-2 border rounded-lg" />
              <button type="submit" class="px-3 py-2 bg-primary text-white rounded-lg">Appliquer</button>
            </form>
            <p class="text-xs text-zinc-400 mt-2">Utilisez soit Mois+Année, soit plage de dates (from / to).</p>
          </div>
        </div>

        <div class="bg-white border rounded-xl p-4 mb-6">
          <h3 class="text-lg font-semibold mb-4">Evolution mensuelle</h3>
          <div v-if="!props.chart || props.chart.labels.length === 0" class="text-zinc-500 p-4">Aucune donnée mensuelle disponible.</div>
          <div v-else class="grid md:grid-cols-3 gap-3">
            <div v-for="(label, idx) in props.chart.labels" :key="label" class="p-3 border rounded-lg bg-zinc-50">
              <p class="text-xs text-zinc-400">{{ label }}</p>
              <div class="mt-2 flex items-center justify-between">
                <div>
                  <p class="text-sm text-zinc-500">Buts</p>
                  <p class="font-bold text-xl">{{ props.chart.goals[idx] ?? 0 }}</p>
                </div>
                <div>
                  <p class="text-sm text-zinc-500">Passes</p>
                  <p class="font-bold text-xl">{{ props.chart.assists[idx] ?? 0 }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="bg-white border rounded-xl p-4">
          <h3 class="text-lg font-semibold mb-4">Historique détaillé</h3>
          <div v-if="props.stats.length === 0" class="text-zinc-500 p-4">Aucune statistique trouvée pour cette période.</div>
          <div v-else class="space-y-2">
            <div v-for="s in props.stats" :key="s.id" class="p-3 border rounded-lg flex justify-between items-center">
              <div>
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-600">{{ props.user.name.substring(0,1) }}</div>
                  <div>
                    <p class="font-medium">{{ props.user.name }}</p>
                    <p class="text-xs text-zinc-400">{{ s.date }} — match</p>
                  </div>
                </div>
              </div>
              <div class="text-sm text-zinc-700 flex gap-4 items-center">
                <div>Buts: <span class="font-semibold">{{ s.goals }}</span></div>
                <div>Passes: <span class="font-semibold">{{ s.assists }}</span></div>
                <div>Matchs: <span class="font-semibold">{{ s.matches_played }}</span></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
