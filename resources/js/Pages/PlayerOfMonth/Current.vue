

<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    player: Object,
    stats: Array
});

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
        <div class="min-h-screen bg-gradient-to-b from-citron-50 via-citron-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-citron-50 font-sans">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-citron-50 dark:from-gray-900 dark:to-gray-800 py-16 px-4 border-b border-citron-200 dark:border-gray-700">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                    </div>

                    <div class="flex items-center absolute top-0 right-0 mr-4 mb-10 mt-2  gap-4">
                            <Link href="/dashboard"
                                  class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-lime-600 rounded-lg text-white font-bold text-sm shadow-lg hover:shadow-emerald-500/30 transform hover:scale-105 transition-all">
                                <span>Retour</span>
                                <i class="fas fa-chevron-right text-xs"></i>
                            </Link>
                    </div>
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-citron-500 dark:text-citron-300 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        
                        <div class="text-citron-400 dark:text-citron-500 text-sm tracking-widest">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl md:text-6xl font-thin text-gray-900 dark:text-citron-50 tracking-wider mb-3">JOUEUR DU MOIS</h1>
                    <div class="text-citron-500 dark:text-citron-300 text-lg tracking-widest font-light">Joueur exceptionnel du mois</div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-16">
                
                <!-- Message si aucun joueur trouvé -->
                <div v-if="!props.player" class="text-center py-32">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-8 md:p-12 max-w-2xl mx-auto border border-citron-200 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-citron-900/50 dark:to-citron-800/50 w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-citron-300 dark:border-citron-700">
                            <i class="fas fa-trophy text-citron-500 dark:text-citron-400 text-4xl md:text-5xl"></i>
                        </div>
                        <h2 class="text-2xl md:text-4xl font-thin text-gray-900 dark:text-citron-50 mb-4">AUCUN JOUEUR DU MOIS</h2>
                        <p class="text-gray-600 dark:text-citron-300 text-lg font-light">
                            Le joueur du mois n'a pas encore été sélectionné pour cette période.
                        </p>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div v-else class="space-y-12">
                    
                    <!-- Carte du joueur premium -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-6 md:p-10 border border-citron-200 dark:border-gray-700 shadow-lg">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <!-- Photo du joueur -->
                            <div class="relative">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full w-32 h-32 md:w-40 md:h-40 border-4 border-citron-300 dark:border-citron-600/50 shadow-2xl" 
                                     data-alt="Photo du joueur du mois" 
                                     :style='`background-image: url("${props.player?.image || props.player?.user?.avatar || "https://ui-avatars.com/api/?name=" + encodeURIComponent(props.player.user.name) + "&background=citron-500&color=white"}");`'>
                                </div>
                                <!-- Badge Joueur du mois -->
                                <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-citron-400 to-citron-500 dark:from-citron-500 dark:to-citron-600 rounded-full p-3 border-2 border-white dark:border-gray-800">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                            </div>
                            
                            <!-- Infos du joueur -->
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
                                    <h2 class="text-2xl md:text-4xl font-thin text-gray-900 dark:text-citron-50 tracking-wide">
                                        {{ props.player?.user?.name || 'Joueur du Mois' }}
                                    </h2>
                                    <div class="bg-gradient-to-r from-citron-100 to-citron-200 dark:from-citron-900/50 dark:to-citron-800/50 rounded-2xl px-4 md:px-6 py-2 border border-citron-300 dark:border-citron-700">
                                        <p class="text-citron-700 dark:text-citron-300 font-light tracking-wider text-sm md:text-base">JOUEUR DU MOIS</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-citron-300 text-lg font-light mb-4">
                                    Pseudo: "{{ props.player?.user?.pseudo || 'Champion' }}"
                                </p>
                                <p class="text-gray-700 dark:text-citron-200 text-xl font-light italic max-w-2xl">
                                    "{{ props.player?.reason || 'Le succès n\'est pas final, l\'échec n\'est pas fatal : c\'est le courage de continuer qui compte.' }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des Stats Premium -->
                    <div>
                        <h2 class="text-2xl md:text-3xl font-thin text-gray-900 dark:text-citron-50 tracking-wider mb-8 px-4">RÉSUMÉ DES STATISTIQUES</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg">
                                <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-gray-800 dark:to-gray-700 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-citron-300 dark:border-gray-600">
                                    <i class="fas fa-futbol text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                                </div>
                                <p class="text-gray-600 dark:text-citron-300 text-sm tracking-widest font-light mb-2">TOTAL BUTS</p>
                                <p class="text-4xl md:text-5xl font-thin text-gray-900 dark:text-citron-50">{{ totalStats.goals }}</p>
                            </div>
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg">
                                <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-gray-800 dark:to-gray-700 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-citron-300 dark:border-gray-600">
                                    <i class="fas fa-exchange-alt text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                                </div>
                                <p class="text-gray-600 dark:text-citron-300 text-sm tracking-widest font-light mb-2">TOTAL PASSES</p>
                                <p class="text-4xl md:text-5xl font-thin text-gray-900 dark:text-citron-50">{{ totalStats.assists }}</p>
                            </div>
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg">
                                <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-gray-800 dark:to-gray-700 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-citron-300 dark:border-gray-600">
                                    <i class="fas fa-calendar-check text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                                </div>
                                <p class="text-gray-600 dark:text-citron-300 text-sm tracking-widest font-light mb-2">TOTAL MATCHS</p>
                                <p class="text-4xl md:text-5xl font-thin text-gray-900 dark:text-citron-50">{{ totalStats.matches }}</p>
                            </div>
                            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg">
                                <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-citron-900/50 dark:to-citron-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-citron-300 dark:border-citron-700">
                                    <i class="fas fa-medal text-citron-500 dark:text-citron-400 text-2xl md:text-3xl"></i>
                                </div>
                                <p class="text-gray-600 dark:text-citron-300 text-sm tracking-widest font-light mb-2">TOTAL POINTS</p>
                                <p class="text-4xl md:text-5xl font-thin text-citron-600 dark:text-citron-400">{{ totalStats.points }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
