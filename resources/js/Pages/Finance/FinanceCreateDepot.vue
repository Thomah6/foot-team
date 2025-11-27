<script setup>
import { ref } from 'vue'
const montant = ref(20)
const emit = defineEmits(['depot'])
function sendDepot() {
  emit('depot', montant.value)
}
</script>
<template>
  <div class="flex flex-col gap-4 p-6 bg-white border border-neutral-200 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900">Déclarer un dépôt</h2>
    <p class="text-neutral-80080 text-sm">Faites glisser pour sélectionner le montant de votre dépôt.</p>
    <div class="flex flex-col items-center gap-4">
      <span class="text-5xl font-bold text-primary">{{ montant }} F CFA</span>
      <input type="range" min="5" max="50" step="5" v-model="montant" class="w-full h-2 bg-neutral-200 rounded-lg accent-primary"/>
      <div class="w-full flex justify-between text-xs text-neutral-80060">
        <span>100 F CFA</span>
        <span>50 F CFA</span>
      </div>
      <button @click="sendDepot" class="w-full h-12 px-4 bg-primary text-white rounded-lg font-bold mt-4">
        Envoyer
      </button>
    </div>
  </div>
</template>

