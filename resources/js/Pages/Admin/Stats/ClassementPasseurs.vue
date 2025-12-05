<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    passeurs: Array
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-white font-sans relative">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12 lg:py-16 px-4 border-b border-gray-200 dark:border-gray-700">
                <!-- Logo et titre -->
                <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 rounded-xl flex items-center justify-center border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-exchange-alt text-lime-600 dark:text-lime-400 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-lime-700 dark:text-lime-400 font-semibold text-lg sm:text-xl tracking-wider hidden sm:block">FC DYNAMO</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center order-first sm:order-none">
                        <div class="text-emerald-600 dark:text-emerald-400 text-lg sm:text-xl font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 dark:text-gray-400 text-sm font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-20 sm:w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-4 sm:mb-6">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-white tracking-tight mb-4">
                        CLASSEMENT DES PASSEURS
                    </h1>
                    <div class="text-gray-600 dark:text-gray-400 text-sm sm:text-lg font-medium tracking-wide">Les architectes du jeu</div>
                </div>
                
                <!-- Podium pour le top 3 -->
                <div v-if="props.passeurs.length >= 3" class="mb-8 md:mb-12">
                    <div class="relative">
                        <!-- Podium Style Pyramide -->
                        <div class="flex flex-col items-center">
                            <!-- 1ère place -->
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gradient-to-br from-lime-400 to-emerald-500 dark:from-lime-600 dark:to-emerald-700 rounded-2xl transform rotate-3 scale-105 opacity-20"></div>
                                <div class="relative bg-gradient-to-br from-lime-400 to-emerald-500 dark:from-lime-600 dark:to-emerald-700 rounded-2xl p-4 sm:p-6 md:p-8 shadow-2xl border-2 border-lime-400/30 dark:border-lime-600/30">
                                    <div class="flex items-center gap-4 md:gap-6">
                                        <div class="relative">
                                            <img :src="props.passeurs[0].avatar ? '/storage/' + props.passeurs[0].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.passeurs[0].name)}&background=lime-500&color=white&size=80`" 
                                                 class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full border-4 border-white shadow-xl" />
                                            <div class="absolute -top-2 -right-2 bg-gradient-to-br from-yellow-400 to-amber-500 text-white w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-xs sm:text-sm md:text-base shadow-lg border-2 border-white">
                                                <i class="fas fa-crown text-xs sm:text-sm md:text-base"></i>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-gray-900 dark:text-white font-bold text-lg sm:text-xl md:text-2xl mb-1">{{ props.passeurs[0].name }}</p>
                                            <p class="text-gray-600 dark:text-gray-400 text-sm sm:text-base md:text-base mb-2">{{ props.passeurs[0].pseudo }}</p>
                                            <div class="bg-lime-100 dark:bg-lime-900/30 backdrop-blur-sm rounded-xl px-2 sm:px-3 py-2 inline-block">
                                                <p class="text-lime-700 dark:text-lime-400 font-bold text-base sm:text-lg md:text-xl">{{ props.passeurs[0].total_assists }} passes</p>
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
                                                <img :src="props.passeurs[1].avatar ? '/storage/' + props.passeurs[1].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.passeurs[1].name)}&background=gray-500&color=white&size=64`" 
                                                     class="w-16 h-16 md:w-20 md:h-20 rounded-full border-3 border-white shadow-lg" />
                                                <div class="absolute -top-1 -right-1 bg-gray-600 text-white w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-lg border-2 border-white">
                                                    2
                                                </div>
                                            </div>
                                            <div class="text-left flex-1">
                                                <p class="text-gray-900 dark:text-white font-bold text-base sm:text-lg md:text-xl mb-1">{{ props.passeurs[1].name }}</p>
                                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm md:text-sm mb-2">{{ props.passeurs[1].pseudo }}</p>
                                                <div class="bg-gray-100 dark:bg-gray-700 backdrop-blur-sm rounded-lg px-2 py-1 inline-block">
                                                    <p class="text-gray-700 dark:text-gray-300 font-bold text-sm sm:text-base md:text-lg">{{ props.passeurs[1].total_assists }} passes</p>
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
                                                <img :src="props.passeurs[2].avatar ? '/storage/' + props.passeurs[2].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.passeurs[2].name)}&background=amber-500&color=white&size=64`" 
                                                     class="w-16 h-16 md:w-20 md:h-20 rounded-full border-3 border-white shadow-lg" />
                                                <div class="absolute -top-1 -right-1 bg-amber-600 text-white w-6 h-6 md:w-8 md:h-8 rounded-full flex items-center justify-center font-bold text-xs md:text-sm shadow-lg border-2 border-white">
                                                    3
                                                </div>
                                            </div>
                                            <div class="text-left flex-1">
                                                <p class="text-gray-900 dark:text-white font-bold text-base sm:text-lg md:text-xl mb-1">{{ props.passeurs[2].name }}</p>
                                                <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm md:text-sm mb-2">{{ props.passeurs[2].pseudo }}</p>
                                                <div class="bg-amber-100 dark:bg-amber-900/30 backdrop-blur-sm rounded-lg px-2 py-1 inline-block">
                                                    <p class="text-amber-700 dark:text-amber-400 font-bold text-sm sm:text-base md:text-lg">{{ props.passeurs[2].total_assists }} passes</p>
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
            <div class="max-w-5xl mx-auto px-4 py-8 sm:py-12 relative z-10">

                <!-- Message si aucun classement -->
                <div v-if="props.passeurs.length === 0" class="text-center py-16 sm:py-24 lg:py-32">
                    <div class="mb-6 sm:mb-8">
                        <i class="fas fa-exchange-alt text-lime-400 dark:text-lime-600 text-4xl md:text-5xl lg:text-7xl mb-6 sm:mb-8"></i>
                    </div>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-600 dark:text-gray-400 mb-4">AUCUNE DONNÉE</p>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-sm sm:text-base">Aucun architecte n'a encore délivré de passe décisive</p>
                </div>

                <!-- Tableau complet -->
                <div v-if="props.passeurs.length > 0" class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-lime-200/50 dark:border-lime-800/30 shadow-xl">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-gradient-to-r from-lime-50 to-emerald-50 dark:from-lime-900/20 dark:to-emerald-900/20 px-4 md:px-8 py-4 md:py-6 border-b border-lime-200 dark:border-lime-800">
                        <div class="grid grid-cols-12 gap-2 md:gap-6 text-gray-700 dark:text-gray-300 text-xs md:text-sm font-semibold tracking-wide">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5">ARCHITECTE</div>
                            <div class="col-span-2 text-center">PASSES</div>
                            <div class="col-span-2 text-center hidden sm:block">MATCHS</div>
                            <div class="col-span-2 text-center hidden sm:block">MOY</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div v-for="(passeur, index) in props.passeurs" 
                             :key="passeur.id"
                             class="px-4 md:px-8 py-3 md:py-6 hover:bg-lime-50/30 dark:hover:bg-lime-900/20 transition-all duration-300"
                             :class="index < 3 ? 'bg-gradient-to-r from-lime-50/20 to-emerald-50/20 dark:from-lime-900/10 dark:to-emerald-900/10' : ''">
                            
                            <div class="grid grid-cols-12 gap-2 md:gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-8 h-8 md:w-12 md:h-12 rounded-full font-bold text-sm md:text-lg"
                                         :class="index === 0 ? 'bg-gradient-to-br from-lime-500 to-emerald-500 text-white' : 
                                                 index === 1 ? 'bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-500 text-gray-700 dark:text-gray-300' : 
                                                 index === 2 ? 'bg-gradient-to-br from-amber-300 to-amber-400 dark:from-amber-600 dark:to-amber-500 text-white' : 
                                                 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-2 md:gap-4">
                                    <div class="relative flex-shrink-0">
                                        <img :src="passeur.avatar ? '/storage/' + passeur.avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(passeur.name)}&background=lime-500&color=white&size=64`" 
                                             class="w-10 h-10 md:w-14 md:h-14 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-white uppercase tracking-wide text-xs md:text-lg truncate">{{ passeur.name }}</p>
                                        <p class="text-gray-600 dark:text-gray-400 text-xs md:text-sm font-medium truncate">{{ passeur.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Passes -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-gradient-to-br from-lime-500 to-emerald-500 dark:from-lime-600 dark:to-emerald-600 rounded-xl px-2 md:px-4 py-1.5 md:py-3 shadow-lg">
                                        <span class="text-white font-bold text-sm md:text-2xl">{{ passeur.total_assists }}</span>
                                    </div>
                                </div>

                                <!-- Matchs (mobile caché) -->
                                <div class="col-span-2 text-center text-gray-700 dark:text-gray-300 font-semibold text-sm md:text-lg hidden sm:block">
                                    {{ passeur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Moyenne (mobile caché) -->
                                <div class="col-span-2 text-center hidden sm:block">
                                    <span class="text-gray-900 dark:text-white font-bold text-sm md:text-lg">
                                        {{ (passeur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                            (passeur.total_assists / (passeur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques du bas -->
                <div v-if="props.passeurs.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mt-8 sm:mt-12">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-trophy text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-lime-600 dark:text-lime-400">{{ props.passeurs[0]?.total_assists || 0 }}</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">PASSES DU LEADER</p>
                        <p class="text-gray-900 dark:text-white mt-2 font-bold text-sm sm:text-base">{{ props.passeurs[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-chart-line text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                            {{ props.passeurs.reduce((sum, p) => sum + p.total_assists, 0) }}
                        </p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">TOTAL PASSES CLUB</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 sm:p-6 lg:p-8 text-center border border-lime-200/50 dark:border-lime-800/30 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-lime-900/30 dark:to-emerald-900/30 w-16 h-16 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 sm:mb-6 border-2 border-lime-300 dark:border-lime-600">
                            <i class="fas fa-users text-lime-600 dark:text-lime-400 text-2xl sm:text-3xl"></i>
                        </div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">{{ props.passeurs.length }}</p>
                        <p class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm tracking-wide font-semibold mt-2">ARCHITECTES CLASSÉS</p>
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