<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-black mb-2 flex items-center gap-3">
                            <i class="fas fa-history"></i>
                            Historique des Joueurs du Mois
                        </h1>
                        <p class="text-blue-100">
                            Tous les joueurs qui ont été élus joueur du mois
                        </p>
                    </div>
                    <Link href="/joueur-du-mois"
                          class="px-4 py-2 bg-white/20 text-white rounded-lg hover:bg-white/30 transition backdrop-blur">
                        <i class="fas fa-arrow-left mr-2"></i>
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
                         class="bg-white rounded-xl border hover:shadow-lg transition-shadow">
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <!-- Date -->
                                    <div class="text-center">
                                        <div class="bg-blue-100 text-blue-600 rounded-lg px-3 py-2">
                                            <i class="fas fa-calendar-alt mb-1"></i>
                                            <p class="text-sm font-medium">
                                                {{ formatDate(player.month) }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <!-- Photo et infos -->
                                    <div class="flex items-center gap-4">
                                        <img :src="player.user.avatar || 'https://via.placeholder.com/80x80.png/00ee99?text=J'"
                                             class="w-16 h-16 rounded-full object-cover" />
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">
                                                {{ player.user.name }}
                                            </h3>
                                            <p class="text-gray-600">
                                                {{ player.user.pseudo }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-3">
                                    <Link :href="`/joueur-du-mois/${formatMonthUrl(player.month)}/stats`"
                                          class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                        <i class="fas fa-chart-line mr-2"></i>
                                        Stats
                                    </Link>
                                </div>
                            </div>

                            <!-- Motivation -->
                            <div v-if="player.reason" class="mt-4 bg-gray-50 rounded-lg p-4">
                                <p class="text-gray-700 italic">
                                    <i class="fas fa-quote-left text-gray-400 mr-2"></i>
                                    {{ player.reason }}
                                    <i class="fas fa-quote-right text-gray-400 ml-2"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="props.players.links.length > 3" class="mt-8">
                    <div class="flex justify-center gap-2">
                        <template v-for="link in props.players.links" :key="link.label">
                            <Link v-if="link.url" 
                                  :href="link.url"
                                  :class="link.active 
                                    ? 'bg-blue-500 text-white px-4 py-2 rounded-lg' 
                                    : 'bg-white text-gray-700 px-4 py-2 rounded-lg border hover:bg-gray-50'"
                                  v-html="link.label">
                            </Link>
                            <span v-else 
                                  class="bg-gray-100 text-gray-400 px-4 py-2 rounded-lg"
                                  v-html="link.label">
                            </span>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Message si aucun joueur -->
            <div v-else class="max-w-2xl mx-auto text-center py-16">
                <i class="fas fa-history text-gray-300 text-8xl mb-6"></i>
                <h2 class="text-3xl font-bold text-gray-700 mb-4">
                    Aucun historique disponible
                </h2>
                <p class="text-gray-600 mb-8">
                    Les joueurs du mois apparaîtront ici dès qu'ils seront désignés.
                </p>
                <Link href="/joueur-du-mois"
                      class="inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-trophy"></i>
                    Voir le joueur du mois actuel
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

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
