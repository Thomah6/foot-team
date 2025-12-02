<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    passeurs: Array
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-slate-100 to-white text-gray-900 font-sans relative">
            
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 py-12 px-4 border-b border-gray-200">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                     </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-lime-600 text-lg font-semibold tracking-wider mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-gray-600 text-sm font-medium tracking-wider">SAISON 2025</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-5xl font-bold text-gray-900 tracking-tight mb-4 bg-gradient-to-r from-lime-600 to-gray-600 bg-clip-text text-transparent">
                        CLASSEMENT DES PASSEURS
                    </h1>
                    <div class="text-gray-600 text-lg font-medium tracking-wide">Les architectes du jeu</div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="max-w-5xl mx-auto px-4 py-12 relative z-10">
                
                <!-- Message si aucun classement -->
                <div v-if="props.passeurs.length === 0" class="text-center py-32">
                    <div class="mb-8">
                        <i class="fas fa-exchange-alt text-lime-200 text-7xl mb-8"></i>
                    </div>
                    <p class="text-3xl font-bold text-gray-600 mb-4">AUCUNE DONNÉE</p>
                    <p class="text-gray-500 font-medium">Aucun architecte n'a encore délivré de passe décisive</p>
                </div>

                <!-- Podium pour le top 3 -->
                <div v-if="props.passeurs.length >= 3" class="mb-16">
                    <div class="flex justify-center items-end gap-8 mb-8">
                        <!-- 2ème place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-slate-200 to-slate-300 w-28 h-28 rounded-2xl flex items-center justify-center mb-4 border-2 border-slate-300 shadow-lg">
                                <span class="text-4xl font-bold text-slate-700">2</span>
                            </div>
                            <img :src="props.passeurs[1].avatar || 'https://via.placeholder.com/100x100.png/C0C0C0?text=2'" 
                                 class="w-16 h-16 rounded-full border-3 border-slate-300 mb-2" />
                            <p class="text-gray-900 font-bold text-lg">{{ props.passeurs[1].name }}</p>
                            <p class="text-gray-600 text-sm font-medium">{{ props.passeurs[1].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-gray-600 text-sm font-medium">{{ props.passeurs[1].total_assists }} passes</p>
                            </div>
                        </div>

                        <!-- 1ère place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-lime-400 to-gray-500 w-32 h-32 rounded-2xl flex items-center justify-center mb-4 border-2 border-lime-400/50 shadow-lg">
                                <span class="text-5xl font-bold text-white">1</span>
                            </div>
                            <img :src="props.passeurs[0].avatar || 'https://via.placeholder.com/100x100.png/FFD700?text=1'" 
                                 class="w-20 h-20 rounded-full border-4 border-lime-400/50 mb-2" />
                            <p class="text-gray-900 font-bold text-xl">{{ props.passeurs[0].name }}</p>
                            <p class="text-gray-600 text-sm font-medium">{{ props.passeurs[0].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-lime-600 text-sm font-bold">{{ props.passeurs[0].total_assists }} passes</p>
                            </div>
                        </div>

                        <!-- 3ème place -->
                        <div class="flex flex-col items-center">
                            <div class="bg-gradient-to-br from-lime-300 to-lime-400 w-28 h-28 rounded-2xl flex items-center justify-center mb-4 border-2 border-lime-300/50 shadow-lg">
                                <span class="text-4xl font-bold text-white">3</span>
                            </div>
                            <img :src="props.passeurs[2].avatar || 'https://via.placeholder.com/100x100.png/CD7F32?text=3'" 
                                 class="w-16 h-16 rounded-full border-3 border-lime-300 mb-2" />
                            <p class="text-gray-900 font-bold text-lg">{{ props.passeurs[2].name }}</p>
                            <p class="text-gray-600 text-sm font-medium">{{ props.passeurs[2].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-lime-600 text-sm font-medium">{{ props.passeurs[2].total_assists }} passes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau complet -->
                <div v-if="props.passeurs.length > 0" class="bg-white backdrop-blur-sm rounded-2xl overflow-hidden border-2 border-lime-200/50 shadow-xl">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-gradient-to-r from-lime-50 to-gray-50 px-8 py-6 border-b border-lime-200">
                        <div class="grid grid-cols-12 gap-6 text-gray-700 text-sm font-semibold tracking-wide">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5">ARCHITECTE</div>
                            <div class="col-span-2 text-center">PASSES</div>
                            <div class="col-span-2 text-center">MATCHS</div>
                            <div class="col-span-2 text-center">MOY</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-gray-100">
                        <div v-for="(passeur, index) in props.passeurs" 
                             :key="passeur.id"
                             class="px-8 py-6 hover:bg-lime-50/30 transition-all duration-300"
                             :class="index < 3 ? 'bg-gradient-to-r from-lime-50/20 to-gray-50/20' : ''">
                            
                            <div class="grid grid-cols-12 gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full font-bold text-lg"
                                         :class="index === 0 ? 'bg-gradient-to-br from-lime-500 to-gray-500 text-white' : 
                                                 index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-300 text-gray-700' : 
                                                 index === 2 ? 'bg-gradient-to-br from-lime-300 to-lime-400 text-white' : 
                                                 'bg-gray-100 text-gray-700'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-4">
                                    <div class="relative">
                                        <img :src="passeur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=P'"
                                             class="w-14 h-14 rounded-full object-cover border-2 border-gray-300" />
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900 uppercase tracking-wide text-lg">{{ passeur.name }}</p>
                                        <p class="text-gray-600 text-sm font-medium">{{ passeur.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Passes -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-lime-50 rounded-xl px-4 py-3 border border-lime-200">
                                        <span class="text-gray-900 font-bold text-2xl">{{ passeur.total_assists }}</span>
                                    </div>
                                </div>

                                <!-- Matchs -->
                                <div class="col-span-2 text-center text-gray-700 font-semibold text-lg">
                                    {{ passeur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Moyenne -->
                                <div class="col-span-2 text-center">
                                    <span class="text-gray-900 font-bold text-lg">
                                        {{ (passeur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                            (passeur.total_assists / (passeur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques du bas -->
                <div v-if="props.passeurs.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-gray-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300">
                            <i class="fas fa-trophy text-lime-600 text-3xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-lime-600">{{ props.passeurs[0]?.total_assists || 0 }}</p>
                        <p class="text-gray-600 text-sm tracking-wide font-semibold mt-2">PASSES DU LEADER</p>
                        <p class="text-gray-900 mt-2 font-bold">{{ props.passeurs[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-gray-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300">
                            <i class="fas fa-chart-line text-lime-600 text-3xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900">
                            {{ props.passeurs.reduce((sum, p) => sum + p.total_assists, 0) }}
                        </p>
                        <p class="text-gray-600 text-sm tracking-wide font-semibold mt-2">TOTAL PASSES CLUB</p>
                    </div>

                    <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl p-8 text-center border-2 border-lime-200/50 shadow-lg">
                        <div class="bg-gradient-to-br from-lime-100 to-gray-100 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-lime-300">
                            <i class="fas fa-users text-lime-600 text-3xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-gray-900">{{ props.passeurs.length }}</p>
                        <p class="text-gray-600 text-sm tracking-wide font-semibold mt-2">ARCHITECTES CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour -->
                <div class="text-center mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-gray-500 text-white px-10 py-5 rounded-2xl font-bold hover:from-lime-600 hover:to-gray-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-gray-500/30">
                        <i class="fas fa-arrow-left"></i>
                        RETOUR AUX CLASSEMENTS
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>