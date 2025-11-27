<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminsideBar from '@/Components/AdminsideBar.vue'

const props = defineProps({
  totalStats: Number,
  activePlayers: Number,
  latestStats: Array
})

const search = ref('')
</script>

<template>
  <div class="flex">
    <div>
      <AdminsideBar />
    </div>

    <div class="relative min-h-screen w-full bg-background-light text-zinc-800 font-display px-8 py-6">
      <div class="max-w-[1000px] mx-auto">
        <div class="flex justify-between items-center mb-6">
          <div>
            <h1 class="text-3xl font-black">Espace Bureau — Statistiques et performances</h1>
            <p class="text-sm text-zinc-600 mt-1">Consultez les performances des joueurs, historiques et classements.</p>
          </div>
          <div class="flex gap-2">
            <Link href="/bureau/stats/leaderboards" class="py-2 px-4 rounded-lg bg-primary text-white font-semibold">Voir classements</Link>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <div class="bg-white p-4 rounded-xl border">
            <p class="text-sm text-zinc-500">Nombre d'entrées statistiques</p>
            <p class="text-2xl font-bold mt-2">{{ props.totalStats }}</p>
          </div>
          <div class="bg-white p-4 rounded-xl border">
            <p class="text-sm text-zinc-500">Joueurs avec stats</p>
            <p class="text-2xl font-bold mt-2">{{ props.activePlayers }}</p>
          </div>
          <div class="bg-white p-4 rounded-xl border">
            <p class="text-sm text-zinc-500">Historique récent</p>
            <p class="text-2xl font-bold mt-2">{{ props.latestStats.length }}</p>
          </div>
        </div>

        <div class="bg-white border rounded-xl p-4">
          <h3 class="text-lg font-semibold mb-3">Dernières statistiques</h3>
          <div v-if="props.latestStats.length === 0" class="text-zinc-500">Aucune statistique enregistrée.</div>
          <div v-else class="grid gap-3">
            <div v-for="s in props.latestStats" :key="s.id" class="p-3 border rounded-lg flex items-center gap-4">
              <img :src="s.user.avatar" class="w-12 h-12 rounded-full object-cover" />
              <div class="flex-grow">
                <div class="flex justify-between items-center">
                  <div>
                    <p class="font-medium">{{ s.user.name }}</p>
                    <p class="text-sm text-zinc-500">{{ s.goals }} buts — {{ s.assists }} passes — le {{ s.date }}</p>
                  </div>
                  <div>
                    <Link :href="`/bureau/stats/members/${s.user.id}/stats`" class="text-xs px-3 py-1 bg-primary/10 text-primary rounded-full">Voir joueur</Link>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
