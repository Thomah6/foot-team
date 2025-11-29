<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    featured: Object,
});
</script>

<template>
    <Link :href="route('news.index')">
        <section
            class="p-4 bg-white dark:bg-gray-800 shadow rounded cursor-pointer 
                   hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
        >
            <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-gray-100">
                À la une
            </h2>

            <div v-if="featured && featured.title" class="space-y-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ featured.title }}
                </h3>
                <p class="text-gray-600 dark:text-gray-300">
                    Cliquez pour voir plus d'actualités
                </p>
            </div>

            <p v-else class="text-gray-500 dark:text-gray-400">
                Aucun contenu mis en avant.
            </p>
        </section>
    </Link>
</template>
