// --- components/ReglementSlider.vue ---
    <template>
    <section class="p-4 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Règlement intérieur</h2>
    
    
    <div class="relative overflow-hidden">
    <div class="transition-transform duration-500" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
    <div v-for="(item, i) in slides" :key="i" class="min-w-full px-4 py-2">
    <h3 class="text-lg font-semibold mb-1">{{ item.title }}</h3>
    <p class="text-gray-700 whitespace-pre-line">{{ item.text }}</p>
    </div>
    </div>
    </div>
    
    
    <div class="flex justify-between mt-4">
    <button @click="prev" class="px-3 py-1 bg-gray-300 rounded">Prev</button>
    <button @click="next" class="px-3 py-1 bg-blue-600 text-white rounded">Next</button>
    </div>
    </section>
    </template>
    
    
    <script setup>
    import { ref } from 'vue'
    
    
    const slides = [
    { title: '1. Objet du règlement', text: `Le présent règlement vise à encadrer l’équipe...` },
    { title: '2. Comportement et discipline', text: `Respect des coachs...
    Attitude exemplaire...` },
    { title: '3. Assiduité et ponctualité', text: `Présence obligatoire...
    Retards sanctionnés...` },
    { title: '4. Matériel et équipements', text: `Tenue complète...
    Protège-tibias obligatoires...` },
    { title: '5. Hygiène et santé', text: `Douche recommandée...
    Interdiction d’alcool...` },
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