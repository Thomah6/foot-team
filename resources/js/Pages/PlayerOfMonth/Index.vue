<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import BannerPlayerMonth from '@/Pages/Admin/News/BannerPlayerMonth.vue'

const props = defineProps({
    player: Object,
    stats: Object,
    previousPlayers: Array
});

const placeholder = "https://via.placeholder.com/400x500?text=Joueur";
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
                            <i class="fas fa-crown text-lime-600 dark:text-lime-400 text-lg sm:text-2xl"></i>
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
                        JOUEUR DU MOIS 
                    </h1>
                    <div class="text-gray-600 dark:text-gray-400 text-sm sm:text-lg font-medium tracking-wide">Joueur exceptionnel du mois</div>
                </div>
            </div>

            <!-- Contenu principal -->
            <div class="max-w-6xl mx-auto px-4 py-8 sm:py-12 relative z-10">
                
                <!-- Composant BannerPlayerMonth -->
                <BannerPlayerMonth 
                    :player="props.player" 
                    :stats="props.stats" 
                    :previousPlayers="props.previousPlayers" 
                />

                <!-- Boutons de navigation -->
                <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 mt-8 sm:mt-12 justify-center">
                    <Link href="/joueur-du-mois/historique"
                          class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 dark:from-lime-600 dark:to-emerald-600 text-white px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 dark:hover:from-lime-700 dark:hover:to-emerald-700 transition-all duration-300 tracking-wider text-sm sm:text-base lg:text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30">
                        <i class="fas fa-history"></i>
                        <span>VOIR L'HISTORIQUE</span>
                    </Link>
                    
                    <Link href="/classements"
                          class="inline-flex items-center gap-2 sm:gap-3 bg-gradient-to-r from-gray-500 to-gray-600 dark:from-gray-600 dark:to-gray-700 text-white px-6 sm:px-8 lg:px-10 py-3 sm:py-4 lg:py-5 rounded-2xl font-bold hover:from-gray-600 hover:to-gray-700 dark:hover:from-gray-700 dark:hover:to-gray-800 transition-all duration-300 tracking-wider text-sm sm:text-base lg:text-lg shadow-lg hover:shadow-xl">
                        <i class="fas fa-arrow-left"></i>
                        <span class="hidden sm:inline">RETOUR AUX CLASSEMENTS</span>
                        <span class="sm:hidden">CLASSEMENTS</span>
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>
