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
        <div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-white text-slate-900 font-sans">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 py-16 px-4 border-b border-slate-200">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center border border-slate-300">
                            <i class="fas fa-trophy text-slate-700 text-2xl"></i>
                        </div>
                        <div class="text-slate-700 font-light text-xl tracking-wider">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-600 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-slate-500 text-sm tracking-widest">SAISON 2024</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-thin text-slate-900 tracking-wider mb-3">JOUEUR DU MOIS</h1>
                    <div class="text-slate-600 text-lg tracking-widest font-light">Joueur exceptionnel du mois</div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-16">
                
                <!-- Message si aucun joueur trouvé -->
                <div v-if="!props.player" class="text-center py-32">
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-12 max-w-2xl mx-auto border border-slate-200 shadow-lg">
                        <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-yellow-300">
                            <i class="fas fa-trophy text-yellow-600 text-5xl"></i>
                        </div>
                        <h2 class="text-4xl font-thin text-slate-900 mb-4">AUCUN JOUEUR DU MOIS</h2>
                        <p class="text-slate-600 text-lg font-light">
                            Le joueur du mois n'a pas encore été sélectionné pour cette période.
                        </p>
                    </div>
                </div>

                <!-- Contenu principal -->
                <div v-else class="space-y-12">
                    
                    <!-- Carte du joueur premium -->
                    <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-10 border border-slate-200 shadow-lg">
                        <div class="flex flex-col md:flex-row items-center gap-8">
                            <!-- Photo du joueur -->
                            <div class="relative">
                                <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full min-h-40 w-40 border-4 border-yellow-600/50 shadow-2xl" 
                                     data-alt="Photo du joueur du mois" 
                                     :style='`background-image: url("${props.player?.image || "https://via.placeholder.com/200x200/FFD700?text=🏆"}");`'>
                                </div>
                                <!-- Badge Joueur du mois -->
                                <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-yellow-400 to-yellow-500 rounded-full p-3 border-2 border-white">
                                    <i class="fas fa-star text-white text-xl"></i>
                                </div>
                            </div>
                            
                            <!-- Infos du joueur -->
                            <div class="flex-1 text-center md:text-left">
                                <div class="flex flex-col md:flex-row items-center gap-4 mb-4">
                                    <h2 class="text-4xl font-thin text-slate-900 tracking-wide">
                                        {{ props.player?.user?.name || 'Joueur du Mois' }}
                                    </h2>
                                    <div class="bg-gradient-to-r from-yellow-100 to-yellow-200 rounded-2xl px-6 py-2 border border-yellow-300">
                                        <p class="text-yellow-700 font-light tracking-wider">JOUEUR DU MOIS</p>
                                    </div>
                                </div>
                                <p class="text-slate-600 text-lg font-light mb-4">
                                    Pseudo: "{{ props.player?.user?.pseudo || 'Champion' }}"
                                </p>
                                <p class="text-slate-700 text-xl font-light italic max-w-2xl">
                                    "{{ props.player?.reason || 'Le succès n\'est pas final, l\'échec n\'est pas fatal : c\'est le courage de continuer qui compte.' }}"
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Résumé des Stats Premium -->
                    <div>
                        <h2 class="text-3xl font-thin text-slate-900 tracking-wider mb-8 px-4">RÉSUMÉ DES STATISTIQUES</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                                <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                                    <i class="fas fa-futbol text-slate-600 text-3xl"></i>
                                </div>
                                <p class="text-slate-600 text-sm tracking-widest font-light mb-2">TOTAL BUTS</p>
                                <p class="text-5xl font-thin text-slate-900">{{ totalStats.goals }}</p>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                                <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                                    <i class="fas fa-exchange-alt text-slate-600 text-3xl"></i>
                                </div>
                                <p class="text-slate-600 text-sm tracking-widest font-light mb-2">TOTAL PASSES</p>
                                <p class="text-5xl font-thin text-slate-900">{{ totalStats.assists }}</p>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                                <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                                    <i class="fas fa-calendar-check text-slate-600 text-3xl"></i>
                                </div>
                                <p class="text-slate-600 text-sm tracking-widest font-light mb-2">TOTAL MATCHS</p>
                                <p class="text-5xl font-thin text-slate-900">{{ totalStats.matches }}</p>
                            </div>
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                                <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-300">
                                    <i class="fas fa-medal text-yellow-600 text-3xl"></i>
                                </div>
                                <p class="text-slate-600 text-sm tracking-widest font-light mb-2">TOTAL POINTS</p>
                                <p class="text-5xl font-thin text-yellow-600">{{ totalStats.points }}</p>
                            </div>
                        </div>
                    </div>

                    </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
