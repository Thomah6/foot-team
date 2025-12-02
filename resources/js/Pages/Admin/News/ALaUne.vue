<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    featured: Object,
});
</script>

<template>
    <Link :href="route('news.index')">
        <section
            class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 
                   hover:bg-citron-50 dark:hover:bg-gray-700 transition-all duration-300 cursor-pointer hover:shadow-xl"
        >
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">
                À la une
            </h2>

            <div v-if="featured && featured.title" class="space-y-4">
                <!-- Image featured -->
                <div v-if="featured.image" class="mb-4">
                    <img 
                        :src="`/storage/${featured.image}`" 
                        alt="Image à la une" 
                        class="w-full h-48 object-cover rounded-lg shadow-md"
                    />
                </div>

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