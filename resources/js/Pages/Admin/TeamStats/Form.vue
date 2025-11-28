<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, watch } from 'vue';

const props = defineProps({
    modelValue: Object,
});

const emit = defineEmits(['update:modelValue']);

const form = ref({
    wins: props.modelValue?.wins || 0,
    losses: props.modelValue?.losses || 0,
    draws: props.modelValue?.draws || 0,
});

const totalMatches = computed(() => {
    return form.value.wins + form.value.losses + form.value.draws;
});

const totalPoints = computed(() => {
    return (form.value.wins * 3) + form.value.draws;
});

const winRate = computed(() => {
    if (totalMatches.value === 0) return 0;
    return Math.round((form.value.wins / totalMatches.value) * 100);
});

const avgPointsPerMatch = computed(() => {
    if (totalMatches.value === 0) return 0;
    return (totalPoints.value / totalMatches.value).toFixed(2);
});

const calculateStats = () => {
    emit('update:modelValue', form.value);
};

// Synchroniser avec les changements de props
watch(props, (newProps) => {
    if (newProps.modelValue) {
        form.value = { ...newProps.modelValue };
    }
}, { deep: true });
</script>

<template>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="mb-4">
            <h2 class="text-lg font-bold text-sky-900">Résultats de l'équipe</h2>
            <p class="text-sm text-gray-600">Saisissez les victoires, défaites et matchs nuls pour cette équipe</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Victoires</label>
                <input v-model.number="form.wins" type="number" min="0" @input="calculateStats" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-sky-200" />
                <p v-if="form.wins" class="text-xs text-gray-500 mt-2">{{ form.wins * 3 }} pts</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Défaites</label>
                <input v-model.number="form.losses" type="number" min="0" @input="calculateStats" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-sky-200" />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-2">Nuls</label>
                <input v-model.number="form.draws" type="number" min="0" @input="calculateStats" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-sky-200" />
                <p v-if="form.draws" class="text-xs text-gray-500 mt-2">{{ form.draws }} pt{{ form.draws > 1 ? 's' : '' }}</p>
            </div>
        </div>

        <div v-if="totalMatches > 0" class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <div class="bg-sky-50 rounded-lg p-3 text-center">
                <p class="text-xs text-sky-700">Matchs</p>
                <p class="text-xl font-bold text-sky-900">{{ totalMatches }}</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center shadow">
                <p class="text-xs text-gray-500">Points</p>
                <p class="text-xl font-bold text-sky-900">{{ totalPoints }}</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center shadow">
                <p class="text-xs text-gray-500">Victoires %</p>
                <p class="text-xl font-bold text-sky-900">{{ winRate }}%</p>
            </div>
            <div class="bg-white rounded-lg p-3 text-center shadow">
                <p class="text-xs text-gray-500">Moy pts/match</p>
                <p class="text-xl font-bold text-sky-900">{{ avgPointsPerMatch }}</p>
            </div>
        </div>
    </div>
</template>