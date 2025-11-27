<script setup>
import { Link } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';

const props = defineProps({
    classement: Array,
    current_month: String,
    is_player_of_month: Boolean
})

const format = (number) => {
    return parseFloat(number).toFixed(1);
}

const formatMonth = (month) => {
    const date = new Date(month + '-01');
    return date.toLocaleDateString('fr-FR', { 
        month: 'long', 
        year: 'numeric' 
    });
}
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
                                <p class="text-4xl font-black tracking-tight">Classement Général</p>
                                <p class="text-gray-600 mt-2">Points = Buts + Passes/2</p>
                            </div>
                            <Link href="/classements" 
                                  class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Retour aux classements
                            </Link>
                        </div>

                    <!-- Podium du Joueur du Mois -->
                    <div v-if="props.classement.length > 0" class="mb-12">
                        <!-- 1er Place -->
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 rounded-2xl p-8 text-white shadow-2xl mb-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-6">
                                    <div class="text-6xl font-bold">🏆</div>
                                    <div>
                                        <h2 class="text-3xl font-bold mb-2">
                                            {{ props.classement[0].user.name }}
                                        </h2>
                                        <p class="text-yellow-100 text-lg">
                                            {{ props.classement[0].user.pseudo }}
                                        </p>
                                        <div class="mt-4 flex gap-4">
                                            <div class="bg-white/20 rounded-lg px-4 py-2">
                                                <span class="text-2xl font-bold">{{ props.classement[0].points }}</span>
                                                <span class="text-sm ml-2">points</span>
                                            </div>
                                            <div class="bg-white/20 rounded-lg px-4 py-2">
                                                <span class="text-2xl font-bold">{{ props.classement[0].total_goals }}</span>
                                                <span class="text-sm ml-2">buts</span>
                                            </div>
                                            <div class="bg-white/20 rounded-lg px-4 py-2">
                                                <span class="text-2xl font-bold">{{ props.classement[0].total_assists }}</span>
                                                <span class="text-sm ml-2">passes</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <img :src="props.classement[0].user.avatar || 'https://via.placeholder.com/150x150.png/FFD700?text=1'"
                                     class="w-32 h-32 rounded-full border-4 border-white shadow-xl" />
                            </div>
                        </div>

                        <!-- 2ème et 3ème Places -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- 2ème Place -->
                            <div v-if="props.classement[1]" 
                                 class="bg-gradient-to-r from-gray-300 to-gray-400 rounded-xl p-6 text-white shadow-lg">
                                <div class="flex items-center gap-4">
                                    <div class="text-4xl font-bold">🥈</div>
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-bold">{{ props.classement[1].user.name }}</h3>
                                        <p class="text-gray-200">{{ props.classement[1].points }} points</p>
                                    </div>
                                    <img :src="props.classement[1].user.avatar || 'https://via.placeholder.com/100x100.png/C0C0C0?text=2'"
                                         class="w-16 h-16 rounded-full border-2 border-white" />
                                </div>
                            </div>

                            <!-- 3ème Place -->
                            <div v-if="props.classement[2]" 
                                 class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-xl p-6 text-white shadow-lg">
                                <div class="flex items-center gap-4">
                                    <div class="text-4xl font-bold">🥉</div>
                                    <div class="flex-grow">
                                        <h3 class="text-xl font-bold">{{ props.classement[2].user.name }}</h3>
                                        <p class="text-orange-100">{{ props.classement[2].points }} points</p>
                                    </div>
                                    <img :src="props.classement[2].user.avatar || 'https://via.placeholder.com/100x100.png/CD7F32?text=3'"
                                         class="w-16 h-16 rounded-full border-2 border-white" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tableau Complet -->
                    <div class="bg-white rounded-xl border overflow-hidden">
                        <div class="bg-zinc-50 px-6 py-4 border-b">
                            <h3 class="text-lg font-semibold text-zinc-900">
                                📊 Classement Complet du Mois
                            </h3>
                            <p class="text-sm text-zinc-600 mt-1">
                                Formula: Points = Buts + (Passes ÷ 2)
                            </p>
                        </div>
                        
                        <!-- Message si aucun classement -->
                        <div v-if="props.classement.length === 0"
                             class="text-center py-12">
                            <i class="fas fa-trophy text-zinc-300 text-6xl mb-4"></i>
                            <p class="text-xl font-semibold text-zinc-700">Aucun joueur ce mois-ci</p>
                            <p class="text-zinc-500 mt-2">Aucune statistique validée pour ce mois</p>
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
                                            Points
                                        </th>
                                        <th class="px-6 py-4 text-center text-xs font-medium text-zinc-500 uppercase tracking-wider">
                                            Buts
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
                                    <tr v-for="(player, index) in props.classement" 
                                        :key="player.user.id"
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
                                                <img :src="player.user.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                                     class="w-10 h-10 rounded-full object-cover mr-3" />
                                                <div>
                                                    <p class="font-medium text-zinc-900">{{ player.user.name }}</p>
                                                    <p class="text-sm text-zinc-500">{{ player.user.pseudo }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Points -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                <i class="fas fa-star mr-1 text-xs"></i>
                                                {{ format(player.points) }}
                                            </span>
                                        </td>

                                        <!-- Buts -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ player.total_goals }}
                                        </td>

                                        <!-- Passes -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ player.total_assists }}
                                        </td>

                                        <!-- Matchs -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-zinc-600">
                                            {{ player.matches_played }}
                                        </td>

                                        <!-- Moyenne par match -->
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="font-medium text-zinc-900">
                                                {{ player.matches_played > 0 ? format(player.points / player.matches_played) : '0.0' }}
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
