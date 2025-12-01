<template>
    <AuthenticatedLayout>
        <div class="vote-list-container p-6 sm:p-8 md:p-10 min-w-full mx-auto bg-gradient-to-b from-blue-400 to-green-400 dark:from-gray-800 dark:to-gray-900 rounded-sm shadow-lg">
            <h2 class="text-3xl font-extrabold text-center text-white mb-8">
                Résultats des Votes
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="reflection in reflections"
                    :key="reflection.id"
                    class="reflection-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow p-6"
                >
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                        <a :href="`/reflections/${reflection.id}`">{{ reflection.titre }}</a> 
                    </h3>
                    <div class="flex justify-between items-center mb-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>POUR:</strong> {{ reflection.pourPercentage }}%
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <strong>CONTRE:</strong> {{ reflection.contrePercentage }}%
                        </p>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-gray-200">
                        Résultat: {{ reflection.winner }}
                    </p>
                    <ul class="mt-4 space-y-2">
                        <li
                            v-for="vote in reflection.votes"
                            :key="vote.id"
                            class="vote-item bg-gray-50 dark:bg-gray-900 rounded-lg p-4 flex items-center justify-between"
                            :class="{
                                'bg-yellow-300 dark:bg-yellow-900': vote.is_bureau,
                            }"
                        >
                            <p class="text-sm text-gray-800 dark:text-gray-200">
                                <strong>Vote:</strong>
                                <span
                                    :class="{
                                        'text-green-600 dark:text-green-400': vote.value === 1,
                                        'text-red-600 dark:text-red-400': vote.value === -1,
                                    }"
                                >
                                    {{ vote.value === 1 ? 'POUR' : 'CONTRE' }}
                                </span>
                            </p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

defineProps({
    reflections: Array,
});
</script>

<style scoped>
.vote-list-container {
    max-width: 1200px;
}

.reflection-card {
    min-height: 200px;
}
</style>
