<script setup>
import { computed } from "vue";

const props = defineProps({
    player: Object,
    stats: Object,
    previousPlayers: Array
});

const placeholder = "https://via.placeholder.com/400x500?text=Joueur";

// Formatage des mois pour l'affichage
const formatMonthYear = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return date.toLocaleString("fr-FR", { month: "long", year: "numeric" });
};
</script>

<template>

    <!-- Joueur du mois actuel -->
    <section class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row gap-6 transition-colors">
        
        <!-- Image + nom -->
        <div class="w-full lg:w-1/3">
            <div class="aspect-[4/5] rounded-xl overflow-hidden shadow-lg mb-4">
                <div class="w-full h-full bg-center bg-cover bg-gradient-to-br from-citron-400 to-green-500"
                     :style="`background-image: url(${player?.image || placeholder});`"></div>
            </div>

            <h2 class="text-3xl font-bold mt-4 text-gray-900 dark:text-gray-100">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
            </h2>
            <div class="mt-2">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-citron-100 dark:bg-citron-900 text-citron-800 dark:text-citron-200">
                    Joueur du mois
                </span>
            </div>
        </div>

        <!-- Stats -->
        <div class="flex-1 flex flex-col gap-6">
            <h2 class="text-3xl font-bold lg:hidden text-gray-900 dark:text-gray-100">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }} 
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col p-4 rounded-lg bg-citron-50 dark:bg-gray-700 border border-citron-100 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">Buts</p>
                    <p class="text-2xl font-bold text-citron-600 dark:text-citron-400">{{ stats?.goals ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 rounded-lg bg-citron-50 dark:bg-gray-700 border border-citron-100 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">Passes Décisives</p>
                    <p class="text-2xl font-bold text-citron-600 dark:text-citron-400">{{ stats?.assists ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 rounded-lg bg-citron-50 dark:bg-gray-700 border border-citron-100 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">Matchs Joués</p>
                    <p class="text-2xl font-bold text-citron-600 dark:text-citron-400">{{ stats?.matches_played ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 rounded-lg bg-citron-50 dark:bg-gray-700 border border-citron-100 dark:border-gray-600">
                    <p class="text-gray-700 dark:text-gray-300 text-sm font-medium">Buts encaissés</p>
                    <p class="text-2xl font-bold text-citron-600 dark:text-citron-400">{{ stats?.goals_against ?? 0 }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Joueurs précédents -->
    <section class="mt-10">
        <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Joueurs du mois précédents</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                v-for="p in previousPlayers"
                :key="p.id"
                class="p-4 bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 flex flex-col gap-3 transition-all hover:shadow-lg hover:border-citron-300 dark:hover:border-gray-600"
            >
                <div class="aspect-[4/5] rounded-lg overflow-hidden shadow-sm">
                    <div class="w-full h-full bg-center bg-cover bg-gradient-to-br from-citron-400 to-green-500"
                         :style="`background-image: url(${p.image || placeholder});`"></div>
                </div>

                <div class="flex flex-col">
                    <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ p.user?.name }}</h3>
                    <p class="text-citron-600 dark:text-citron-400 text-sm font-medium">
                        {{ formatMonthYear(p.month) }}
                    </p>
                    <p class="text-gray-700 dark:text-gray-300 text-sm mt-1 line-clamp-2">
                        {{ p.reason }}
                    </p>
                </div>
            </div>
        </div>

        <p v-if="!previousPlayers?.length" class="text-gray-500 dark:text-gray-400 mt-3">
            Aucun joueur précédent.
        </p>
    </section>
</template>