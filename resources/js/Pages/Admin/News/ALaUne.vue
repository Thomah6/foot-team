<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    featured: Object,
});
</script>

<template>
    <Link :href="route('news.index')">
        <div class="group relative overflow-hidden rounded-2xl bg-white dark:bg-gray-800 border-2 border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 shadow-lg hover:shadow-xl transition-all duration-300 active:scale-95 active:transition-none">
            <!-- Header -->
            <div class="p-5 border-b border-lime-100 dark:border-emerald-900/30">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center shadow-md">
                            <i class="fas fa-bolt text-white text-sm"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900 dark:text-white">
                                ZONE D'INTENSITÉ
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Actualités brûlantes
                            </p>
                        </div>
                    </div>
                    <div class="text-emerald-600 dark:text-lime-400 group-hover:translate-x-1 transition-transform">
                        <i class="fas fa-arrow-right text-lg"></i>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-5">
                <div v-if="featured && featured.title" class="space-y-4">
                    <!-- Image avec effet overlay -->
                    <div v-if="featured.image" class="relative overflow-hidden rounded-xl h-48 md:h-56">
                        <img 
                            :src="`/storage/${featured.image}`" 
                            alt="Image à la une"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent"></div>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-gradient-to-r from-lime-500 to-emerald-500 text-white text-xs font-bold rounded-full">
                                À LA UNE
                            </span>
                        </div>
                    </div>

                    <!-- Featured Content -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <i class="fas fa-clock text-lime-600 dark:text-emerald-400"></i>
                            <span>Publié aujourd'hui</span>
                            <div class="w-1 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
                            <i class="fas fa-eye text-lime-600 dark:text-emerald-400"></i>
                            <span>245 vues</span>
                        </div>
                        
                        <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white line-clamp-2">
                            {{ featured.title }}
                        </h3>
                        
                        <p v-if="featured.excerpt" class="text-gray-700 dark:text-gray-300 line-clamp-3">
                            {{ featured.excerpt }}
                        </p>
                        <p v-else class="text-gray-600 dark:text-gray-400">
                            Cliquez pour entrer dans la zone des actualités...
                        </p>
                    </div>
                </div>

                <!-- Placeholder State -->
                <div v-else class="text-center py-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-emerald-900/20 dark:to-lime-900/20 flex items-center justify-center">
                        <i class="fas fa-newspaper text-lime-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                        Zone en préparation
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Les nouvelles arrivent bientôt. Prépare-toi.
                    </p>
                </div>

                <!-- Action Button -->
                <div class="mt-6 pt-4 border-t border-lime-100 dark:border-emerald-900/30">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-emerald-600 dark:text-lime-400">
                            Explorer toutes les news
                        </span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-lime-500 to-emerald-500 flex items-center justify-center text-white group-hover:rotate-90 transition-transform">
                            <i class="fas fa-arrow-right text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Decorative Corner -->
            <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-bl from-lime-500/10 to-emerald-600/10 rounded-bl-2xl"></div>
        </div>
    </Link>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Touch feedback for mobile */
.active\:scale-95:active {
    transform: scale(0.95);
}

.active\:transition-none:active {
    transition: none;
}

/* Optimize images for mobile */
@media (max-width: 640px) {
    img {
        content-visibility: auto;
    }
}
</style>
