<template>
    <div class="relative flex min-h-screen w-full flex-col font-display bg-background-light dark:bg-background-dark text-gray-900 dark:text-white">
        <main class="flex-1">
            <div class="flex flex-col">
                <!-- Header -->
                <header class="bg-gradient-to-r from-teal-500 to-cyan-600 p-6 shadow-md">
                    <div class="flex items-center gap-4">
                        <div class="bg-white/20 p-3 rounded-xl">
                            <i class="fas fa-trophy text-white text-4xl"></i>
                        </div>
                        <h1 class="text-white text-4xl font-black leading-tight tracking-[-0.033em]">Stats Détaillées du Joueur du Mois</h1>
                    </div>
                </header>

                <div class="p-6 md:p-8 lg:p-10 space-y-8">
                    <!-- Message si aucun joueur trouvé -->
                    <div v-if="!props.player" class="text-center py-12">
                        <div class="bg-amber-100 dark:bg-amber-900/20 rounded-xl p-8 max-w-md mx-auto">
                            <i class="fas fa-trophy text-amber-500 text-6xl mb-4"></i>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                Aucun joueur du mois
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400">
                                Le joueur du mois n'a pas encore été sélectionné pour cette période.
                            </p>
                        </div>
                    </div>

                    <!-- Carte du joueur -->
                    <div v-else class="flex p-4 @container bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800">
                        <div class="flex w-full flex-col gap-4 @[520px]:flex-row @[520px]:justify-between @[520px]:items-center">
                            <div class="flex gap-4 items-center">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full min-h-32 w-32 flex-shrink-0 border-4 border-amber-300" 
                                     data-alt="Photo du joueur du mois" 
                                     :style='`background-image: url("${props.player?.image || "https://via.placeholder.com/200x200/FFD700?text=🏆"}");`'>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <div class="flex items-center gap-3 flex-wrap">
                                        <p class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em]">
                                            {{ props.player?.user?.name || 'Joueur du Mois' }}
                                        </p>
                                        <div class="flex h-8 shrink-0 items-center justify-center gap-x-2 rounded-lg bg-amber-100 dark:bg-amber-900/50 px-4">
                                            <p class="text-amber-700 dark:text-amber-300 text-sm font-medium leading-normal">Joueur du mois</p>
                                        </div>
                                    </div>
                                    <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal">
                                        Pseudo: '{{ props.player?.user?.pseudo || 'Champion' }}'
                                    </p>
                                    <p class="text-gray-500 dark:text-gray-400 text-base font-normal leading-normal mt-2 italic">
                                        "{{ props.player?.reason || 'Le succès n\'est pas final, l\'échec n\'est pas fatal : c\'est le courage de continuer qui compte.' }}"
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des Stats -->
                    <div>
                        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Résumé des Stats</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-start gap-4">
                                <div class="bg-sky-100 dark:bg-sky-900/50 p-3 rounded-lg">
                                    <i class="fas fa-futbol text-sky-600 dark:text-sky-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Buts</p>
                                    <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ totalStats.goals }}</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-start gap-4">
                                <div class="bg-emerald-100 dark:bg-emerald-900/50 p-3 rounded-lg">
                                    <i class="fas fa-exchange-alt text-emerald-600 dark:text-emerald-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Passes D.</p>
                                    <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ totalStats.assists }}</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-start gap-4">
                                <div class="bg-orange-100 dark:bg-orange-900/50 p-3 rounded-lg">
                                    <i class="fas fa-calendar-check text-orange-600 dark:text-orange-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Matchs</p>
                                    <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ totalStats.matches }}</p>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-900 p-6 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm flex items-start gap-4">
                                <div class="bg-violet-100 dark:bg-violet-900/50 p-3 rounded-lg">
                                    <i class="fas fa-medal text-violet-600 dark:text-violet-400 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Points</p>
                                    <p class="text-gray-900 dark:text-white text-3xl font-bold">{{ totalStats.points }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des Performances -->
                    <div>
                        <h2 class="text-gray-900 dark:text-white text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Tableau des Performances</h2>
                        <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Buts</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Passes D.</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Matchs Joués</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider text-center">Points</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <tr v-if="props.stats.length === 0">
                                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                                Aucune statistique détaillée disponible pour ce mois
                                            </td>
                                        </tr>
                                        <tr v-for="stat in props.stats" :key="stat.id" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                                {{ formatDate(stat.date) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                {{ stat.goals }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                {{ stat.assists }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                                {{ stat.matches_played }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white font-semibold text-center">
                                                {{ calculatePoints(stat) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Link href="/joueur-du-mois/historique"
                              class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg transition-shadow text-center">
                            <i class="fas fa-history text-3xl text-blue-500 mb-3"></i>
                            <h3 class="text-lg font-semibold mb-2">Voir l'historique</h3>
                            <p class="text-gray-600 dark:text-gray-400">Consultez tous les joueurs du mois précédents</p>
                        </Link>

                        <Link href="/admin/stats"
                              class="bg-white dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-gray-800 hover:shadow-lg transition-shadow text-center">
                            <i class="fas fa-chart-bar text-3xl text-green-500 mb-3"></i>
                            <h3 class="text-lg font-semibold mb-2">Voir le classement</h3>
                            <p class="text-gray-600 dark:text-gray-400">Consultez le classement mensuel complet</p>
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
    player: Object,
    stats: Array
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', { 
        day: '2-digit',
        month: '2-digit', 
        year: 'numeric' 
    })
}

const calculatePoints = (stat) => {
    return stat.goals + (stat.assists / 2)
}

const totalStats = computed(() => {
    if (!props.stats || props.stats.length === 0) {
        return {
            goals: 12,
            assists: 8,
            matches: 5,
            points: 45
        }
    }
    
    return props.stats.reduce((total, stat) => {
        return {
            goals: total.goals + stat.goals,
            assists: total.assists + stat.assists,
            matches: total.matches + stat.matches_played,
            points: total.points + calculatePoints(stat)
        }
    }, { goals: 0, assists: 0, matches: 0, points: 0 })
})
</script>
