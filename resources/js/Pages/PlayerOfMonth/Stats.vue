<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Header -->
            <div class="bg-gradient-to-r from-citron-500 to-citron-700 dark:from-gray-800 dark:to-gray-900 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black mb-2 flex items-center gap-3">
                                <i class="fas fa-chart-line text-citron-200"></i>
                                Stats du Mois
                            </h1>
                            <p class="text-citron-100 dark:text-citron-300">
                                Performances détaillées de {{ props.player.user.name }} pour {{ formatMonth(props.month) }}
                            </p>
                        </div>
                        <Link href="/joueur-du-mois/historique"
                              class="px-4 py-2 bg-white/20 dark:bg-gray-800/50 text-white rounded-lg hover:bg-white/30 dark:hover:bg-gray-700/70 transition backdrop-blur border border-white/30 dark:border-gray-600 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Retour à l'historique
                        </Link>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 py-8">
                <div class="max-w-4xl mx-auto">
                    <!-- Carte du joueur -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6 mb-8">
                        <div class="flex flex-col md:flex-row items-center gap-6 mb-6">
                            <img :src="props.player.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(props.player.user.name) + '&background=citron-500&color=white'"
                                 class="w-24 h-24 rounded-full object-cover border-4 border-citron-200 dark:border-citron-700" />
                            <div class="text-center md:text-left">
                                <h2 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-citron-100">
                                    {{ props.player.user.name }}
                                </h2>
                                <p class="text-gray-600 dark:text-citron-300 text-lg">
                                    {{ props.player.user.pseudo || 'Pas de pseudo' }}
                                </p>
                                <div class="bg-citron-100 dark:bg-citron-900/50 text-citron-700 dark:text-citron-300 px-3 py-1 rounded-full text-sm font-medium mt-2 inline-flex items-center gap-2 border border-citron-200 dark:border-citron-800">
                                    🏆 Joueur du mois - {{ formatMonth(props.month) }}
                                </div>
                            </div>
                        </div>

                        <!-- Motivation -->
                        <div v-if="props.player.reason" class="bg-citron-50 dark:bg-citron-900/20 rounded-lg p-4 mb-6 border border-citron-200 dark:border-citron-800">
                            <p class="text-gray-700 dark:text-citron-200 italic">
                                <i class="fas fa-quote-left text-citron-400 mr-2"></i>
                                {{ props.player.reason }}
                                <i class="fas fa-quote-right text-citron-400 ml-2"></i>
                            </p>
                        </div>
                    </div>

                    <!-- Statistiques détaillées -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-lg p-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-citron-100 mb-6 flex items-center gap-3">
                            <i class="fas fa-chart-bar text-citron-500"></i>
                            Statistiques du mois
                        </h3>

                        <!-- Message si aucune stat -->
                        <div v-if="props.stats.length === 0" class="text-center py-8">
                            <div class="bg-citron-50 dark:bg-citron-900/20 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-citron-500 text-3xl"></i>
                            </div>
                            <p class="text-gray-600 dark:text-citron-300">
                                Aucune statistique détaillée disponible pour ce mois
                            </p>
                        </div>

                        <!-- Tableau des stats -->
                        <div v-else class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-citron-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-citron-300 uppercase tracking-wider">
                                            Buts
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-citron-300 uppercase tracking-wider">
                                            Passes
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-citron-300 uppercase tracking-wider">
                                            Matchs
                                        </th>
                                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-citron-300 uppercase tracking-wider">
                                            Points
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="stat in props.stats" :key="stat.id" class="hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-citron-100">
                                            {{ formatDate(stat.date) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900 dark:text-citron-100">
                                            {{ stat.goals }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-citron-100">
                                            {{ stat.assists }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900 dark:text-citron-100">
                                            {{ stat.matches_played }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-citron-100 dark:bg-citron-900/50 text-citron-700 dark:text-citron-300 border border-citron-200 dark:border-citron-800">
                                                {{ calculatePoints(stat) }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Résumé des stats -->
                        <div v-if="props.stats.length > 0" class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center">
                                    <div class="bg-citron-50 dark:bg-citron-900/30 text-citron-600 dark:text-citron-300 rounded-lg px-4 py-3 border border-citron-200 dark:border-citron-800">
                                        <p class="text-2xl font-bold">{{ totalStats.goals }}</p>
                                        <p class="text-sm">Buts totaux</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-300 rounded-lg px-4 py-3 border border-blue-200 dark:border-blue-800">
                                        <p class="text-2xl font-bold">{{ totalStats.assists }}</p>
                                        <p class="text-sm">Passes totales</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-300 rounded-lg px-4 py-3 border border-green-200 dark:border-green-800">
                                        <p class="text-2xl font-bold">{{ totalStats.matches }}</p>
                                        <p class="text-sm">Matchs joués</p>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div class="bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-300 rounded-lg px-4 py-3 border border-yellow-200 dark:border-yellow-800">
                                        <p class="text-2xl font-bold">{{ totalStats.points }}</p>
                                        <p class="text-sm">Points totaux</p>
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

<script setup>
import { Link, computed } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    player: Object,
    stats: Array,
    month: String
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', { 
        day: 'numeric',
        month: 'long', 
        year: 'numeric' 
    })
}

const formatMonth = (month) => {
    const date = new Date(month + '-01')
    return date.toLocaleDateString('fr-FR', { 
        month: 'long', 
        year: 'numeric' 
    })
}

const calculatePoints = (stat) => {
    return stat.goals + (stat.assists / 2)
}

const totalStats = computed(() => {
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