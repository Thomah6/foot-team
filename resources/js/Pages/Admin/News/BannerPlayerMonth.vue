<script setup>
const props = defineProps({
    player: Object,
    stats: Object
});

const placeholder = "https://via.placeholder.com/400x500?text=Joueur";
</script>

<template>
    <section class="p-6 bg-white rounded-xl shadow-md flex flex-col lg:flex-row gap-6">
        <!-- Photo joueur -->
        <div class="w-full lg:w-1/3 aspect-[4/5] rounded-xl overflow-hidden">
            <div class="w-full h-full bg-center bg-cover"
                :style="`background-image: url(${player?.user?.image || placeholder});`"></div>
            <h2 class="text-3xl font-bold">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
            </h2>
        </div>
        <h2 class="text-3xl font-bold">
                {{ player?.user?.name ?? "Aucun joueur sélectionné" }}
        </h2>

        <!-- Stats -->
        <div class="flex-1 flex flex-col gap-6">
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
</template>
