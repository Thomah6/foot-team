<<<<<<< HEAD
=======
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
                                        <tr v-if="props.stats.lenght === 0">
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

>>>>>>> 3dee3a5bef77a34b39d2b3cd26556643038ba0d4
<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    player: Object,
    stats: Array
});

console.log(props.stats)



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

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-black text-white font-sans">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-slate-800 to-slate-900 py-16 px-4 border-b border-slate-700">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl flex items-center justify-center border border-slate-600">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <div class="text-slate-300 font-light text-xl tracking-wider">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-200 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-slate-500 text-sm tracking-widest">SAISON 2024</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-thin text-white tracking-wider mb-3">JOUEUR DU MOIS</h1>
                    <div class="text-slate-400 text-lg tracking-widest font-light">STATISTIQUES DÉTAILLÉES</div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-16">
                
                <!-- Message si aucun joueur trouvé -->
                <div v-if="!props.player" class="text-center py-32">
                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-3xl p-12 max-w-2xl mx-auto border border-slate-800">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-slate-600/30">
                            <i class="fas fa-trophy text-slate-400 text-5xl"></i>
                        </div>
                        <h2 class="text-4xl font-thin text-white mb-4">AUCUN JOUEUR DU MOIS</h2>
                        <p class="text-slate-400 text-lg font-light">
                            Le joueur du mois n'a pas encore été sélectionné pour cette période.
                        </p>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div v-else class="space-y-12">
                    
                    <!-- Carte du joueur premium -->
                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-3xl p-10 border border-slate-800 shadow-2xl">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <!-- Photo du joueur -->
                            <div class="relative">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full min-h-40 w-40 border-4 border-yellow-600/50 shadow-2xl" 
                                     data-alt="Photo du joueur du mois" 
                                     :style='`background-image: url("${props.player?.image || "https://via.placeholder.com/200x200/FFD700?text=🏆"}");`'>
                                </div>
                                <!-- Badge Joueur du mois -->
                                <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-full p-3 border-2 border-slate-900">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                            </div>
                            
                            <!-- Infos du joueur -->
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
                                    <h2 class="text-4xl font-thin text-white tracking-wide">
                                        {{ props.player?.user?.name || 'Joueur du Mois' }}
                                    </h2>
                                    <div class="bg-gradient-to-r from-yellow-600/20 to-yellow-700/20 rounded-2xl px-6 py-2 border border-yellow-600/30">
                                        <p class="text-yellow-500 font-light tracking-wider">JOUEUR DU MOIS</p>
                                    </div>
                                </div>
                                <p class="text-slate-400 text-lg font-light mb-4">
                                    Pseudo: "{{ props.player?.user?.pseudo || 'Champion' }}"
                                </p>
                                <p class="text-slate-300 text-xl font-light italic max-w-2xl">
                                    "{{ props.player?.reason || 'Le succès n\'est pas final, l\'échec n\'est pas fatal : c\'est le courage de continuer qui compte.' }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des Stats Premium -->
                    <div>
                        <h2 class="text-3xl font-thin text-white tracking-wider mb-8 px-4">RÉSUMÉ DES STATISTIQUES</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl">
                                <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30">
                                    <i class="fas fa-futbol text-slate-400 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm tracking-widest font-light mb-2">TOTAL BUTS</p>
                                <p class="text-5xl font-thin text-white">{{ totalStats.goals }}</p>
                            </div>
                            <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl">
                                <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30">
                                    <i class="fas fa-exchange-alt text-slate-400 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm tracking-widest font-light mb-2">TOTAL PASSES</p>
                                <p class="text-5xl font-thin text-white">{{ totalStats.assists }}</p>
                            </div>
                            <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl">
                                <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30">
                                    <i class="fas fa-calendar-check text-slate-400 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm tracking-widest font-light mb-2">TOTAL MATCHS</p>
                                <p class="text-5xl font-thin text-white">{{ totalStats.matches }}</p>
                            </div>
                            <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl">
                                <div class="bg-gradient-to-br from-yellow-600/20 to-yellow-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-600/30">
                                    <i class="fas fa-medal text-yellow-500 text-3xl"></i>
                                </div>
                                <p class="text-slate-400 text-sm tracking-widest font-light mb-2">TOTAL POINTS</p>
                                <p class="text-5xl font-thin text-yellow-500">{{ totalStats.points }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau des Performances Premium -->
                    <div>
                        <h2 class="text-3xl font-thin text-white tracking-wider mb-8 px-4">TABLEAU DES PERFORMANCES</h2>
                        <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-800 shadow-2xl">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-slate-800/80 border-b border-slate-700">
                                        <tr>
                                            <th class="px-8 py-6 text-slate-300 text-sm font-light tracking-wider text-left">DATE</th>
                                            <th class="px-8 py-6 text-slate-300 text-sm font-light tracking-wider text-center">BUTS</th>
                                            <th class="px-8 py-6 text-slate-300 text-sm font-light tracking-wider text-center">PASSES</th>
                                            <th class="px-8 py-6 text-slate-300 text-sm font-light tracking-wider text-center">MATCHS</th>
                                            <th class="px-8 py-6 text-slate-300 text-sm font-light tracking-wider text-center">POINTS</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-800/50">
                                        <tr v-if="!props.stats">
                                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 font-light">
                                                Aucune statistique détaillée disponible pour ce mois 
                                            </td>
                                        </tr>
                                        <tr v-for="stat in props.stats" :key="stat.id" class="hover:bg-slate-800/30 transition-colors duration-300">
                                            <td class="px-8 py-6 text-white font-light">{{ formatDate(stat.date) }}</td>
                                            <td class="px-8 py-6 text-slate-300 font-light text-center">{{ stat.goals }}</td>
                                            <td class="px-8 py-6 text-slate-300 font-light text-center">{{ stat.assists }}</td>
                                            <td class="px-8 py-6 text-slate-300 font-light text-center">{{ stat.matches_played }}</td>
                                            <td class="px-8 py-6 text-yellow-500 font-light text-center">{{ calculatePoints(stat) }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Actions Premium -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <Link href="/joueur-du-mois/historique"
                              class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-10 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                            <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30 group-hover:border-slate-500/50 transition-colors">
                                <i class="fas fa-history text-slate-400 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-thin text-white mb-4 tracking-wide">VOIR L'HISTORIQUE</h3>
                            <p class="text-slate-400 font-light">Consultez tous les joueurs du mois précédents</p>
                        </Link>

                        <Link href="/classements"
                              class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-10 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                            <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30 group-hover:border-slate-500/50 transition-colors">
                                <i class="fas fa-chart-bar text-slate-400 text-3xl"></i>
                            </div>
                            <h3 class="text-2xl font-thin text-white mb-4 tracking-wide">VOIR LE CLASSEMENT</h3>
                            <p class="text-slate-400 font-light">Consultez le classement mensuel complet</p>
                        </Link>
                    </div>

                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
