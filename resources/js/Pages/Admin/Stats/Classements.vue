<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';
</script>

<template>
    <div class="flex min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-white text-gray-900 font-display">
        <!-- Sidebar -->
        <div class="">
            <AdminsideBar />
        </div>
        
        <!-- Contenu principal -->
        <div class="flex-1">
            <!-- Padding pour mobile -->
            <div class="pt-16 lg:pt-0">
                <div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 py-5 flex justify-center">
                    <div class="max-w-[1200px] w-full">

                        <!-- Header avec gradient FC Dynamo -->
                        <div class="mb-8 pb-6 border-b border-lime-200/30">
                            <div class="flex justify-between items-center mb-2">
                                <div>
                                    <h1 class="text-4xl font-black tracking-tight bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                                        Tous les Classements
                                    </h1>
                                    <p class="text-gray-600 mt-2 font-medium">Explorez les statistiques des combattants de l'arène</p>
                                </div>
                                <Link href="/admin/stats" 
                                    class="px-5 py-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white font-bold rounded-xl hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 shadow-lg hover:shadow-xl hover:shadow-emerald-500/30">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour aux stats
                                </Link>
                            </div>
                        </div>

                        <!-- Grid des classements -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Classement général -->
                            <Link href="/classements/general"
                                class="group bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-8 hover:shadow-xl hover:shadow-lime-500/20 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                                        <i class="fas fa-ranking-star text-lime-600 text-3xl"></i>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Classement Général</h3>
                                    <p class="text-gray-600 text-sm mb-4 font-medium">Points = Buts + Passes/2</p>
                                    
                                    <div class="text-left space-y-3 text-sm bg-lime-50/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Type de calcul:</span>
                                            <span class="font-bold text-lime-700">Points pondérés</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Données:</span>
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">Stats validées</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-lime-200/50">
                                        <span class="text-lime-600 font-bold group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                                            Voir le classement 
                                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </div>
                                </div>
                            </Link>

                            <!-- Classement des buteurs -->
                            <Link href="/classements/buteurs"
                                class="group bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-8 hover:shadow-xl hover:shadow-lime-500/20 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                                        <i class="fas fa-futbol text-lime-600 text-3xl"></i>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Classement des Buteurs</h3>
                                    <p class="text-gray-600 text-sm mb-4 font-medium">Les prédateurs de surface</p>
                                    
                                    <div class="text-left space-y-3 text-sm bg-lime-50/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Type de calcul:</span>
                                            <span class="font-bold text-lime-700">Buts totaux</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Seuil minimum:</span>
                                            <span class="px-2 py-1 bg-lime-100 text-lime-700 rounded-lg text-xs font-bold">2 buts</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-lime-200/50">
                                        <span class="text-lime-600 font-bold group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                                            Voir les prédateurs 
                                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </div>
                                </div>
                            </Link>

                            <!-- Classement des passeurs -->
                            <Link href="/classements/passeurs"
                                class="group bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-8 hover:shadow-xl hover:shadow-lime-500/20 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                                        <i class="fas fa-hands-helping text-lime-600 text-3xl"></i>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Classement des Passeurs</h3>
                                    <p class="text-gray-600 text-sm mb-4 font-medium">Les architectes du jeu</p>
                                    
                                    <div class="text-left space-y-3 text-sm bg-lime-50/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Type de calcul:</span>
                                            <span class="font-bold text-lime-700">Passes décisives</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Seuil minimum:</span>
                                            <span class="px-2 py-1 bg-lime-100 text-lime-700 rounded-lg text-xs font-bold">2 passes</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-lime-200/50">
                                        <span class="text-lime-600 font-bold group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                                            Voir les architectes 
                                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </div>
                                </div>
                            </Link>

                            <!-- Classement des gardiens -->
                            <Link href="/classements/gardiens"
                                class="group bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-8 hover:shadow-xl hover:shadow-lime-500/20 hover:-translate-y-1 transition-all duration-300 cursor-pointer">
                                
                                <div class="text-center">
                                    <div class="bg-gradient-to-br from-lime-100 to-emerald-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 border-2 border-lime-300 group-hover:border-lime-400 transition-colors">
                                        <i class="fas fa-shield-alt text-lime-600 text-3xl"></i>
                                    </div>
                                    
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Classement des Gardiens</h3>
                                    <p class="text-gray-600 text-sm mb-4 font-medium">Les murailles imprenables</p>
                                    
                                    <div class="text-left space-y-3 text-sm bg-lime-50/50 rounded-xl p-4 mb-4">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Type de calcul:</span>
                                            <span class="font-bold text-lime-700">Moins encaissé</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600 font-medium">Données:</span>
                                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">Goals against</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 pt-4 border-t border-lime-200/50">
                                        <span class="text-lime-600 font-bold group-hover:text-emerald-600 transition inline-flex items-center gap-2">
                                            Voir les murailles 
                                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                        </span>
                                    </div>
                                </div>
                            </Link>

                        </div>

                        <!-- Section informations -->
                        <div class="mt-12 bg-gradient-to-r from-lime-50 to-emerald-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-8 shadow-lg">
                            <div class="flex items-start gap-4">
                                <div class="bg-gradient-to-br from-lime-200 to-emerald-200 w-16 h-16 rounded-2xl flex items-center justify-center border-2 border-lime-300">
                                    <i class="fas fa-info-circle text-lime-700 text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Informations sur les classements</h3>
                                    <p class="text-gray-700 font-medium mb-3">
                                        Tous les classements sont calculés uniquement à partir des statistiques <span class="font-bold text-emerald-700">validées par l'administration</span>. 
                                        Les données sont mises à jour après chaque match validé.
                                    </p>
                                    <div class="flex items-center gap-3 text-sm">
                                        <span class="px-3 py-1 bg-lime-100 text-lime-700 rounded-full font-bold">Saison 2024</span>
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full font-bold">Club FC Dynamo</span>
                                        <span class="px-3 py-1 bg-lime-200 text-lime-800 rounded-full font-bold">Arène officielle</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>