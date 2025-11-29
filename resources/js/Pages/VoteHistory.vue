<template>
    <div class="vote-history p-4 sm:p-6 md:p-8 max-w-4xl mx-auto bg-">
        <h2 class="text-lg md:text-2xl font-extrabold mb-6 text-center text-gray-900 dark:text-gray-900">
            Mon historique de votes
        </h2>

        <ul class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 auto-rows-fr">
            <li
                v-for="vote in votes"
                :key="vote.id"
                class="vote-card bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-lg p-4 sm:p-5 shadow-sm hover:shadow-md transition-shadow flex flex-col justify-between"
            >
                <div>
                    <p class="text-sm md:text-base font-semibold text-gray-800 dark:text-gray-100 mb-2 truncate">
                        {{ vote.reflection.titre }}
                    </p>

                    <p class="text-sm md:text-sm text-gray-600 dark:text-gray-300 flex items-center gap-3">
                        <strong class="sr-only">Vote:</strong>
                        <span
                            :class="[
                                vote.value === 1
                                    ? 'text-green-700 bg-green-100 dark:bg-green-900 dark:text-green-200'
                                    : 'text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-200',
                                'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold'
                            ]"
                        >
                            {{ vote.value === 1 ? 'POUR' : 'CONTRE' }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto md:ml-0">
                         Le {{ formatCommentDate(vote.created_at) }}
                        </span>
                    </p>
                </div>

                <!-- optional meta area kept minimal and consistent with app -->
                <!-- <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    <span class="inline-block mr-2">ID: {{ vote.id }}</span>
                </div> -->
            </li>
        </ul>

        <!-- No votes placeholder -->
        <p v-if="(!votes || votes.length === 0)" class="mt-6 text-center text-gray-600 dark:text-gray-400">
            Vous n'avez pas encore voté.
        </p>
    </div>
</template>

<script setup>
import { ref, onMounted, defineProps } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const formatCommentDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR");
};

defineProps({
    votes: Array,
});

onMounted( () => {
    router.get(route("vote.history"));
    // votes.value = response.data;
});
</script>

<style scoped>

.vote-history {
    /* mobile-first container keeps layout consistent with Dashboard */
    width: 100%;
}

/* Slight visual lift for cards on larger screens */
@media (min-width: 768px) {
    .vote-card {
        min-height: 120px;
    }
}

/* Ensure text truncation behaves nicely inside cards */
.vote-card .truncate {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

</style>