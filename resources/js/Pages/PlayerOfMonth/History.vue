<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
            <!-- Header -->
            <div class="bg-gradient-to-r from-citron-500 to-citron-700 dark:from-gray-800 dark:to-gray-900 text-white">
                <div class="container mx-auto px-4 py-12">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <h1 class="text-3xl md:text-4xl font-black mb-2 flex items-center gap-3">
                                <i class="fas fa-history text-citron-200"></i>
                                Historique des Joueurs du Mois
                            </h1>
                            <p class="text-citron-100 dark:text-citron-300">
                                Tous les joueurs qui ont été élus joueur du mois
                            </p>
                        </div>
                        <Link href="/joueur-du-mois"
                              class="px-4 py-2 bg-white/20 dark:bg-gray-800/50 text-white rounded-lg hover:bg-white/30 dark:hover:bg-gray-700/70 transition backdrop-blur border border-white/30 dark:border-gray-600 flex items-center justify-center gap-2">
                            <i class="fas fa-arrow-left"></i>
                            Retour
                        </Link>
                    </div>
                </div>
            </div>

            <div class="container mx-auto px-4 py-8">
                <!-- Liste des joueurs du mois -->
                <div v-if="props.players.data.length > 0" class="max-w-4xl mx-auto">
                    <div class="grid gap-6">
                        <div v-for="player in props.players.data" :key="player.id"
                             class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow hover:border-citron-300 dark:hover:border-citron-500">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <!-- Date -->
                                        <div class="text-center">
                                            <div class="bg-citron-100 dark:bg-citron-900/50 text-citron-600 dark:text-citron-300 rounded-lg px-3 py-2 border border-citron-200 dark:border-citron-800">
                                                <i class="fas fa-calendar-alt mb-1"></i>
                                                <p class="text-sm font-medium">
                                                    {{ formatDate(player.month) }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Photo et infos -->
                                        <div class="flex items-center gap-4">
                                            <img :src="player.user.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(player.user.name) + '&background=citron-500&color=white'"
                                                 class="w-16 h-16 rounded-full object-cover border-2 border-citron-200 dark:border-citron-700" />
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-citron-100">
                                                    {{ player.user.name }}
                                                </h3>
                                                <p class="text-gray-600 dark:text-citron-300">
                                                    {{ player.user.pseudo || 'Pas de pseudo' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center gap-3">
                                        <Link :href="`/joueur-du-mois/${formatMonthUrl(player.month)}/stats`"
                                              class="px-4 py-2 bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white rounded-lg transition font-semibold flex items-center gap-2">
                                            <i class="fas fa-chart-line"></i>
                                            Stats
                                        </Link>
                                    </div>
                                </div>

                                <!-- Motivation -->
                                <div v-if="player.reason" class="mt-4 bg-citron-50 dark:bg-citron-900/20 rounded-lg p-4 border border-citron-200 dark:border-citron-800">
                                    <p class="text-gray-700 dark:text-citron-200 italic">
                                        <i class="fas fa-quote-left text-citron-400 mr-2"></i>
                                        {{ player.reason }}
                                        <i class="fas fa-quote-right text-citron-400 ml-2"></i>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="props.players.links.length > 3" class="mt-8">
                        <div class="flex justify-center gap-2 flex-wrap">
                            <template v-for="link in props.players.links" :key="link.label">
                                <Link v-if="link.url" 
                                      :href="link.url"
                                      :class="link.active 
                                        ? 'bg-citron-600 dark:bg-citron-500 text-white px-4 py-2 rounded-lg font-semibold' 
                                        : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700'"
                                      v-html="link.label">
                                </Link>
                                <span v-else 
                                      class="bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 px-4 py-2 rounded-lg"
                                      v-html="link.label">
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Message si aucun joueur -->
                <div v-else class="max-w-2xl mx-auto text-center py-16">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8">
                        <div class="bg-citron-100 dark:bg-citron-900/30 w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-history text-citron-600 dark:text-citron-400 text-4xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-citron-100 mb-4">
                            Aucun historique disponible
                        </h2>
                        <p class="text-gray-600 dark:text-citron-300 mb-8">
                            Les joueurs du mois apparaîtront ici dès qu'ils seront désignés.
                        </p>
                        <Link href="/joueur-du-mois"
                              class="inline-flex items-center gap-2 bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white px-6 py-3 rounded-lg transition font-semibold">
                            <i class="fas fa-trophy"></i>
                            Voir le joueur du mois actuel
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    players: Object
})

const formatDate = (dateString) => {
    const date = new Date(dateString)
    return date.toLocaleDateString('fr-FR', { 
        month: 'long', 
        year: 'numeric' 
    })
}

const formatMonthUrl = (dateString) => {
    const date = new Date(dateString)
    return date.toISOString().slice(0, 7) // YYYY-MM
}
</script>