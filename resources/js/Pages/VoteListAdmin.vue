<template>
    <div class="vote-list-admin p-4 sm:p-6 md:p-8 max-w-4xl mx-auto bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-lg shadow-md">
        <h2 class="text-lg md:text-2xl font-extrabold mb-6 text-center">
            Résultats des votes
        </h2>
        <div
            v-for="reflection in reflections"
            :key="reflection.id"
            class="mb-8 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm"
        >
            <h3 class="text-md md:text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">
                {{ reflection.titre }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <p class="text-sm md:text-base text-gray-700 dark:text-gray-300">
                    <strong class="font-medium">POUR:</strong> {{ reflection.pourPercentage }}%
                </p>
                <p class="text-sm md:text-base text-gray-700 dark:text-gray-300">
                    <strong class="font-medium">CONTRE:</strong> {{ reflection.contrePercentage }}%
                </p>
            </div>
            <p class="text-sm md:text-base font-bold text-gray-800 dark:text-gray-200 mt-4">
                Résultat: {{ reflection.winner }}
            </p>
            <ul class="mt-6 space-y-4">
                <li
                    v-for="vote in reflection.votes"
                    :key="vote.id"
                    class="p-4 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg flex items-center justify-between"
                    :class="{
                        'bg-yellow-100 dark:bg-yellow-900': vote.is_bureau,
                    }"
                >
                    <p class="text-sm md:text-base text-gray-800 dark:text-gray-200">
                        <strong>Vote:</strong>
                        <span
                            :class="{
                                'text-green-600 dark:text-green-400': vote.value === 1,
                                'text-red-600 dark:text-red-400': vote.value === -1,
                            }"
                        >
                            {{ vote.value === 1 ? "POUR" : "CONTRE" }}
                        </span>
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

defineProps({
    reflections: Array,
});

onMounted(() => {
    router.get(route("vote.list.admin"));
});
</script>

<style scoped>
.vote-list-admin {
    /* Mobile-first container for consistent layout */
    width: 100%;
}

/* Ensure spacing and alignment for cards */
.vote-list-admin > div {
    transition: box-shadow 0.2s ease-in-out;
}

.vote-list-admin > div:hover {
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}
</style>
