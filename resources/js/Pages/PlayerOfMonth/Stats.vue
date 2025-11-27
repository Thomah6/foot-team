<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-green-500 to-teal-600 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-black mb-2 flex items-center gap-3">
                            <i class="fas fa-chart-line"></i>
                            Stats du Mois
                        </h1>
                        <p class="text-green-100">
                            Performances détaillées de {{ props.player.user.name }} pour {{ formatMonth(props.month) }}
                        </p>
                    </div>
                    <Link href="/joueur-du-mois/historique"
                          class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition backdrop-blur">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à l'historique
                    </Link>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto">
                <!-- Carte du joueur -->
                <div class="bg-white rounded-xl border shadow-lg p-6 mb-8">
                    <div class="flex items-center gap-6 mb-6">
                        <img :src="props.player.user.avatar || 'https://via.placeholder.com/120x120.png/00ee99?text=J'"
                             class="w-24 h-24 rounded-full object-cover" />
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900">
                                {{ props.player.user.name }}
                            </h2>
                            <p class="text-gray-600 text-lg">
                                {{ props.player.user.pseudo }}
                            </p>
                            <div class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium mt-2">
                                🏆 Joueur du mois - {{ formatMonth(props.month) }}
                            </div>
                        </div>
                    </div>

                    <!-- Motivation -->
                    <div v-if="props.player.reason" class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-gray-700 italic">
                            <i class="fas fa-quote-left text-gray-400 mr-2"></i>
                            {{ props.player.reason }}
                            <i class="fas fa-quote-right text-gray-400 ml-2"></i>
                        </p>
                    </div>
                </div>

                <!-- Statistiques détaillées -->
                <div class="bg-white rounded-xl border shadow-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                        <i class="fas fa-chart-bar text-green-500"></i>
                        Statistiques du mois
                    </h3>

                    <!-- Message si aucune stat -->
                    <div v-if="props.stats.length === 0" class="text-center py-8">
                        <i class="fas fa-chart-line text-gray-300 text-6xl mb-4"></i>
                        <p class="text-gray-600">
                            Aucune statistique détaillée disponible pour ce mois
                        </p>
                    </div>

                    <!-- Tableau des stats -->
                    <div v-else class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Buts
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Passes
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Matchs
                                    </th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Points
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="stat in props.stats" :key="stat.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ formatDate(stat.date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-gray-900">
                                        {{ stat.goals }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ stat.assists }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                                        {{ stat.matches_played }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ calculatePoints(stat) }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Résumé des stats -->
                    <div v-if="props.stats.length > 0" class="mt-6 pt-6 border-t">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center">
                                <div class="bg-blue-100 text-blue-600 rounded-lg px-4 py-3">
                                    <p class="text-2xl font-bold">{{ totalStats.goals }}</p>
                                    <p class="text-sm">Buts totaux</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-purple-100 text-purple-600 rounded-lg px-4 py-3">
                                    <p class="text-2xl font-bold">{{ totalStats.assists }}</p>
                                    <p class="text-sm">Passes totales</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 text-green-600 rounded-lg px-4 py-3">
                                    <p class="text-2xl font-bold">{{ totalStats.matches }}</p>
                                    <p class="text-sm">Matchs joués</p>
                                </div>
                            </div>
                            <div class="text-center">
                                <div class="bg-yellow-100 text-yellow-600 rounded-lg px-4 py-3">
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
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

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
