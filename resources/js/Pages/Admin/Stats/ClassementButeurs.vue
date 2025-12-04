<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    buteurs: Array
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-citron-50 via-citron-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-citron-50 font-sans relative">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-citron-50 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12 lg:py-16 px-4 border-b border-citron-200 dark:border-gray-700">
                <!-- Logo et titre -->
                <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-citron-200 to-emerald-200 dark:from-citron-800 dark:to-emerald-700 rounded-xl flex items-center justify-center border-2 border-citron-300 dark:border-citron-600">
                            <i class="fas fa-futbol text-citron-600 dark:text-citron-400 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-citron-700 dark:text-citron-400 font-semibold text-lg sm:text-xl tracking-wider hidden sm:block">FC DYNAMO</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center order-first sm:order-none">
                        <div class="text-citron-600 dark:text-citron-400 text-lg sm:text-xl font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 dark:text-citron-500 text-sm font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-20 sm:w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-4 sm:mb-6">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-citron-50 tracking-tight mb-4">
                        CLASSEMENT DES BUTEURS
                    </h1>
                    <div class="text-gray-600 dark:text-citron-400 text-sm sm:text-lg font-medium tracking-wide">Les prédateurs de surface</div>
                </div>
                
                <!-- Podium pour le top 3 -->
                <div v-if="props.buteurs.length >= 3" class="mb-8 md:mb-12">
                    <div class="relative">
                        <!-- Podium Style Pyramide -->
                        <div class="flex flex-col items-center">
                            <!-- 1ère place -->
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gradient-to-br from-citron-400 to-emerald-500 dark:from-citron-600 dark:to-emerald-700 rounded-2xl transform rotate-3 scale-105 opacity-20"></div>
                                <div class="relative bg-gradient-to-br from-citron-400 to-emerald-500 dark:from-citron-600 dark:to-emerald-700 rounded-2xl p-6 md:p-8 shadow-2xl border-2 border-citron-400/30 dark:border-citron-600/30">
                                    <div class="flex items-center gap-4 md:gap-6">
                                        <div class="relative">
                                            <img :src="props.buteurs[0].avatar ? '/storage/' + props.buteurs[0].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.buteurs[0].name)}&background=citron-500&color=white&size=80`" 
                                                 class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-white shadow-xl" />
                                            <div class="absolute -top-2 -right-2 bg-gradient-to-br from-yellow-400 to-amber-500 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-sm md:text-base shadow-lg border-2 border-white">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-gray-900 dark:text-white font-bold text-xl md:text-2xl mb-1">{{ props.buteurs[0].name }}</p>
                                            <p class="text-gray-600 dark:text-citron-100 text-sm md:text-base mb-2">{{ props.buteurs[0].pseudo }}</p>
                                            <div class="bg-citron-100 dark:bg-white/20 backdrop-blur-sm rounded-xl px-3 py-2 inline-block">
                                                <p class="text-citron-700 dark:text-white font-bold text-lg md:text-xl">{{ props.buteurs[0].total_goals }} buts</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- 2ème et 3ème place -->
                            <div class="flex flex-col sm:flex-row gap-4 md:gap-6 w-full max-w-2xl">
                                <!-- 2ème place -->
                                <div class="relative flex-1">
                                    <div class="absolute inset-0 bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 rounded-2xl transform -rotate-2 scale-105 opacity-20"></div>
                                    <div class="relative bg-gradient-to-br from-gray-300 to-gray-400 dark:from-gray-600 dark:to-gray-700 rounded-2xl p-4 md:p-6 shadow-xl border-2 border-gray-300/30 dark:border-gray-600/30">
                                        <div class="flex items-center gap-3 md:gap-4">
                                            <div class="relative">
                                                <img :src="props.buteurs[1].avatar ? '/storage/' + props.buteurs[1].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.buteurs[1].name)}&background=gray-500&color=white&size=64`" 
                                                     class="w-16 h-16 md:w-20 md:h-20 rounded-full border-3 border-white shadow-lg" />
                                                <div class="absolute -top-1 -right-1 bg-gray-600 text-white w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-lg border-2 border-white">
                                                    2
                                                </div>
                                            </div>
                                            <div class="text-left flex-1">
                                                <p class="text-gray-900 dark:text-white font-bold text-lg md:text-xl mb-1">{{ props.buteurs[1].name }}</p>
                                                <p class="text-gray-600 dark:text-gray-200 text-xs md:text-sm mb-2">{{ props.buteurs[1].pseudo }}</p>
                                                <div class="bg-gray-100 dark:bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 inline-block">
                                                    <p class="text-gray-700 dark:text-white font-bold text-base md:text-lg">{{ props.buteurs[1].total_goals }} buts</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 3ème place -->
                                <div class="relative flex-1">
                                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-amber-500 dark:from-amber-600 dark:to-amber-700 rounded-2xl transform rotate-2 scale-105 opacity-20"></div>
                                    <div class="relative bg-gradient-to-br from-amber-400 to-amber-500 dark:from-amber-600 dark:to-amber-700 rounded-2xl p-4 md:p-6 shadow-xl border-2 border-amber-400/30 dark:border-amber-600/30">
                                        <div class="flex items-center gap-3 md:gap-4">
                                            <div class="relative">
                                                <img :src="props.buteurs[2].avatar ? '/storage/' + props.buteurs[2].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.buteurs[2].name)}&background=amber-500&color=white&size=64`" 
                                                     class="w-16 h-16 md:w-20 md:h-20 rounded-full border-3 border-white shadow-lg" />
                                                <div class="absolute -top-1 -right-1 bg-amber-600 text-white w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-lg border-2 border-white">
                                                    3
                                                </div>
                                            </div>
                                            <div class="text-left flex-1">
                                                <p class="text-gray-900 dark:text-white font-bold text-lg md:text-xl mb-1">{{ props.buteurs[2].name }}</p>
                                                <p class="text-gray-600 dark:text-amber-100 text-xs md:text-sm mb-2">{{ props.buteurs[2].pseudo }}</p>
                                                <div class="bg-amber-100 dark:bg-white/20 backdrop-blur-sm rounded-lg px-2 py-1 inline-block">
                                                    <p class="text-amber-700 dark:text-white font-bold text-base md:text-lg">{{ props.buteurs[2].total_goals }} buts</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="max-w-5xl mx-auto px-4 py-12 relative z-10">

                <!-- Tableau complet -->
                <div v-if="props.buteurs.length > 0" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-citron-200/50 dark:border-gray-700 shadow-xl">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-gradient-to-r from-citron-50 to-emerald-50 dark:from-gray-800 dark:to-gray-700 px-4 md:px-8 py-4 md:py-6 border-b border-citron-200 dark:border-gray-600">
                        <div class="grid grid-cols-12 gap-2 md:gap-6 text-gray-700 dark:text-citron-400 text-xs md:text-sm font-semibold tracking-wide">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5">PRÉDATEUR</div>
                            <div class="col-span-2 text-center">BUTS</div>
                            <div class="col-span-2 text-center hidden sm:block">MATCHS</div>
                            <div class="col-span-2 text-center hidden sm:block">MOY</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div v-for="(buteur, index) in props.buteurs" 
                             :key="buteur.id"
                             class="px-4 md:px-8 py-3 md:py-6 hover:bg-citron-50/30 dark:hover:bg-gray-700/30 transition-all duration-300"
                             :class="index < 3 ? 'bg-gradient-to-r from-citron-50/20 to-emerald-50/20 dark:from-gray-700/20 dark:to-gray-600/20' : ''">
                            
                            <div class="grid grid-cols-12 gap-2 md:gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-8 h-8 md:w-12 md:h-12 rounded-full font-bold text-sm md:text-lg"
                                         :class="index === 0 ? 'bg-gradient-to-br from-citron-500 to-emerald-500 text-white' : 
                                                 index === 1 ? 'bg-gradient-to-br from-gray-200 to-gray-300 text-gray-700' : 
                                                 index === 2 ? 'bg-gradient-to-br from-amber-300 to-amber-400 text-white' : 
                                                 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-2 md:gap-4">
                                    <div class="relative flex-shrink-0">
                                        <img :src="buteur.avatar ? '/storage/' + buteur.avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(buteur.name)}&background=citron-500&color=white&size=64`" 
                                             class="w-10 h-10 md:w-14 md:h-14 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-citron-50 uppercase tracking-wide text-xs md:text-lg truncate">{{ buteur.name }}</p>
                                        <p class="text-gray-600 dark:text-citron-400 text-xs md:text-sm font-medium truncate">{{ buteur.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Buts -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-citron-50 dark:bg-citron-900/30 rounded-xl px-2 md:px-4 py-1.5 md:py-3 border border-citron-200 dark:border-citron-700">
                                        <span class="text-gray-900 dark:text-citron-50 font-bold text-sm md:text-2xl">{{ buteur.total_goals }}</span>
                                    </div>
                                </div>

                                <!-- Matchs (mobile caché) -->
                                <div class="col-span-2 text-center text-gray-700 dark:text-citron-400 font-semibold text-sm md:text-lg hidden sm:block">
                                    {{ buteur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Moyenne (mobile caché) -->
                                <div class="col-span-2 text-center hidden sm:block">
                                    <span class="text-gray-900 dark:text-citron-50 font-bold text-sm md:text-lg">
                                        {{ (buteur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                            (buteur.total_goals / (buteur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques du bas -->
                <div v-if="props.buteurs.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 mt-8 md:mt-12">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-citron-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-citron-100 to-emerald-100 dark:from-citron-900/50 dark:to-emerald-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-citron-300 dark:border-citron-600">
                            <i class="fas fa-trophy text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-citron-600 dark:text-citron-400">{{ props.buteurs[0]?.total_goals || 0 }}</p>
                        <p class="text-gray-600 dark:text-citron-400 text-xs md:text-sm tracking-wide font-semibold mt-2">BUTS DU LEADER</p>
                        <p class="text-gray-900 dark:text-citron-50 mt-2 font-bold text-sm md:text-base">{{ props.buteurs[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-citron-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-citron-100 to-emerald-100 dark:from-citron-900/50 dark:to-emerald-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-citron-300 dark:border-citron-600">
                            <i class="fas fa-chart-line text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-citron-50">
                            {{ props.buteurs.reduce((sum, b) => sum + b.total_goals, 0) }}
                        </p>
                        <p class="text-gray-600 dark:text-citron-400 text-xs md:text-sm tracking-wide font-semibold mt-2">TOTAL BUTS CLUB</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-citron-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-citron-100 to-emerald-100 dark:from-citron-900/50 dark:to-emerald-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-citron-300 dark:border-citron-600">
                            <i class="fas fa-users text-citron-600 dark:text-citron-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-citron-50">{{ props.buteurs.length }}</p>
                        <p class="text-gray-600 dark:text-citron-400 text-xs md:text-sm tracking-wide font-semibold mt-2">PRÉDATEURS CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="text-center mt-8 md:mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-2 md:gap-3 bg-gray-900 dark:bg-citron-600 text-white px-6 md:px-10 py-3 md:py-5 rounded-2xl font-bold hover:bg-gray-800 dark:hover:bg-citron-700 transition-all duration-300 tracking-wider text-sm md:text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">RETOUR AUX CLASSEMENTS</span>
                        <span class="sm:hidden">RETOUR</span>
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>