<script setup>
import { ref } from 'vue'

const slides = [
    { title: '1. Objet du règlement', text: `Le présent règlement vise à encadrer l'équipe...` },
    { title: '2. Comportement et discipline', text: `Respect des coachs...
    Attitude exemplaire...` },
    { title: '3. Assiduité et ponctualité', text: `Présence obligatoire...
    Retards sanctionnés...` },
    { title: '4. Matériel et équipements', text: `Tenue complète...
    Protège-tibias obligatoires...` },
    { title: '5. Hygiène et santé', text: `Douche recommandée...
    Interdiction d'alcool...` },
    { title: '6. Communication', text: `Usage du groupe officiel...
    Respect du staff...` },
    { title: '7. Engagement sportif', text: `Donner le meilleur...
    Respect tactique...` },
    { title: '8. Sanctions', text: `Avertissements...
    Suspension...` },
    { title: '9. Engagement du joueur', text: `Signature obligatoire...` },
]

const currentIndex = ref(0)

function next() {
    if (currentIndex.value < slides.length - 1) currentIndex.value++
}

function prev() {
    if (currentIndex.value > 0) currentIndex.value--
}
</script>

<template>
    <section class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Règlement intérieur</h2>
        
        <div class="relative overflow-hidden rounded-lg bg-gray-50 dark:bg-gray-900 p-4">
            <div class="transition-transform duration-500" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                <div v-for="(item, i) in slides" :key="i" class="min-w-full px-4 py-4">
                    <h3 class="text-lg font-semibold mb-3 text-citron-600 dark:text-citron-400">{{ item.title }}</h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ item.text }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex justify-between mt-6">
            <button @click="prev" 
                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
                Précédent
            </button>
            <button @click="next" 
                class="px-4 py-2 bg-citron-500 hover:bg-citron-600 text-white rounded-lg transition-colors font-medium shadow-sm">
                Suivant
            </button>
        </div>
        
        <!-- Indicateurs -->
        <div class="flex justify-center gap-2 mt-4">
            <button v-for="(_, i) in slides" 
                :key="i" 
                @click="currentIndex = i"
                :class="['w-2 h-2 rounded-full transition-all', currentIndex === i ? 'bg-citron-500' : 'bg-gray-300 dark:bg-gray-600']">
            </button>
        </div>
    </section>
</template>