<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue'
// Charts removed — we'll show card lists only

const props = defineProps({
  goals: Array,
  assists: Array,
  goalkeepers: Array
})

</script>

<template>
  <div class="flex">
    <div>
      <AdminsideBar />
    </div>

    <div class="relative min-h-screen w-full bg-background-light text-zinc-800 font-display px-8 py-8">
      <div class="max-w-[1200px] mx-auto">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-black">Classements — Bureau</h1>
          <div>
            <Link href="/bureau/stats" class="py-2 px-3 rounded-lg bg-red-500 text-primary text-sm bg-red-500 text-white"><-Retour</Link>
          </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6 mb-6">
          <div class="bg-white border rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-3">Meilleurs buteurs</h3>
            <div class="space-y-2">
              <div v-for="u in props.goals.slice(0,6)" :key="u.id" class="p-3 border rounded-lg flex justify-between items-center hover:bg-zinc-50">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-600">{{ u.name.substring(0,1) }}</div>
                  <div>
                    <p class="font-medium">{{ u.name }}</p>
                    <p class="text-xs text-zinc-400">{{ u.team_name ?? '—' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm text-zinc-500">Buts</p>
                  <p class="font-bold text-xl">{{ u.total_goals ?? 0 }}</p>
                </div>
              </div>
            </div>
            
          </div>

          <div class="bg-white border rounded-xl p-4">
            <h3 class="text-lg font-semibold mb-3">Meilleurs passeurs</h3>
            <div class="space-y-2">
              <div v-for="u in props.assists.slice(0,6)" :key="u.id" class="p-3 border rounded-lg flex justify-between items-center hover:bg-zinc-50">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-full bg-zinc-100 flex items-center justify-center font-bold text-zinc-600">{{ u.name.substring(0,1) }}</div>
                  <div>
                    <p class="font-medium">{{ u.name }}</p>
                    <p class="text-xs text-zinc-400">{{ u.team_name ?? '—' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-sm text-zinc-500">Passes</p>
                  <p class="font-bold text-xl">{{ u.total_assists ?? 0 }}</p>
                </div>
              </div>
            </div>
            
          </div>
        </div>

        <div class="bg-white border rounded-xl p-4">
          <h3 class="text-lg font-semibold mb-4">Gardien — moins de buts encaissés</h3>
          <table class="w-full text-sm">
            <thead class="text-zinc-600 text-left"><tr><th>Joueur</th><th class="text-right">But(s) encaissé(s)</th></tr></thead>
            <tbody>
              <tr v-for="u in props.goalkeepers" :key="u.id" class="border-t hover:bg-zinc-50">
                <td class="py-3">{{ u.name }}</td>
                <td class="text-right py-3 font-semibold">{{ u.total_goals_against ?? 0 }}</td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>
</template>
