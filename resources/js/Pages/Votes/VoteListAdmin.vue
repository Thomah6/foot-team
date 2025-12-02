<template>
    <AuthenticatedLayout>
        <div class="vote-list-container p-6 sm:p-8 md:p-10 min-w-full mx-auto bg-gradient-to-br from-citron-400 to-citron-600 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg">
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-white dark:text-citron-50 mb-2">
                    📊 Résultats des Votes
                </h2>
                <p class="text-citron-100 dark:text-citron-300">
                    Vue d'ensemble des votes sur toutes les réflexions
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="reflection in reflections"
                    :key="reflection.id"
                    class="reflection-card bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6 border border-citron-100 dark:border-gray-700 hover:border-citron-300 dark:hover:border-citron-500"
                >
                    <div class="mb-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-citron-100 mb-2">
                            <a :href="`/reflections/${reflection.id}`" class="hover:text-citron-600 dark:hover:text-citron-400 transition-colors">
                                {{ reflection.titre }}
                            </a> 
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-citron-300 italic mb-3">
                            "{{ reflection.contenu?.substring(0, 60) }}..."
                        </p>
                    </div>
                    
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="text-center">
                                <span class="text-sm text-green-700 dark:text-green-400 font-semibold">POUR</span>
                                <div class="text-lg font-bold text-green-600 dark:text-green-300">{{ reflection.pourPercentage }}%</div>
                            </div>
                            <div class="text-center">
                                <span class="text-sm text-red-700 dark:text-red-400 font-semibold">CONTRE</span>
                                <div class="text-lg font-bold text-red-600 dark:text-red-300">{{ reflection.contrePercentage }}%</div>
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 mt-2">
                            <div 
                                class="bg-gradient-to-r from-green-400 to-red-400 h-2.5 rounded-full" 
                                :style="{ width: `${reflection.pourPercentage}%` }"
                            ></div>
                        </div>
                    </div>
                    
                    <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <p class="text-sm font-bold text-gray-800 dark:text-citron-200 text-center">
                            🏆 Résultat: 
                            <span :class="{
                                'text-green-600 dark:text-green-400': reflection.winner === 'POUR',
                                'text-red-600 dark:text-red-400': reflection.winner === 'CONTRE',
                                'text-gray-600 dark:text-gray-400': reflection.winner === 'ÉGALITÉ'
                            }">
                                {{ reflection.winner }}
                            </span>
                        </p>
                    </div>
                    
                    <div class="mt-4 border-t dark:border-gray-700 pt-4">
                        <p class="text-xs text-gray-600 dark:text-citron-300 mb-2 font-semibold">
                            Détails des votes ({{ reflection.votes?.length || 0 }}):
                        </p>
                        <ul class="space-y-2 max-h-40 overflow-y-auto pr-2">
                            <li
                                v-for="vote in reflection.votes"
                                :key="vote.id"
                                class="vote-item bg-gray-50 dark:bg-gray-900 rounded-lg p-3 flex items-center justify-between text-sm"
                                :class="{
                                    'bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700': vote.is_bureau,
                                }"
                            >
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-600 dark:text-citron-300">{{ vote.user?.name }}</span>
                                    <span v-if="vote.is_bureau" class="text-xs bg-yellow-500 dark:bg-yellow-700 text-white px-2 py-0.5 rounded-full">👑</span>
                                </div>
                                <span
                                    :class="{
                                        'text-green-600 dark:text-green-400 font-semibold': vote.value === 1,
                                        'text-red-600 dark:text-red-400 font-semibold': vote.value === -1,
                                    }"
                                >
                                    {{ vote.value === 1 ? '✅ POUR' : '❌ CONTRE' }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div 
                v-if="!reflections || reflections.length === 0"
                class="text-center p-8 bg-white/10 dark:bg-gray-800/50 rounded-xl border border-white/20 dark:border-gray-700 mt-8"
            >
                <p class="text-gray-200 dark:text-citron-200 text-lg">
                    📭 Aucune réflexion avec des votes disponibles.
                </p>
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
    min-height: 220px;
    transition: all 0.3s ease;
}

.reflection-card:hover {
    transform: translateY(-5px);
}

/* Custom scrollbar */
.reflection-card ul::-webkit-scrollbar {
    width: 6px;
}

.reflection-card ul::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.reflection-card ul::-webkit-scrollbar-thumb {
    background: #c0c0c0;
    border-radius: 3px;
}

.dark .reflection-card ul::-webkit-scrollbar-track {
    background: #374151;
}

.dark .reflection-card ul::-webkit-scrollbar-thumb {
    background: #4b5563;
}
</style>

