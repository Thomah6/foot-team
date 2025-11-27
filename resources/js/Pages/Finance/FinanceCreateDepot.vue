<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const montant = ref(20)
const description = ref('')

function sendDepot() {
  router.post(route('finances.storeDepot'), {
    montant: montant.value,
    description: description.value
  }, {
    onSuccess: () => {
      montant.value = 20
      description.value = ''
    }
  })
}
</script>

<template>
  <div class="flex flex-col gap-4 p-6 bg-white border border-neutral-200 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900">Déclarer un dépôt</h2>
    <p class="text-neutral-600 text-sm">Faites glisser pour sélectionner le montant de votre dépôt.</p>
    <div class="flex flex-col items-center gap-4">
      <span class="text-5xl font-bold text-primary">{{ montant }} €</span>
      <input type="range" min="5" max="50" step="5" v-model.number="montant" class="w-full h-2 bg-neutral-200 rounded-lg accent-primary"/>
      <div class="w-full flex justify-between text-xs text-neutral-600">
        <span>5 €</span>
        <span>50 €</span>
      </div>
      <input 
        v-model="description" 
        type="text" 
        placeholder="Description (optionnel)" 
        class="w-full px-4 py-2 border border-neutral-200 rounded-lg text-sm"
      />
      <button @click="sendDepot" class="w-full h-12 px-4 bg-blue-600 text-white rounded-lg font-bold mt-4 hover:bg-blue-700">
        Envoyer
      </button>
    </div>
  </div>
</template>

