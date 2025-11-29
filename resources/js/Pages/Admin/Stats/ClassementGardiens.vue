<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';

const props = defineProps({
    gardiens: Array
})
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900 font-display">
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
                            <p class="text-4xl font-black tracking-tight">Classement des Gardiens</p>
                            <p class="text-gray-600 mt-2">Solidité défensive</p>
                        </div>
                        <Link href="/classements" 
                              class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour aux classements
                        </Link>
                    </div>

                    <!-- Info -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                        <p class="text-red-800 font-semibold mb-2">
                            <i class="fas fa-info-circle mr-2"></i>
                            Classement des gardiens
                        </p>
                        <p class="text-red-700">
                            <strong>Moins de buts encaissés = Meilleur classement</strong>
                        </p>
                        <p class="text-red-600 text-sm mt-1">
                            Seuls les gardiens avec des buts encaissés sont classés
                        </p>
                    </div>

                    <!-- Classement -->
                    <div class="bg-white rounded-xl border overflow-hidden">
                        
                        <!-- Message si aucun classement -->
                        <div v-if="props.gardiens.length === 0"
                             class="text-center py-12">
                            <i class="fas fa-shield-alt text-zinc-300 text-6xl mb-4"></i>
                            <p class="text-xl font-semibold text-zinc-700">Aucun gardien</p>
                            <p class="text-zinc-500 mt-2">Aucun gardien avec des buts encaissés validés</p>
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
                                            Gardien
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Buts Encaissés
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
                                    <tr v-for="(gardien, index) in props.gardiens" 
                                        :key="gardien.id"
                                        :class="index === 0 ? 'bg-green-50' : index === 1 ? 'bg-gray-50' : index === 2 ? 'bg-orange-50' : ''">

                                        <!-- Position -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center justify-center w-8 h-8 rounded-full font-bold"
                                                 :class="index === 0 ? 'bg-green-500 text-white' : 
                                                         index === 1 ? 'bg-gray-400 text-white' : 
                                                         index === 2 ? 'bg-orange-600 text-white' : 'bg-zinc-200 text-zinc-700'">
                                                {{ index + 1 }}
                                            </div>
                                        </td>

                                        <!-- Gardien -->
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <img :src="gardien.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=G'"
                                                     class="w-10 h-10 rounded-full object-cover mr-3" />
                                                <div>
                                                    <p class="font-medium text-zinc-900">{{ gardien.name }}</p>
                                                    <p class="text-sm text-zinc-500">{{ gardien.pseudo }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Buts Encaissés -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                                                  :class="gardien.total_goals_against <= 5 ? 'bg-green-100 text-green-800' : 
                                                          gardien.total_goals_against <= 10 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'">
                                                <i class="fas fa-shield-alt mr-1 text-xs"></i>
                                                {{ gardien.total_goals_against }}
                                            </span>
                                        </td>

                                        <!-- Matchs -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0 }}
                                        </td>

                                        <!-- Moyenne par match -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-medium text-zinc-900">
                                                {{ (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0) > 0 ? 
                                                    (gardien.total_goals_against / (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0)).toFixed(1) : '0.0' }}
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
