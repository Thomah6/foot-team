<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
    validatedCount: Number,
    topStats: Array,
    topButeurs: Array
})

const format = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-5xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                    Stats du Club
                </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Suivez les performances des joueurs et les statistiques du club
                </p>
            </div>

            <!-- Stats principales -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                
                <!-- Stats validées -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition">
                    <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ props.validatedCount }}</h3>
                    <p class="text-gray-600">Stats validées</p>
                </div>

                <!-- Top buteur -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition">
                    <div class="bg-yellow-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-trophy text-yellow-600 text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">
                        {{ props.topButeurs[0]?.total_goals || 0 }} buts
                    </h3>
                    <p class="text-gray-600">Meilleur buteur</p>
                    <p class="text-sm font-medium text-blue-600 mt-2">
                        {{ props.topButeurs[0]?.name || '-' }}
                    </p>
                </div>

                <!-- Total joueurs -->
                <div class="bg-white rounded-2xl shadow-lg p-8 text-center transform hover:scale-105 transition">
                    <div class="bg-blue-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-blue-600 text-3xl"></i>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-900 mb-2">{{ props.topButeurs.length }}</h3>
                    <p class="text-gray-600">Joueurs actifs</p>
                </div>
            </div>

            <!-- Top buteurs -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-crown text-yellow-500 mr-3"></i>
                    Classement des Buteurs
                </h2>
                
                <div class="space-y-4">
                    <div v-for="(buteur, index) in props.topButeurs" 
                         :key="buteur.id"
                         class="flex items-center gap-4 p-4 rounded-lg hover:bg-gray-50 transition">
                        
                        <!-- Position -->
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold"
                             :class="index === 0 ? 'bg-yellow-500 text-white' : 
                                     index === 1 ? 'bg-gray-400 text-white' : 
                                     index === 2 ? 'bg-orange-600 text-white' : 'bg-gray-200 text-gray-700'">
                            {{ index + 1 }}
                        </div>
                        
                        <!-- Avatar et nom -->
                        <img :src="buteur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                             class="w-12 h-12 rounded-full object-cover" />
                        
                        <div class="flex-1">
                            <p class="font-semibold text-lg">{{ buuteur.name }}</p>
                            <p class="text-sm text-gray-600">{{ buuteur.pseudo }}</p>
                        </div>
                        
                        <!-- Stats -->
                        <div class="text-right">
                            <p class="text-2xl font-bold text-blue-600">{{ buuteur.total_goals }}</p>
                            <p class="text-sm text-gray-600">buts</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dernières performances -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-history text-blue-600 mr-3"></i>
                    Dernières Performances
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div v-for="stat in props.topStats" 
                         :key="stat.id"
                         class="border rounded-xl p-6 hover:shadow-lg transition">
                        
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-4">
                            <img :src="stat.user.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                 class="w-10 h-10 rounded-full object-cover" />
                            <div>
                                <p class="font-semibold">{{ stat.user.name }}</p>
                                <p class="text-sm text-gray-500">{{ format(stat.date) }}</p>
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-2 text-center">
                            <div class="bg-green-50 rounded-lg p-2">
                                <i class="fas fa-futbol text-green-500"></i>
                                <p class="font-bold text-green-600">{{ stat.goals }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-2">
                                <i class="fas fa-hands-helping text-blue-500"></i>
                                <p class="font-bold text-blue-600">{{ stat.assists }}</p>
                            </div>
                            <div class="bg-purple-50 rounded-lg p-2">
                                <i class="fas fa-calendar text-purple-500"></i>
                                <p class="font-bold text-purple-600">{{ stat.matches_played }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-12 text-gray-600">
                <p class="mb-4">
                    <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                    Toutes les statistiques sont validées par l'administration
                </p>
                <Link href="/login" 
                      class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    <i class="fas fa-sign-in-alt"></i>
                    Espace Admin
                </Link>
            </div>

        </div>
    </div>
</template>
