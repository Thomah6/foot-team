<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';

const props = defineProps({
    passeurs: Array
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
                            <p class="text-4xl font-black tracking-tight">Classement des Passeurs</p>
                            <p class="text-zinc-600 mt-2">Générosité et vision de jeu</p>
                        </div>
                        <Link href="/admin/stats/classements" 
                              class="px-4 py-2 bg-zinc-200 text-zinc-700 rounded-lg hover:bg-zinc-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour aux classements
                        </Link>
                    </div>

                    <!-- Classement -->
                    <div class="bg-white rounded-xl border overflow-hidden">
                        
                        <!-- Message si aucun classement -->
                        <div v-if="props.passeurs.length === 0"
                             class="text-center py-12">
                            <i class="fas fa-hands-helping text-zinc-300 text-6xl mb-4"></i>
                            <p class="text-xl font-semibold text-zinc-700">Aucun passeur</p>
                            <p class="text-zinc-500 mt-2">Aucune passe décisive validée (minimum 2 passes requises)</p>
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
                                            Joueur
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Passes
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
                                    <tr v-for="(passeur, index) in props.passeurs" 
                                        :key="passeur.id"
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

                                        <!-- Joueur -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img :src="passeur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                                     class="w-10 h-10 rounded-full object-cover mr-3" />
                                                <div>
                                                    <p class="font-medium text-zinc-900">{{ passeur.name }}</p>
                                                    <p class="text-sm text-zinc-500">{{ passeur.pseudo }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Passes -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-hands-helping mr-1 text-xs"></i>
                                                {{ passeur.total_assists }}
                                            </span>
                                        </td>

                                        <!-- Matchs -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ passeur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                        </td>

                                        <!-- Moyenne par match -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-medium text-zinc-900">
                                                {{ (passeur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                                    (passeur.total_assists / (passeur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
