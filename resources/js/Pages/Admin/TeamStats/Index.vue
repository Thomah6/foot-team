<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    stats: Object,
    teamsCount: Number,
});

const form = useForm({});

const formatMonth = (date) => {
    return new Intl.DateTimeFormat('fr-FR', {
        month: 'long',
        year: 'numeric',
    }).format(new Date(date));
};

const calculateTotalMatches = () => {
    return props.stats.data.reduce((sum, stat) => {
        return sum + stat.wins + stat.losses + stat.draws;
    }, 0);
};

const calculateTotalPoints = () => {
    return props.stats.data.reduce((sum, stat) => {
        return sum + (stat.wins * 3) + stat.draws;
    }, 0);
};

const showDeleteModal = ref(false);
const deletingId = ref(null);

const deleteStat = (id) => {
    deletingId.value = id;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    if (!deletingId.value) return;
    form.delete(route('admin.team-stats.destroy', deletingId.value), {
        onSuccess: () => {
            // Optionally show a toast or Inertia flash message handled by backend
        },
    });
    deletingId.value = null;
};
</script>

<template>
    <AuthenticatedLayout>
        <div class="max-w-7xl mx-auto p-4">
            <!-- Hero header -->
            <div class="mb-6 bg-gradient-to-r from-sky-900 to-yellow-500 text-white rounded-lg shadow-md p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center">
                        <span class="text-2xl font-bold">⚽</span>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight">Stats des équipes</h1>
                        <p class="text-sm opacity-90 mt-1">Gestion mensuelle des résultats — victoires, défaites, nuls</p>
                    </div>
                </div>
                <div class="w-full sm:w-auto">
                    <Link :href="route('admin.team-stats.create')" class="inline-flex items-center gap-3 px-4 py-2.5 text-sm font-semibold text-sky-900 bg-white rounded-lg shadow hover:scale-[1.02] transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                        Ajouter des stats
                    </Link>
                </div>
            </div>
            <ConfirmModal v-model="showDeleteModal" title="Supprimer la stat" message="Êtes-vous sûr de vouloir supprimer cette stat ? Cette action est irréversible." confirm-text="Supprimer" cancel-text="Annuler" variant="danger" @confirm="confirmDelete" />

            <!-- Statistiques globales -->
            <div v-if="stats.data.length > 0" class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500 uppercase">Total équipes</p>
                    <p class="text-2xl font-bold text-sky-900">{{ teamsCount }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500 uppercase">Stats enregistrées</p>
                    <p class="text-2xl font-bold text-sky-700">{{ stats.data.length }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500 uppercase">Total V/D/N</p>
                    <p class="text-2xl font-bold text-sky-700">{{ calculateTotalMatches() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <p class="text-xs text-gray-500 uppercase">Points totaux</p>
                    <p class="text-2xl font-bold text-sky-700">{{ calculateTotalPoints() }}</p>
                </div>
            </div>

            <!-- Tableau stats - Version mobile friendly -->
            <div class="bg-white dark:bg-gray-900 rounded-lg shadow overflow-hidden">
                <!-- Tableau desktop -->
                <div class="hidden md:block overflow-x-auto bg-white rounded-lg shadow">
                    <table class="w-full table-auto">
                        <thead>
                            <tr class="bg-sky-900 text-white">
                                <th class="px-6 py-3 text-left text-sm font-semibold">Équipe</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold">Mois</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Résultats</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Matchs</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Points</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Taux V.</th>
                                <th class="px-6 py-3 text-center text-sm font-semibold">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="stat in stats.data" :key="stat.id" class="border-b last:border-b-0 hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div :style="{ background: stat.team.color || '#0ea5e9' }" class="w-8 h-8 rounded-full"></div>
                                        <div class="font-semibold text-sky-900">{{ stat.team.name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ formatMonth(stat.month) }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2 text-sm font-medium">
                                        <span class="px-2 py-1 bg-green-50 text-green-700 rounded">✓ {{ stat.wins }}</span>
                                        <span class="px-2 py-1 bg-red-50 text-red-700 rounded">✗ {{ stat.losses }}</span>
                                        <span class="px-2 py-1 bg-yellow-50 text-yellow-700 rounded">= {{ stat.draws }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-semibold">{{ stat.wins + stat.losses + stat.draws }}</td>
                                <td class="px-6 py-4 text-center font-semibold text-sky-700">{{ (stat.wins * 3) + stat.draws }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-block px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-sm font-semibold">{{ ((stat.wins / (stat.wins + stat.losses + stat.draws)) * 100).toFixed(1) }}%</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-2">
                                        <Link :href="route('admin.team-stats.edit', stat.id)" class="text-sky-900 font-medium">Éditer</Link>
                                        <button @click="deleteStat(stat.id)" class="text-red-600 font-medium"> Supprimer</button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Cartes mobile -->
                <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    <div v-for="stat in stats.data" :key="stat.id"
                        class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                        <!-- En-tête de la carte -->
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">
                                    {{ stat.team.name }}
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ formatMonth(stat.month) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Matchs</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white">
                                    {{ stat.wins + stat.losses + stat.draws }}
                                </p>
                            </div>
                        </div>

                        <!-- Résultats -->
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="text-center p-2 bg-green-100 dark:bg-green-900 rounded">
                                <p class="text-xs text-green-700 dark:text-green-300">Victoires</p>
                                <p class="text-lg font-bold text-green-900 dark:text-green-100">
                                    {{ stat.wins }}
                                </p>
                            </div>
                            <div class="text-center p-2 bg-red-100 dark:bg-red-900 rounded">
                                <p class="text-xs text-red-700 dark:text-red-300">Défaites</p>
                                <p class="text-lg font-bold text-red-900 dark:text-red-100">
                                    {{ stat.losses }}
                                </p>
                            </div>
                            <div class="text-center p-2 bg-yellow-100 dark:bg-yellow-900 rounded">
                                <p class="text-xs text-yellow-700 dark:text-yellow-300">Nuls</p>
                                <p class="text-lg font-bold text-yellow-900 dark:text-yellow-100">
                                    {{ stat.draws }}
                                </p>
                            </div>
                        </div>

                        <!-- Statistiques complémentaires -->
                        <div class="grid grid-cols-2 gap-2 mb-3">
                            <div class="bg-blue-50 dark:bg-blue-900 p-2 rounded">
                                <p class="text-xs text-blue-700 dark:text-blue-300">Points</p>
                                <p class="text-lg font-bold text-blue-900 dark:text-blue-100">
                                    {{ (stat.wins * 3) + stat.draws }}
                                </p>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900 p-2 rounded">
                                <p class="text-xs text-purple-700 dark:text-purple-300">Taux V.</p>
                                <p class="text-lg font-bold text-purple-900 dark:text-purple-100">
                                    {{ ((stat.wins / (stat.wins + stat.losses + stat.draws)) * 100).toFixed(1) }}%
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <Link :href="route('admin.team-stats.edit', stat.id)" class="flex-1 text-center px-3 py-2 text-sm font-medium text-white
                     bg-blue-600 hover:bg-blue-700 rounded transition">
                             Éditer
                            </Link>
                            <button @click="deleteStat(stat.id)" class="flex-1 px-3 py-2 text-sm font-medium text-white
                     bg-red-600 hover:bg-red-700 rounded transition">
                                 Supprimer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Message vide -->
                <div v-if="stats.data.length === 0" class="text-center py-12">
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Aucune stat d'équipe enregistrée pour le moment
                    </p>
                    <Link href="/admin/team-stats/create" class="inline-block px-4 py-2 text-sm font-medium text-white
                 bg-green-600 hover:bg-green-700 rounded-lg transition">
                     Ajouter la première stat
                    </Link>
                </div>
            </div>

            <!-- Pagination (si desktop) -->
            <div v-if="stats.links" class="mt-6 flex justify-between items-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Affichage {{ stats.from }} à {{ stats.to }} sur {{ stats.total }} stats
                </p>
                <div class="flex gap-2">
                    <Link v-for="link in stats.links" :key="link.label" :href="link.url || '#'" :class="[
                        'px-3 py-1 text-sm rounded transition',
                        link.active
                            ? 'bg-blue-600 text-white'
                            : link.url
                                ? 'text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-800'
                                : 'text-gray-400 cursor-not-allowed'
                    ]" v-html="link.label" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>