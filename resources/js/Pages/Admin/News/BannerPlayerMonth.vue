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
    <section class="p-4 sm:p-6 lg:p-8 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl flex flex-col lg:flex-row gap-6 lg:gap-8 transition-colors border border-gray-200/50 dark:border-gray-700">
        
        <!-- Image + nom -->
        <div class="w-full lg:w-1/3 aspect-[4/5] rounded-2xl overflow-hidden shadow-lg">
            <div class="w-full h-full bg-center bg-cover transition-transform duration-300 hover:scale-105"
                 :style="`background-image: url(${player?.image || placeholder});`"></div>

            <h2 class="text-2xl sm:text-3xl font-bold mt-4 sm:mt-6 text-gray-900 dark:text-white">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
            </h2>
        </div>

        <!-- Stats -->
        <div class="flex-1 flex flex-col gap-6">
            <h2 class="text-2xl sm:text-3xl font-bold lg:hidden text-gray-900 dark:text-white">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }} 
            </h2>

            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="flex flex-col p-4 sm:p-6 rounded-xl bg-gradient-to-br from-lime-50 to-emerald-50 dark:from-lime-900/30 dark:to-emerald-900/30 border border-lime-200 dark:border-lime-700 shadow-lg">
                    <p class="text-lime-700 dark:text-lime-400 font-semibold text-sm sm:text-base mb-2">Buts</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.goals ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 sm:p-6 rounded-xl bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/30 dark:to-indigo-900/30 border border-blue-200 dark:border-blue-700 shadow-lg">
                    <p class="text-blue-700 dark:text-blue-400 font-semibold text-sm sm:text-base mb-2">Passes Décisives</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.assists ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 sm:p-6 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/30 dark:to-orange-900/30 border border-amber-200 dark:border-amber-700 shadow-lg">
                    <p class="text-amber-700 dark:text-amber-400 font-semibold text-sm sm:text-base mb-2">Matchs Joués</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.matches_played ?? 0 }}</p>
                </div>

                <div class="flex flex-col p-4 sm:p-6 rounded-xl bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900/30 dark:to-pink-900/30 border border-red-200 dark:border-red-700 shadow-lg">
                    <p class="text-red-700 dark:text-red-400 font-semibold text-sm sm:text-base mb-2">Buts encaissés</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">{{ stats?.goals_against ?? 0 }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Joueurs précédents -->
    <section class="mt-8 sm:mt-10 lg:mt-12">
        <h2 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-gray-900 dark:text-white">Joueurs du mois précédents</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6">
            <div
                v-for="p in previousPlayers"
                :key="p.id"
                class="p-4 sm:p-6 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-lg flex flex-col gap-3 sm:gap-4 transition-all duration-300 hover:shadow-xl hover:scale-105 border border-gray-200/50 dark:border-gray-700"
            >
                <div class="aspect-[4/5] rounded-xl overflow-hidden shadow-md">
                    <div class="w-full h-full bg-center bg-cover transition-transform duration-300 hover:scale-110"
                         :style="`background-image: url(${p.image || placeholder});`"></div>
                </div>

                <div class="flex flex-col flex-1">
                    <h3 class="font-bold text-lg sm:text-xl text-gray-900 dark:text-white">{{ p.user?.name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base font-medium">
                        {{ formatMonthYear(p.month) }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-300 text-sm sm:text-base mt-2 line-clamp-3">
                        {{ p.reason }}
                    </p>
                </div>
            </div>
        </div>

        <div v-if="!previousPlayers?.length" class="text-center py-8 sm:py-12">
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 sm:p-8 border border-gray-200/50 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400 text-base sm:text-lg font-medium">
                    Aucun joueur précédent.
                </p>
            </div>
        </div>
    </section>

</template>
