<script setup>
import { computed, ref, watch } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TeamStatsForm from './Form.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    teams: Array,
    stat: Object,
    isEditing: Boolean,
    currentMonth: String,
});

const statsData = ref({
    wins: props.stat?.wins || 0,
    losses: props.stat?.losses || 0,
    draws: props.stat?.draws || 0,
});

const form = useForm({
    team_id: props.stat?.team_id || '',
    month: props.stat?.month || props.currentMonth,
    wins: props.stat?.wins || 0,
    losses: props.stat?.losses || 0,
    draws: props.stat?.draws || 0,
});

const totalMatches = computed(() => {
    return form.wins + form.losses + form.draws;
});

// Synchroniser les changements du composant enfant
watch(statsData, (newVal) => {
    form.wins = newVal.wins;
    form.losses = newVal.losses;
    form.draws = newVal.draws;
}, { deep: true });

const submit = () => {
    if (props.isEditing) {
        form.patch(route('admin.team-stats.update', props.stat.id));
    } else {
        form.post(route('admin.team-stats.store'));
    }
};
</script>
<template>
    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto p-4">
            <!-- Header -->
            <div class="mb-6 bg-white rounded-lg shadow p-4 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-extrabold text-emerald-900">{{ isEditing ? 'Modifier les stats' : 'Ajouter des stats' }}</h1>
                    <p class="text-sm text-gray-600 mt-1">{{ isEditing ? 'Mettez à jour les résultats de l\'équipe' : 'Enregistrer les nouveaux résultats de l\'équipe' }}</p>
                </div>
                <Link :href="route('admin.team-stats.index')" class="inline-flex items-center gap-2 px-3 py-2 bg-sky-50 text-sky-800 rounded">
                    ← Retour
                </Link>
            </div>

            <!-- Formulaire principal -->
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Section Équipe -->
                <div class="bg-white rounded-lg shadow p-4 md:p-6 mb-4">
                    <h2 class="text-md font-semibold text-gray-700 mb-3">Équipe</h2>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Sélectionner l'équipe</label>
                        <select v-model="form.team_id" :disabled="isEditing" class="w-full px-3 py-2 border rounded-lg bg-white focus:ring-2 focus:ring-emerald-300">
                            <option value="">-- Choisir une équipe --</option>
                            <option v-for="team in teams" :key="team.id" :value="team.id">{{ team.name }}</option>
                        </select>
                        <InputError :message="form.errors.team_id" class="mt-2" />
                    </div>
                </div>

                <!-- Section Mois -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow p-4 md:p-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Période</h2>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Mois concerné (premier jour du mois)
                        </label>
                        <input v-model="form.month" type="date" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                   bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white
                   focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition" />
                        <InputError :message="form.errors.month" class="mt-2" />
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Les données statistiques couvriront tout le mois sélectionné
                        </p>
                    </div>
                </div>

                <!-- Section Résultats - Composant Form.vue -->
                <TeamStatsForm v-model="statsData" />

                <!-- Section Résumé -->
                <div class="bg-gradient-to-r from-lime-50 to-emerald-50 dark:from-lime-900 dark:to-emerald-900
                  rounded-lg shadow p-4 md:p-6 border border-lime-200 dark:border-lime-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="text-xl mr-2">📊</span>
                        Résumé des données
                    </h2>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Victoires</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                                {{ form.wins }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Défaites</p>
                            <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                                {{ form.losses }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Nuls</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                                {{ form.draws }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total</p>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                {{ totalMatches }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Messages d'erreur -->
                <div v-if="form.errors.duplicate" class="bg-red-50 dark:bg-red-900 border border-red-200 dark:border-red-700 
                                               rounded-lg p-4">
                    <p class="text-sm text-red-700 dark:text-red-200">
                        ⚠️ {{ form.errors.duplicate }}
                    </p>
                    <button @click="form.post(route('admin.team-stats.store'), { data: { ...form, override: true } })"
                        type="button" class="mt-2 text-sm text-red-700 dark:text-red-200 font-semibold hover:underline">
                        Remplacer les données existantes
                    </button>
                </div>

                <!-- Boutons d'action -->
                <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                    <Link :href="route('admin.team-stats.index')" class="px-4 py-2 text-sm font-medium text-emerald-800 bg-white rounded border">Annuler</Link>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-lime-500 to-emerald-500 rounded shadow">
                        <span v-if="form.processing" class="flex items-center justify-center"><span class="animate-spin mr-2">⏳</span>Traitement...</span>
                        <span v-else>{{ isEditing ? '✏️ Mettre à jour' : '✅ Ajouter les stats' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>