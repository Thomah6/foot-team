<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-black text-white font-sans">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-slate-800 to-slate-900 py-16 px-4 border-b border-slate-700">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl flex items-center justify-center border border-slate-600">
                            <i class="fas fa-trophy text-white text-2xl"></i>
                        </div>
                        <div class="text-slate-300 font-light text-xl tracking-wider">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-200 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-slate-500 text-sm tracking-widest">SAISON 2024</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-thin text-white tracking-wider mb-3">TOUS LES CLASSEMENTS</h1>
                    <div class="text-slate-400 text-lg tracking-widest font-light">STATISTIQUES ET PERFORMANCES</div>
                </div>
            </div>

            <!-- Grid des classements -->
            <div class="max-w-6xl mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Classement Général -->
                    <Link href="/classements/general" 
                          class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-yellow-600/20 to-yellow-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-600/30 group-hover:border-yellow-500/50 transition-colors">
                            <i class="fas fa-medal text-yellow-500 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-white mb-3 tracking-wide">GÉNÉRAL</h3>
                        <p class="text-slate-400 mb-6 font-light">Points = Buts + Passes/2</p>
                        <div class="text-yellow-500 font-light tracking-wider group-hover:text-yellow-400 transition">
                            VOIR LE CLASSEMENT →
                        </div>
                    </Link>

                    <!-- Classement Buteurs -->
                    <Link href="/classements/buteurs" 
                          class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30 group-hover:border-slate-500/50 transition-colors">
                            <i class="fas fa-futbol text-slate-400 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-white mb-3 tracking-wide">BUTEURS</h3>
                        <p class="text-slate-400 mb-6 font-light">Les meilleurs buteurs du club</p>
                        <div class="text-slate-300 font-light tracking-wider group-hover:text-slate-200 transition">
                            VOIR LES BUTEURS →
                        </div>
                    </Link>

                    <!-- Classement Passeurs -->
                    <Link href="/classements/passeurs" 
                          class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30 group-hover:border-slate-500/50 transition-colors">
                            <i class="fas fa-exchange-alt text-slate-400 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-white mb-3 tracking-wide">PASSEURS</h3>
                        <p class="text-slate-400 mb-6 font-light">Générosité et vision de jeu</p>
                        <div class="text-slate-300 font-light tracking-wider group-hover:text-slate-200 transition">
                            VOIR LES PASSEURS →
                        </div>
                    </Link>

                    <!-- Classement Gardiens -->
                    <Link href="/classements/gardiens" 
                          class="group bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 shadow-2xl hover:bg-slate-800/50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30 group-hover:border-slate-500/50 transition-colors">
                            <i class="fas fa-hand-paper text-slate-400 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-white mb-3 tracking-wide">GARDIENS</h3>
                        <p class="text-slate-400 mb-6 font-light">Solidité défensive</p>
                        <div class="text-slate-300 font-light tracking-wider group-hover:text-slate-200 transition">
                            VOIR LES GARDIENS →
                        </div>
                    </Link>

                </div>

                <!-- Section Joueur du Mois -->
                <div class="mt-20 text-center">
                    <div class="bg-gradient-to-r from-slate-800/80 to-slate-900/80 backdrop-blur-sm rounded-3xl p-12 text-white border border-slate-700 shadow-2xl">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-slate-600/30">
                            <i class="fas fa-star text-slate-400 text-5xl"></i>
                        </div>
                        <h2 class="text-4xl font-thin mb-6 tracking-wider">JOUEUR DU MOIS</h2>
                        <p class="text-xl mb-8 text-slate-300 font-light max-w-2xl mx-auto">
                            Découvrez le joueur élu ce mois-ci pour ses performances exceptionnelles
                        </p>
                        <Link href="/joueur-du-mois" 
                              class="inline-flex items-center gap-3 bg-slate-800 text-white px-8 py-4 rounded-2xl font-light hover:bg-slate-700 transition-all duration-300 tracking-wider text-lg border border-slate-700">
                            <i class="fas fa-trophy"></i>
                            VOIR LE JOUEUR DU MOIS
                        </Link>
                    </div>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>
