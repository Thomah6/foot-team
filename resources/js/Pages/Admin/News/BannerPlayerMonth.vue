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
    <section class="p-6 bg-white rounded-xl shadow-md flex flex-col lg:flex-row gap-6">
        <div class="w-full lg:w-1/3 aspect-[4/5] rounded-xl overflow-hidden">
            <div class="w-full h-full bg-center bg-cover"
                 :style="`background-image: url(${player?.user?.image || placeholder});`"></div>
            <h2 class="text-3xl font-bold mt-4">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
            </h2>
            
        </div>

        <!-- Stats -->
        <div class="flex-1 flex flex-col gap-6">
            <h2 class="text-3xl font-bold lg:hidden">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
            </h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col p-4 rounded bg-gray-100">
                    <p>Buts</p>
                    <p class="text-2xl font-bold">{{ stats?.goals ?? 0 }}</p>
                </div>
                <div class="flex flex-col p-4 rounded bg-gray-100">
                    <p>Passes Décisives</p>
                    <p class="text-2xl font-bold">{{ stats?.assists ?? 0 }}</p>
                </div>
                <div class="flex flex-col p-4 rounded bg-gray-100">
                    <p>Matchs Joués</p>
                    <p class="text-2xl font-bold">{{ stats?.matches_played ?? 0 }}</p>
                </div>
                <div class="flex flex-col p-4 rounded bg-gray-100">
                    <p>Buts encaissés</p>
                    <p class="text-2xl font-bold">{{ stats?.goals_against ?? 0 }}</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Joueurs précédents -->
    <section class="mt-10">
        <h2 class="text-2xl font-bold mb-4">Joueurs du mois précédents</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="p in previousPlayers" :key="p.id" class="p-4 bg-white rounded-xl shadow-md flex flex-col gap-3">
                <div class="aspect-[4/5] rounded-lg overflow-hidden">
                    <div class="w-full h-full bg-center bg-cover"
                         :style="`background-image: url(${p.user?.image || placeholder});`"></div>
                </div>
                <div class="flex flex-col">
                    <h3 class="font-bold text-lg">{{ p.user?.name }}</h3>
                    <p class="text-gray-500 text-sm">{{ formatMonthYear(p.month) }}</p>
                    <p class="text-gray-700 text-sm mt-1 line-clamp-2">{{ p.reason }}</p>
                </div>
            </div>
        </div>
        <p v-if="!previousPlayers?.length" class="text-gray-500 mt-3">
            Aucun joueur précédent.
        </p>
    </section>

</template>
