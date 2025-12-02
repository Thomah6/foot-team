<template>
    <AuthenticatedLayout>
        <div class="vote-history-container p-6 sm:p-8 md:p-10 min-w-full mx-auto bg-gradient-to-b from-citron-500 to-citron-700 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg">
            <h2 class="text-3xl font-extrabold text-center text-white dark:text-citron-50 mb-8">
                Mon Historique de Votes
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="vote in votes"
                    :key="vote.id"
                    class="vote-card bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between border border-citron-100 dark:border-gray-700 hover:border-citron-300 dark:hover:border-citron-500"
                >
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-citron-100 mb-4">
                            {{ vote.reflection.titre }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-citron-200 mb-2">
                            <span
                                :class="[
                                    vote.value === 1
                                        ? 'text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-300 border border-green-200 dark:border-green-800'
                                        : 'text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-300 border border-red-200 dark:border-red-800',
                                    'inline-block px-3 py-1 rounded-full text-xs font-semibold',
                                ]"
                            >
                                {{ vote.value === 1 ? '✅ POUR' : '❌ CONTRE' }}
                            </span>
                        </p>
                        <p class="text-xs text-gray-500 dark:text-citron-300 mt-2">
                            "{{ vote.reflection.contenu?.substring(0, 80) }}..."
                        </p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-citron-300 mt-4 border-t dark:border-gray-700 pt-3">
                        Voté le {{ formatCommentDate(vote.created_at) }}
                    </p>
                </div>
            </div>

            <div
                v-if="!votes || votes.length === 0"
                class="text-center p-8 bg-white/10 dark:bg-gray-800/50 rounded-xl border border-white/20 dark:border-gray-700 mt-8"
            >
                <p class="text-gray-200 dark:text-citron-200 text-lg">
                    📝 Vous n'avez pas encore voté.
                </p>
                <p class="text-gray-300 dark:text-citron-300 text-sm mt-2">
                    Participez aux réflexions pour voir votre historique ici.
                </p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const formatCommentDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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
    min-height: 180px;
    transition: all 0.3s ease;
}

.vote-card:hover {
    transform: translateY(-4px);
}
</style>
<<<<<<< HEAD
=======

>>>>>>> bc0c59620896928c4b8142a64de53d68a350f302
