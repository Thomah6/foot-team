// --- components/ReglementSlider.vue ---
    <template>
        <section class="p-4 sm:p-6 bg-gradient-to-br from-white to-lime-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg border border-lime-100 dark:border-emerald-800">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-600 to-lime-500 rounded-full"></div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Règlement intérieur</h2>
        </div>
    
    
        <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="transition-transform duration-500 ease-out" :style="{ transform: `translateX(-${currentIndex * 100}%)` }">
                <div v-for="(item, i) in slides" :key="i" class="min-w-full px-4 sm:px-6 py-6 sm:py-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-3 py-1 text-xs sm:text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-lime-600 rounded-full">
                            {{ i + 1 }}/{{ slides.length }}
                        </span>
                        <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white">{{ item.title }}</h3>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 dark:text-gray-300 whitespace-pre-line leading-relaxed">{{ item.text }}</p>
                </div>
            </div>
        </div>
    </div>
    
        <div class="flex items-center justify-between mt-6 gap-3">
            <Button @click="prev" variant="secondary" size="sm" :disabled="currentIndex === 0">
                <template #icon>
                    <i class="fas fa-chevron-left"></i>
                </template>
                Précédent
            </Button>

            <div class="flex items-center gap-1">
                <button 
                    v-for="(_, i) in slides" 
                    :key="i"
                    @click="currentIndex = i"
                    :class="[
                        'w-2 h-2 rounded-full transition-colors duration-200',
                        i === currentIndex 
                            ? 'bg-gradient-to-r from-emerald-600 to-lime-600 w-6' 
                            : 'bg-gray-300 dark:bg-gray-600 hover:bg-gray-400'
                    ]"
                ></button>
            </div>

            <Button @click="next" variant="primary" size="sm" :disabled="currentIndex === slides.length - 1">
                Suivant
                <template #icon>
                    <i class="fas fa-chevron-right"></i>
                </template>
            </Button>
        </div>
    </section>
    </template>
    
    
    <script setup>
    import { ref } from 'vue'
    import Button from '@/Components/Button.vue'
    
    
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