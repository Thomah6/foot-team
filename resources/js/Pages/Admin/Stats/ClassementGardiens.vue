<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    gardiens: Array
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-blue-50 via-blue-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-blue-50 font-sans relative">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-blue-50 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12 lg:py-16 px-4 border-b border-blue-200 dark:border-gray-700">
                <!-- Logo et titre -->
                <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-blue-200 to-indigo-200 dark:from-blue-800 dark:to-indigo-700 rounded-xl flex items-center justify-center border-2 border-blue-300 dark:border-blue-600">
                            <i class="fas fa-hand-paper text-blue-600 dark:text-blue-400 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-blue-700 dark:text-blue-400 font-semibold text-lg sm:text-xl tracking-wider hidden sm:block">FC DYNAMO</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center order-first sm:order-none">
                        <div class="text-blue-600 dark:text-blue-400 text-lg sm:text-xl font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 dark:text-blue-500 text-sm font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-20 sm:w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-4 sm:mb-6">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 dark:text-blue-50 tracking-tight mb-4">
                        CLASSEMENT DES GARDIENS
                    </h1>
                    <div class="text-gray-600 dark:text-blue-400 text-sm sm:text-lg font-medium tracking-wide">Les murailles imprenables</div>
                </div>
                
                <!-- Podium pour le top 3 -->
                <div v-if="props.gardiens.length >= 3" class="mb-8 md:mb-12">
                    <div class="relative">
                        <!-- Podium Style Pyramide -->
                        <div class="flex flex-col items-center">
                            <!-- 1ère place -->
                            <div class="relative mb-6">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-400 to-indigo-500 dark:from-blue-600 dark:to-indigo-700 rounded-2xl transform rotate-3 scale-105 opacity-20"></div>
                                <div class="relative bg-gradient-to-br from-blue-400 to-indigo-500 dark:from-blue-600 dark:to-indigo-700 rounded-2xl p-6 md:p-8 shadow-2xl border-2 border-blue-400/30 dark:border-blue-600/30">
                                    <div class="flex items-center gap-4 md:gap-6">
                                        <div class="relative">
                                            <img :src="props.gardiens[0].avatar ? '/storage/' + props.gardiens[0].avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(props.gardiens[0].name)}&background=blue-500&color=white&size=80`" 
                                                 class="w-20 h-20 md:w-24 md:h-24 rounded-full border-4 border-white shadow-xl" />
                                            <div class="absolute -top-2 -right-2 bg-gradient-to-br from-yellow-400 to-amber-500 text-white w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center font-bold text-sm md:text-base shadow-lg border-2 border-white">
                                                <i class="fas fa-crown"></i>
                                            </div>
                                        </div>
                                        <div class="text-left">
                                            <p class="text-gray-900 dark:text-white font-bold text-xl md:text-2xl mb-1">{{ props.gardiens[0].name }}</p>
                                            <p class="text-gray-600 dark:text-blue-100 text-sm md:text-base mb-2">{{ props.gardiens[0].pseudo }}</p>
                                            <div class="bg-blue-100 dark:bg-white/20 backdrop-blur-sm rounded-xl px-3 py-2 inline-block">
                                                <p class="text-blue-700 dark:text-white font-bold text-lg md:text-xl">{{ props.gardiens[0].stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0 }} clean sheets</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-2 text-center hidden sm:block">ZÉRO BUTS</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-gray-100 dark:divide-gray-700">
                        <div v-for="(gardien, index) in props.gardiens" 
                             :key="gardien.id"
                             class="px-4 md:px-8 py-3 md:py-6 hover:bg-blue-50/30 dark:hover:bg-gray-700/30 transition-all duration-300"
                             :class="index < 3 ? 'bg-gradient-to-r from-blue-50/20 to-indigo-50/20 dark:from-gray-700/20 dark:to-gray-600/20' : ''">
                            
                            <div class="grid grid-cols-12 gap-2 md:gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-8 h-8 md:w-12 md:h-12 rounded-full font-bold text-sm md:text-lg"
                                         :class="index === 0 ? 'bg-gradient-to-br from-blue-500 to-indigo-500 text-white' : 
                                                 index === 1 ? 'bg-gradient-to-br from-gray-200 to-gray-300 text-gray-700' : 
                                                 index === 2 ? 'bg-gradient-to-br from-blue-300 to-blue-400 text-white' : 
                                                 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-2 md:gap-4">
                                    <div class="relative flex-shrink-0">
                                        <img :src="gardien.avatar ? '/storage/' + gardien.avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(gardien.name)}&background=blue-500&color=white&size=64`" 
                                             class="w-10 h-10 md:w-14 md:h-14 rounded-full object-cover border-2 border-gray-300 dark:border-gray-600" />
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-blue-50 uppercase tracking-wide text-xs md:text-lg truncate">{{ gardien.name }}</p>
                                        <p class="text-gray-600 dark:text-blue-400 text-xs md:text-sm font-medium truncate">{{ gardien.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Moyenne buts encaissés -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-xl px-2 md:px-4 py-1.5 md:py-3 border border-blue-200 dark:border-blue-700">
                                        <span class="text-gray-900 dark:text-blue-50 font-bold text-sm md:text-2xl">
                                            {{ (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0) > 0 ? 
                                                (gardien.total_goals_against / (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0)).toFixed(1) : '0.0' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Matchs (mobile caché) -->
                                <div class="col-span-2 text-center text-gray-700 dark:text-blue-400 font-semibold text-sm md:text-lg hidden sm:block">
                                    {{ gardien.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Clean sheets (mobile caché) -->
                                <div class="col-span-2 text-center hidden sm:block">
                                    <span class="text-gray-900 dark:text-blue-50 font-bold text-sm md:text-lg">
                                        {{ gardien.stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques du bas -->
                <div v-if="props.gardiens.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-8 mt-8 md:mt-12">
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-blue-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/50 dark:to-indigo-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-blue-300 dark:border-blue-600">
                            <i class="fas fa-trophy text-blue-600 dark:text-blue-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-blue-600 dark:text-blue-400">
                            {{ props.gardiens[0] ? (props.gardiens[0].stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0) : 0 }}
                        </p>
                        <p class="text-gray-600 dark:text-blue-400 text-xs md:text-sm tracking-wide font-semibold mt-2">CLEAN SHEETS DU LEADER</p>
                        <p class="text-gray-900 dark:text-blue-50 mt-2 font-bold text-sm md:text-base">{{ props.gardiens[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-blue-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/50 dark:to-indigo-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-blue-300 dark:border-blue-600">
                            <i class="fas fa-shield-alt text-blue-600 dark:text-blue-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-blue-50">
                            {{ props.gardiens.reduce((sum, g) => sum + (g.stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0), 0) }}
                        </p>
                        <p class="text-gray-600 dark:text-blue-400 text-xs md:text-sm tracking-wide font-semibold mt-2">TOTAL CLEAN SHEETS</p>
                    </div>

                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-4 md:p-8 text-center border border-blue-200/50 dark:border-gray-700 shadow-lg">
                        <div class="bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/50 dark:to-indigo-800/50 w-16 h-16 md:w-20 md:h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 md:mb-6 border-2 border-blue-300 dark:border-blue-600">
                            <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl md:text-3xl"></i>
                        </div>
                        <p class="text-2xl md:text-4xl font-bold text-gray-900 dark:text-blue-50">{{ props.gardiens.length }}</p>
                        <p class="text-gray-600 dark:text-blue-400 text-xs md:text-sm tracking-wide font-semibold mt-2">GARDIENS CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="text-center mt-8 md:mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-2 md:gap-3 bg-gray-900 dark:bg-blue-600 text-white px-6 md:px-10 py-3 md:py-5 rounded-2xl font-bold hover:bg-gray-800 dark:hover:bg-blue-700 transition-all duration-300 tracking-wider text-sm md:text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">RETOUR AUX CLASSEMENTS</span>
                        <span class="sm:hidden">RETOUR</span>
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>