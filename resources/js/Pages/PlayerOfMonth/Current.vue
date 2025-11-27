<template>
    <div class="min-h-screen bg-gradient-to-b from-yellow-50 to-white">
        <!-- Header -->
        <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white">
            <div class="container mx-auto px-4 py-12">
                <div class="text-center">
                    <h1 class="text-5xl font-black mb-4 flex items-center justify-center gap-3">
                        <i class="fas fa-trophy"></i>
                        Joueur du Mois
                    </h1>
                    <p class="text-yellow-100 text-xl">
                        Le meilleur joueur basé sur les performances mensuelles
                    </p>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-4 py-8">
            <!-- Joueur du mois actuel -->
            <div v-if="props.player" class="max-w-4xl mx-auto">
                <!-- Carte principale du vainqueur -->
                <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-3xl p-8 text-white shadow-2xl mb-8">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                        <div class="flex-1 text-center md:text-left">
                            <div class="text-6xl mb-4">🏆</div>
                            <h2 class="text-4xl font-bold mb-2">
                                {{ props.player.user.name }}
                            </h2>
                            <p class="text-yellow-100 text-xl mb-6">
                                {{ props.player.user.pseudo }}
                            </p>
                            <div class="bg-white/20 rounded-xl p-4 backdrop-blur">
                                <p class="text-lg font-medium mb-2">
                                    <i class="fas fa-quote-left mr-2"></i>
                                    {{ props.player.reason }}
                                    <i class="fas fa-quote-right ml-2"></i>
                                </p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <img :src="props.player.user.avatar || 'https://via.placeholder.com/200x200.png/FFD700?text=1'"
                                 class="w-40 h-40 rounded-full border-4 border-white shadow-xl" />
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <Link href="/joueur-du-mois/historique"
                          class="bg-white rounded-xl p-6 border hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-history text-3xl text-blue-500 mb-3"></i>
                        <h3 class="text-lg font-semibold mb-2">Voir l'historique</h3>
                        <p class="text-gray-600">Consultez tous les joueurs du mois précédents</p>
                    </Link>

                    <Link href="/classements/general"
                          class="bg-white rounded-xl p-6 border hover:shadow-lg transition-shadow text-center">
                        <i class="fas fa-chart-bar text-3xl text-green-500 mb-3"></i>
                        <h3 class="text-lg font-semibold mb-2">Voir le classement</h3>
                        <p class="text-gray-600">Consultez le classement mensuel complet</p>
                    </Link>
                </div>
            </div>

            <!-- Message si aucun joueur du mois -->
            <div v-else class="max-w-2xl mx-auto text-center py-16">
                <i class="fas fa-trophy text-gray-300 text-8xl mb-6"></i>
                <h2 class="text-3xl font-bold text-gray-700 mb-4">
                    Aucun joueur du mois pour le moment
                </h2>
                <p class="text-gray-600 mb-8">
                    Le joueur du mois sera désigné automatiquement à la fin du mois.
                </p>
                <Link href="/classements/general"
                      class="inline-flex items-center gap-2 bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-chart-bar"></i>
                    Voir le classement actuel
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    player: Object
})
</script>
