<script setup>
import { ref, defineEmits } from 'vue'
import { router } from '@inertiajs/vue3'
import ConfirmModalFinance from '@/Components/ConfirmModalFinance.vue'

const emit = defineEmits(['refresh-table'])

const montant = ref(100)
const description = ref('')
const showConfirmModal = ref(false)

function askConfirmation() {
  showConfirmModal.value = true
}

function confirmDepot() {
  showConfirmModal.value = false
  // Post to server and ask parent to refresh table on success
  // We avoid client-side navigation so Toasts can be seen
  const data = {
    montant: montant.value,
    description: description.value,
  }

  // Use Inertia global router via window (already available in app), keep minimal imports
  // eslint-disable-next-line no-undef
  router.post(route('finances.storeDepot'), data, {
    onSuccess: () => {
      montant.value = 100
      description.value = ''
      emit('refresh-table')
    },
  })
}

function cancelDepot() {
  showConfirmModal.value = false
}
</script>

<template>
  <div class="flex flex-col gap-4 p-6 bg-white border border-neutral-200 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900">Déclarer un dépôt</h2>
    <p class="text-neutral-600 text-sm">Faites glisser pour sélectionner le montant de votre dépôt.</p>
    <div class="flex flex-col items-center gap-4">
      <span class="text-5xl font-bold text-primary">{{ montant }} F CFA</span>
      <input type="range" min="100" max="10000" step="100" v-model.number="montant" class="w-full h-2 bg-neutral-200 rounded-lg accent-primary"/>
      <div class="w-full flex justify-between text-xs text-neutral-600">
        <span>100 F CFA</span>
        <span>10 000 F CFA</span>
      </div>
      <input 
        v-model="description" 
        type="text" 
        placeholder="Description (optionnel)" 
        class="w-full px-4 py-2 border border-neutral-200 rounded-lg text-sm"
      />
      <button @click="askConfirmation" class="w-full h-12 px-4 bg-blue-600 text-white rounded-lg font-bold mt-4 hover:bg-blue-700">
        Envoyer
      </button>
    </div>

    <ConfirmModalFinance
      :show="showConfirmModal"
      title="Confirmer le dépôt"
      :message="`Voulez-vous déclarer un dépôt de ${montant} F CFA ?`"
      @confirm="confirmDepot"
      @cancel="cancelDepot"
    />
  </div>
</template>

