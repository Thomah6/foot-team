<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-white text-gray-900 font-sans">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 py-16 px-4 border-b border-gray-200">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                     
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-emerald-600 text-lg font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 text-sm font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-gray-900 tracking-tight mb-4 bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                        TOUS LES CLASSEMENTS
                    </h1>
                    <div class="text-gray-600 text-lg font-medium tracking-wide">STATISTIQUES ET PERFORMANCES</div>
                </div>
            </div>

            <!-- Grid des classements -->
            <div class="max-w-6xl mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Classement Général -->
                    <Link :href="route('stats.classement.general')" 
                          class="group bg-white backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg hover:shadow-xl hover:shadow-lime-500/20 transition-all duration-300 hover:-translate-y-2 hover:border-lime-300">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                            <i class="fas fa-medal text-lime-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">CLASSEMENT GÉNÉRAL</h3>
                        <p class="text-gray-600 mb-6 font-medium">Points = Buts + Passes/2</p>
                        <div class="text-lime-600 font-semibold tracking-wide group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                            Voir le classement 
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </Link>

                    <!-- Classement Buteurs -->
                    <Link :href="route('stats.classement.buteurs')" 
                          class="group bg-white backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg hover:shadow-xl hover:shadow-lime-500/20 transition-all duration-300 hover:-translate-y-2 hover:border-lime-300">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                            <i class="fas fa-futbol text-lime-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">CLASSEMENT BUTEURS</h3>
                        <p class="text-gray-600 mb-6 font-medium">Les meilleurs prédateurs de surface</p>
                        <div class="text-lime-600 font-semibold tracking-wide group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                            Voir les buteurs 
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </Link>

                    <!-- Classement Passeurs -->
                    <Link :href="route('stats.classement.passeurs')" 
                          class="group bg-white backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg hover:shadow-xl hover:shadow-lime-500/20 transition-all duration-300 hover:-translate-y-2 hover:border-lime-300">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                            <i class="fas fa-exchange-alt text-lime-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">CLASSEMENT PASSEURS</h3>
                        <p class="text-gray-600 mb-6 font-medium">Vision de jeu et générosité</p>
                        <div class="text-lime-600 font-semibold tracking-wide group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                            Voir les passeurs 
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </Link>

                    <!-- Classement Gardiens -->
                    <Link :href="route('stats.classement.gardiens')" 
                          class="group bg-white backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg hover:shadow-xl hover:shadow-lime-500/20 transition-all duration-300 hover:-translate-y-2 hover:border-lime-300">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                            <i class="fas fa-hand-paper text-lime-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">CLASSEMENT GARDIENS</h3>
                        <p class="text-gray-600 mb-6 font-medium">Solidité défensive ultime</p>
                        <div class="text-lime-600 font-semibold tracking-wide group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                            Voir les gardiens 
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </Link>

                </div>

                <!-- Section Joueur du Mois -->
                <div class="mt-20 text-center">
                    <div class="bg-gradient-to-r from-white to-slate-50 backdrop-blur-sm rounded-3xl p-12 text-gray-900 border-2 border-lime-200/50 shadow-xl">
                        <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border-2 border-lime-300">
                            <i class="fas fa-crown text-lime-600 text-5xl"></i>
                        </div>
                        <h2 class="text-4xl font-bold mb-6 bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                            PRÉDATEUR DU MOIS
                        </h2>
                        <p class="text-xl mb-8 text-gray-600 font-medium max-w-2xl mx-auto">
                            Découvrez le joueur élu cette semaine pour ses performances exceptionnelles dans l'arène
                        </p>
                        <Link :href="route('player.month.current')" 
                              class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white px-10 py-5 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30 hover:-translate-y-1">
                            <i class="fas fa-trophy"></i>
                            DÉCOUVRIR LE PRÉDATEUR
                        </Link>
                    </div>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>