<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';

const props = defineProps({
    buteurs: Array
})
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
                            <p class="text-4xl font-black tracking-tight">Classement des Buteurs</p>
                            <p class="text-zinc-600 mt-2">Pureté du but marqué</p>
                        </div>
                        <Link href="/admin/stats/classements" 
                              class="px-4 py-2 bg-zinc-200 text-zinc-700 rounded-lg hover:bg-zinc-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour aux classements
                        </Link>
                    </div>

                    <!-- Info -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <p class="text-green-800 font-semibold mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Classement des buteurs
                        </p>
                        <p class="text-green-700">
                            <strong>Seuil minimum : 2 buts</strong> pour apparaître dans ce classement
                        </p>
                        <p class="text-green-600 text-sm mt-1">
                            Seules les statistiques validées par l'admin sont comptabilisées
                        </p>
                    </div>

                    <!-- Classement -->
                    <div class="bg-white rounded-xl border overflow-hidden">
                        
                        <!-- Message si aucun classement -->
                        <div v-if="props.buteurs.length === 0"
                             class="text-center py-12">
                            <i class="fas fa-futbol text-zinc-300 text-6xl mb-4"></i>
                            <p class="text-xl font-semibold text-zinc-700">Aucun buteur</p>
                            <p class="text-zinc-500 mt-2">Aucun joueur n'a atteint le minimum de 2 buts</p>
                        </div>

                        <!-- Tableau du classement -->
                        <div v-else class="overflow-x-auto">
                            <table class="w-full">
                                <!-- Header -->
                                <thead class="bg-zinc-50 border-b">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Position
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Buteur
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Buts
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Matchs
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Moy/Match
                                        </th>
                                    </tr>
                                </thead>

                                <!-- Body -->
                                <tbody class="bg-white divide-y divide-zinc-200">
                                    <tr v-for="(buteur, index) in props.buteurs" 
                                        :key="buteur.id"
                                        :class="index === 0 ? 'bg-yellow-50' : index === 1 ? 'bg-gray-50' : index === 2 ? 'bg-orange-50' : ''">

                                        <!-- Position -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                                 :class="index === 0 ? 'bg-yellow-500 text-white' : 
                                                         index === 1 ? 'bg-gray-400 text-white' : 
                                                         index === 2 ? 'bg-orange-600 text-white' : 'bg-zinc-200 text-zinc-700'">
                                                {{ index + 1 }}
                                            </div>
                                        </td>

                                        <!-- Buteur -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img :src="buteur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=B'"
                                                     class="w-10 h-10 rounded-full object-cover mr-3" />
                                                <div>
                                                    <p class="font-medium text-zinc-900">{{ buteur.name }}</p>
                                                    <p class="text-sm text-zinc-500">{{ buteur.pseudo }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Buts -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-futbol mr-1 text-xs"></i>
                                                {{ buteur.total_goals }}
                                            </span>
                                        </td>

                                        <!-- Matchs -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ buteur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                        </td>

                                        <!-- Moyenne par match -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-medium text-zinc-900">
                                                {{ (buteur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                                    (buteur.total_goals / (buteur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Statistiques du classement -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                        <div class="bg-white rounded-xl border p-6 text-center">
                            <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trophy text-green-600 text-2xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-zinc-900">{{ props.buteurs[0]?.total_goals || 0 }}</p>
                            <p class="text-zinc-600">Buts du leader</p>
                            <p class="text-sm text-green-600 mt-1">{{ props.buteurs[0]?.name || '-' }}</p>
                        </div>

                        <div class="bg-white rounded-xl border p-6 text-center">
                            <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-blue-600 text-2xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-zinc-900">
                                {{ props.buteurs.reduce((sum, b) => sum + b.total_goals, 0) }}
                            </p>
                            <p class="text-zinc-600">Total de buts</p>
                        </div>

                        <div class="bg-white rounded-xl border p-6 text-center">
                            <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-purple-600 text-2xl"></i>
                            </div>
                            <p class="text-2xl font-bold text-zinc-900">{{ props.buteurs.length }}</p>
                            <p class="text-zinc-600">Buteurs classés</p>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
