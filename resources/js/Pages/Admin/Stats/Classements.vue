<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';
</script>

<template>
    <div class="min-h-screen bg-background-light text-zinc-800 font-display">
        <!-- Sidebar -->
        <AdminsideBar />
        
        <!-- Contenu principal -->
        <div class="lg:ml-64">
            <!-- Padding pour mobile pour ne pas être sous le hamburger -->
            <div class="pt-16 lg:pt-0">
                <div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 py-5 flex justify-center">
                    <div class="max-w-[1200px] w-full">

                    <!-- Header -->
                    <div class="flex justify-between items-center p-4 mb-6">
                        <div>
                            <p class="text-4xl font-black tracking-tight">Tous les Classements</p>
                            <p class="text-zinc-600 mt-2">Explorez les différentes statistiques et performances</p>
                        </div>
                        <Link href="/admin/stats" 
                              class="px-4 py-2 bg-zinc-200 text-zinc-700 rounded-lg hover:bg-zinc-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour aux stats
                        </Link>
                    </div>

                    <!-- Grid des classements -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Classement général -->
                        <Link href="/admin/stats/classement/general"
                              class="group bg-white rounded-xl border p-8 hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                            
                            <div class="text-center">
                                <div class="bg-purple-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition">
                                    <i class="fas fa-ranking-star text-purple-600 text-3xl"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold text-zinc-900 mb-2">Classement Général</h3>
                                <p class="text-zinc-600 text-sm mb-4">Points = Buts + Passes/2</p>
                                
                                <div class="text-left space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Type de calcul:</span>
                                        <span class="font-medium">Points pondérés</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Données:</span>
                                        <span class="font-medium text-green-600">Stats validées</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t">
                                    <span class="text-purple-600 font-semibold group-hover:text-purple-700 transition">
                                        Voir le classement →
                                    </span>
                                </div>
                            </div>
                        </Link>

                        <!-- Classement des buteurs -->
                        <Link href="/admin/stats/classements/buteurs"
                              class="group bg-white rounded-xl border p-8 hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                            
                            <div class="text-center">
                                <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-green-200 transition">
                                    <i class="fas fa-futbol text-green-600 text-3xl"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold text-zinc-900 mb-2">Classement des Buteurs</h3>
                                <p class="text-zinc-600 text-sm mb-4">Pureté du but marqué</p>
                                
                                <div class="text-left space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Type de calcul:</span>
                                        <span class="font-medium">Buts totaux</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Seuil minimum:</span>
                                        <span class="font-medium">2 buts</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t">
                                    <span class="text-green-600 font-semibold group-hover:text-green-700 transition">
                                        Voir le classement →
                                    </span>
                                </div>
                            </div>
                        </Link>

                        <!-- Classement des passeurs -->
                        <Link href="/admin/stats/classements/passeurs"
                              class="group bg-white rounded-xl border p-8 hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                            
                            <div class="text-center">
                                <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition">
                                    <i class="fas fa-hands-helping text-blue-600 text-3xl"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold text-zinc-900 mb-2">Classement des Passeurs</h3>
                                <p class="text-zinc-600 text-sm mb-4">Générosité et vision de jeu</p>
                                
                                <div class="text-left space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Type de calcul:</span>
                                        <span class="font-medium">Passes décisives</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Seuil minimum:</span>
                                        <span class="font-medium">2 passes</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t">
                                    <span class="text-blue-600 font-semibold group-hover:text-blue-700 transition">
                                        Voir le classement →
                                    </span>
                                </div>
                            </div>
                        </Link>

                        <!-- Classement des gardiens -->
                        <Link href="/admin/stats/classements/gardiens"
                              class="group bg-white rounded-xl border p-8 hover:shadow-lg hover:scale-105 transition-all cursor-pointer">
                            
                            <div class="text-center">
                                <div class="bg-red-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-red-200 transition">
                                    <i class="fas fa-shield-alt text-red-600 text-3xl"></i>
                                </div>
                                
                                <h3 class="text-xl font-bold text-zinc-900 mb-2">Classement des Gardiens</h3>
                                <p class="text-zinc-600 text-sm mb-4">Solidité défensive</p>
                                
                                <div class="text-left space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Type de calcul:</span>
                                        <span class="font-medium">Moins encaissé</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-zinc-500">Données:</span>
                                        <span class="font-medium">Goals against</span>
                                    </div>
                                </div>
                                
                                <div class="mt-4 pt-4 border-t">
                                    <span class="text-red-600 font-semibold group-hover:text-red-700 transition">
                                        Voir le classement →
                                    </span>
                                </div>
                            </div>
                        </Link>

                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
