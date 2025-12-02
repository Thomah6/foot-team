<template>
    <AuthenticatedLayout>
        <div class="vote-history-container p-6 sm:p-8 md:p-10 min-w-full mx-auto bg-gradient-to-b from-blue-500 to-indigo-700 dark:from-gray-800 dark:to-gray-900 rounded-sm shadow-lg">
            <h2 class="text-3xl font-extrabold text-center text-white mb-8">
                Mon Historique de Votes
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="vote in votes"
                    :key="vote.id"
                    class="vote-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-shadow p-6 flex flex-col justify-between"
                >
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 mb-4">
                            {{ vote.reflection.titre }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            <span
                                :class="[
                                    vote.value === 1
                                        ? 'text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200'
                                        : 'text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-200',
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                ]"
                            >
                                {{ vote.value === 1 ? 'POUR' : 'CONTRE' }}
                            </span>
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4">
                        Le {{ formatCommentDate(vote.created_at) }}
                    </p>
                </div>
            </div>

            <p
                v-if="!votes || votes.length === 0"
                class="text-center text-gray-200 mt-8"
            >
                Vous n'avez pas encore voté.
            </p>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const formatCommentDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR");
};

defineProps({
    votes: Array,
});
</script>

<style scoped>
.vote-history-container {
    max-width: 1200px;
}

.vote-card {
    min-height: 150px;
}
</style>
