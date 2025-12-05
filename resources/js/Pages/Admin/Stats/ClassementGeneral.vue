<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    classement: Array,
    current_month: String,
    is_player_of_month: Boolean
})

const format = (number) => {
    return parseFloat(number).toFixed(1);
}

const formatMonth = (month) => {
    const date = new Date(month + '-01');
    return date.toLocaleDateString('fr-FR', { 
        month: 'long', 
        year: 'numeric' 
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-white font-sans relative">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12 lg:py-16 px-4 border-b border-gray-200 dark:border-gray-700">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                     </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-emerald-600 dark:text-emerald-400 text-lg sm:text-xl font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm sm:text-base font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-6 sm:mb-8">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-4 bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                        CLASSEMENT GÉNÉRAL
                    </h1>
                    <div class="text-gray-600 dark:text-gray-400 text-base sm:text-lg font-medium tracking-wide">Points = Buts + Passes/2</div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="max-w-5xl mx-auto px-4 py-8 sm:py-12 relative z-10">
                
                <!-- Message si aucun classement -->
                <div v-if="props.classement.length === 0" class="text-center py-16 sm:py-24 lg:py-32">
                    <div class="mb-6 sm:mb-8">
                        <i class="fas fa-medal text-lime-200 dark:text-lime-800 text-5xl sm:text-6xl lg:text-7xl mb-6 sm:mb-8"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-600 dark:text-gray-400 mb-4">AUCUNE DONNÉE</p>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm sm:text-base">Aucun combattant n'a encore marqué de points</p>
                </div>

                <!-- Podium pour le top 3 -->
                <div v-if="props.classement.length >= 3" class="mb-12 sm:mb-16">
                    <div class="flex justify-center items-end gap-4 sm:gap-6 lg:gap-8 mb-6 sm:mb-8">
                        <!-- 2ème place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-700 dark:to-slate-600 w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 border-2 border-slate-300 dark:border-slate-600 shadow-lg">
                                <span class="text-3xl sm:text-4xl lg:text-4xl font-bold text-slate-700 dark:text-slate-300">2</span>
                            </div>
                            <img :src="props.classement[1].avatar || 'https://via.placeholder.com/100x100.png/C0C0C0?text=2'" 
                                 class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full border-3 border-slate-300 dark:border-slate-600 mb-2" />
                            <p class="text-gray-900 dark:text-white font-bold text-sm sm:text-base lg:text-lg">{{ props.classement[1].name }}</p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-medium">{{ props.classement[1].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-medium">{{ props.classement[1].points }} pts</p>
                            </div>
                        </div>

                        <!-- 1ère place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-lime-400 to-emerald-500 dark:from-lime-600 dark:to-emerald-600 w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 border-2 border-lime-400/50 dark:border-lime-600/50 shadow-lg">
                                <span class="text-4xl sm:text-5xl lg:text-5xl font-bold text-white">1</span>
                            </div>
                            <img :src="props.classement[0].avatar || 'https://via.placeholder.com/100x100.png/FFD700?text=1'" 
                                 class="w-16 h-16 sm:w-18 sm:h-18 lg:w-20 lg:h-20 rounded-full border-4 border-lime-400/50 dark:border-lime-600/50 mb-2" />
                            <p class="text-gray-900 dark:text-white font-bold text-lg sm:text-xl lg:text-2xl">{{ props.classement[0].name }}</p>
                            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base font-medium">{{ props.classement[0].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-lime-600 dark:text-lime-400 text-sm sm:text-base font-bold">{{ props.classement[0].points }} pts</p>
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm">B: {{ props.classement[0].total_goals }} | P: {{ props.classement[0].total_assists }}</p>
                            </div>
                        </div>

                        <!-- 3ème place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-amber-300 to-amber-400 dark:from-amber-600 dark:to-amber-500 w-20 h-20 sm:w-24 sm:h-24 lg:w-28 lg:h-28 rounded-2xl flex items-center justify-center mb-3 sm:mb-4 border-2 border-amber-300/50 dark:border-amber-600/50 shadow-lg">
                                <span class="text-3xl sm:text-4xl lg:text-4xl font-bold text-white">3</span>
                            </div>
                            <img :src="props.classement[2].avatar || 'https://via.placeholder.com/100x100.png/CD7F32?text=3'" 
                                 class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 rounded-full border-3 border-amber-300 dark:border-amber-600 mb-2" />
                            <p class="text-gray-900 dark:text-white font-bold text-sm sm:text-base lg:text-lg">{{ props.classement[2].name }}</p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-medium">{{ props.classement[2].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-medium">{{ props.classement[2].points }} pts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau complet -->
                <div v-if="props.classement.length > 0" class="bg-white dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl overflow-hidden border-2 border-lime-200/50 dark:border-lime-800/30 shadow-xl">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-gradient-to-r from-lime-50 to-emerald-50 dark:from-lime-900/20 dark:to-emerald-900/20 px-4 sm:px-6 lg:px-8 py-4 sm:py-6 border-b border-lime-200 dark:border-lime-800">
                        <div class="grid grid-cols-12 gap-2 sm:gap-4 lg:gap-6 text-gray-700 dark:text-gray-300 text-xs sm:text-sm font-semibold tracking-wide">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5 hidden sm:block">COMBATTANT</div>
                            <div class="col-span-5 sm:hidden">JOUEUR</div>
                            <div class="col-span-2 text-center">POINTS</div>
                            <div class="col-span-2 text-center hidden lg:block">MATCHS</div>
                            <div class="col-span-2 text-center hidden lg:block">MOY</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div v-for="(joueur, index) in props.classement" 
                             :key="joueur.id"
                             class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6 hover:bg-lime-50/30 dark:hover:bg-lime-900/20 transition-all duration-300"
                             :class="index < 3 ? 'bg-gradient-to-r from-lime-50/20 to-emerald-50/20 dark:from-lime-900/10 dark:to-emerald-900/10' : ''">
                            
                            <div class="grid grid-cols-12 gap-2 sm:gap-4 lg:gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 rounded-full font-bold text-sm sm:text-base lg:text-lg"
                                         :class="index === 0 ? 'bg-gradient-to-br from-lime-500 to-emerald-500 text-white' : 
                                                 index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-300 dark:from-slate-600 dark:to-slate-500 text-gray-700 dark:text-gray-300' : 
                                                 index === 2 ? 'bg-gradient-to-br from-amber-300 to-amber-400 dark:from-amber-600 dark:to-amber-500 text-white' : 
                                                 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-2 sm:gap-3 lg:gap-4">
                                    <div class="relative">
                                        <img :src="joueur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=G'"
                                             class="w-10 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" />
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold text-gray-900 dark:text-white uppercase tracking-wide text-sm sm:text-base lg:text-lg truncate">{{ joueur.name }}</p>
                                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm font-medium truncate">{{ joueur.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-lime-50 dark:bg-lime-900/30 rounded-xl px-2 sm:px-3 lg:px-4 py-2 sm:py-3 border border-lime-200 dark:border-lime-700">
                                        <span class="text-gray-900 dark:text-white font-bold text-lg sm:text-xl lg:text-2xl">{{ joueur.points }}</span>
                                    </div>
                                </div>

                                <!-- Matchs -->
                                <div class="col-span-2 text-center text-gray-700 dark:text-gray-300 font-semibold text-base sm:text-lg">
                                    {{ joueur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Moyenne -->
                                <div class="col-span-2 text-center">
                                    <span class="text-gray-900 dark:text-white font-bold text-base sm:text-lg">
                                        {{ (joueur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                            (joueur.points / (joueur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques du bas -->
                <div v-if="props.classement.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mt-8 sm:mt-12">
                    <div class="bg-gradient-to-br from-white to-lime-50 dark:from-gray-800/80 dark:to-lime-900/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border-2 border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-trophy text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-lime-600 dark:text-lime-400">{{ props.classement[0]?.points || 0 }}</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">POINTS DU LEADER</p>
                        <p class="text-gray-900 dark:text-white mt-2 font-bold text-sm sm:text-base">{{ props.classement[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-white to-lime-50 dark:from-gray-800/80 dark:to-lime-900/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border-2 border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-chart-line text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                            {{ props.classement.reduce((sum, j) => sum + j.points, 0) }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">TOTAL POINTS CLUB</p>
                    </div>

                    <div class="bg-gradient-to-br from-white to-lime-50 dark:from-gray-800/80 dark:to-lime-900/20 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border-2 border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-users text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">{{ props.classement.length }}</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">COMBATTANTS CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="text-center mt-8 sm:mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 dark:from-lime-600 dark:to-emerald-600 text-white px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 dark:hover:from-lime-700 dark:hover:to-emerald-700 transition-all duration-300 tracking-wider text-sm sm:text-base lg:text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">RETOUR AUX CLASSEMENTS</span>
                        <span class="sm:hidden">RETOUR</span>
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>