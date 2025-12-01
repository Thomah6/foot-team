<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
                    <h1 class="text-6xl font-thin text-slate-900 tracking-wider mb-3">TOUS LES CLASSEMENTS</h1>
                    <div class="text-slate-600 text-lg tracking-widest font-light">STATISTIQUES ET PERFORMANCES</div>
                </div>
            </div>

            <!-- Grid des classements -->
            <div class="max-w-6xl mx-auto px-4 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Classement Général -->
                    <Link :href="route('stats.classement.general')" 
                          class="group bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg hover:bg-slate-50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-300 group-hover:border-yellow-400 transition-colors">
                            <i class="fas fa-medal text-yellow-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">GÉNÉRAL</h3>
                        <p class="text-slate-600 mb-6 font-light">Points = Buts + Passes/2</p>
                        <div class="text-yellow-600 font-light tracking-wider group-hover:text-yellow-700 transition">
                            VOIR LE CLASSEMENT →
                        </div>
                    </Link>

                    <!-- Classement Buteurs -->
                    <Link :href="route('stats.classement.buteurs')" 
                          class="group bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg hover:bg-slate-50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300 group-hover:border-slate-400 transition-colors">
                            <i class="fas fa-futbol text-slate-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">BUTEURS</h3>
                        <p class="text-slate-600 mb-6 font-light">Les meilleurs buteurs du club</p>
                        <div class="text-slate-700 font-light tracking-wider group-hover:text-slate-800 transition">
                            VOIR LES BUTEURS →
                        </div>
                    </Link>

                    <!-- Classement Passeurs -->
                    <Link :href="route('stats.classement.passeurs')" 
                          class="group bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg hover:bg-slate-50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300 group-hover:border-slate-400 transition-colors">
                            <i class="fas fa-exchange-alt text-slate-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">PASSEURS</h3>
                        <p class="text-slate-600 mb-6 font-light">Générosité et vision de jeu</p>
                        <div class="text-slate-700 font-light tracking-wider group-hover:text-slate-800 transition">
                            VOIR LES PASSEURS →
                        </div>
                    </Link>

                    <!-- Classement Gardiens -->
                    <Link :href="route('stats.classement.gardiens')" 
                          class="group bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg hover:bg-slate-50 transition-all duration-300 hover:scale-105">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300 group-hover:border-slate-400 transition-colors">
                            <i class="fas fa-hand-paper text-slate-600 text-4xl"></i>
                        </div>
                        <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">GARDIENS</h3>
                        <p class="text-slate-600 mb-6 font-light">Solidité défensive</p>
                        <div class="text-slate-700 font-light tracking-wider group-hover:text-slate-800 transition">
                            VOIR LES GARDIENS →
                        </div>
                    </Link>

                </div>

                <!-- Section Joueur du Mois -->
                <div class="mt-20 text-center">
                    <div class="bg-gradient-to-r from-white to-slate-50 backdrop-blur-sm rounded-3xl p-12 text-slate-900 border border-slate-200 shadow-lg">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-8 border border-slate-300">
                            <i class="fas fa-star text-slate-600 text-5xl"></i>
                        </div>
                        <h2 class="text-4xl font-thin mb-6 tracking-wider text-slate-900">JOUEUR DU MOIS</h2>
                        <p class="text-xl mb-8 text-slate-600 font-light max-w-2xl mx-auto">
                            Découvrez le joueur élu ce mois-ci pour ses performances exceptionnelles
                        </p>
                        <Link :href="route('player.month.current')" 
                              class="inline-flex items-center gap-3 bg-slate-100 text-slate-700 px-8 py-4 rounded-2xl font-light hover:bg-slate-200 transition-all duration-300 tracking-wider text-lg border border-slate-300">
                            <i class="fas fa-trophy"></i>
                            VOIR LE JOUEUR DU MOIS
                        </Link>
                    </div>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>
