<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900 font-display">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-trophy text-yellow-500 mr-3"></i>
                    Tous les Classements
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Explorez les différentes statistiques et performances des joueurs
                </p>
            </div>

            <!-- Grid des classements -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <!-- Classement Général -->
                <Link href="/classements/general" 
                      class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition group">
                    <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-medal text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Classement Général</h3>
                    <p class="text-gray-600 mb-4">Points = Buts + Passes/2</p>
                    <div class="text-yellow-500 font-semibold group-hover:text-yellow-600 transition">
                        Voir le classement →
                    </div>
                </Link>

                <!-- Classement Buteurs -->
                <Link href="/classements/buteurs" 
                      class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition group">
                    <div class="bg-gradient-to-r from-green-400 to-green-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-futbol text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Classement Buteurs</h3>
                    <p class="text-gray-600 mb-4">Les meilleurs buteurs du club</p>
                    <div class="text-green-500 font-semibold group-hover:text-green-600 transition">
                        Voir les buteurs →
                    </div>
                </Link>

                <!-- Classement Passeurs -->
                <Link href="/classements/passeurs" 
                      class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition group">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exchange-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Classement Passeurs</h3>
                    <p class="text-gray-600 mb-4">Générosité et vision de jeu</p>
                    <div class="text-blue-500 font-semibold group-hover:text-blue-600 transition">
                        Voir les passeurs →
                    </div>
                </Link>

                <!-- Classement Gardiens -->
                <Link href="/classements/gardiens" 
                      class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition group">
                    <div class="bg-gradient-to-r from-red-400 to-red-500 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hand-paper text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Classement Gardiens</h3>
                    <p class="text-gray-600 mb-4">Solidité défensive</p>
                    <div class="text-red-500 font-semibold group-hover:text-red-600 transition">
                        Voir les gardiens →
                    </div>
                </Link>

            </div>

            <!-- Section Joueur du Mois -->
            <div class="mt-16 text-center">
                <div class="bg-gradient-to-r from-purple-400 to-pink-500 rounded-2xl p-8 text-white">
                    <i class="fas fa-star text-5xl mb-4"></i>
                    <h2 class="text-3xl font-bold mb-4">Joueur du Mois</h2>
                    <p class="text-xl mb-6 text-purple-100">
                        Découvrez le joueur élu ce mois-ci
                    </p>
                    <Link href="/joueur-du-mois" 
                          class="inline-flex items-center gap-2 bg-white text-purple-600 px-6 py-3 rounded-lg font-semibold hover:bg-purple-50 transition">
                        <i class="fas fa-trophy"></i>
                        Voir le joueur du mois
                    </Link>
                </div>
            </div>

        </div>
    </div>
</template>
