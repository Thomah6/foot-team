<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import ConfirmModalFinance from '@/Components/ConfirmModalFinance.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'

const emit = defineEmits(['refresh-table'])

const montant = ref(100)
const description = ref('')
const showConfirmModal = ref(false)
const showConfirmModalLoading = ref(false)

function askConfirmation() {
  showConfirmModal.value = true
}

function confirmDepot() {
  showConfirmModalLoading.value = true
  const data = {
    montant: montant.value,
    description: description.value,
  }

  router.post(route('finances.storeDepot'), data, {
    onSuccess: () => {
      montant.value = 100
      description.value = ''
      emit('refresh-table')
      showConfirmModal.value = false
    },
    onFinish: () => {
      showConfirmModalLoading.value = false
    },
    onError: () => {
      showConfirmModalLoading.value = false
      showConfirmModal.value = false
    }
  })
}

function cancelDepot() {
  showConfirmModal.value = false
}
</script>

<template>
  <div class="flex flex-col gap-4 p-6 bg-white border border-neutral-200 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900">Déclarer un dépôt</h2>
    <p class="text-neutral-600 text-sm">Sélectionnez le montant de votre dépôt.</p>
    <div class="flex flex-col items-center gap-4 w-full">
      <span class="text-5xl font-bold text-primary">{{ montant }} F CFA</span>
      <input type="range" min="100" max="10000" step="100" v-model.number="montant" class="w-full h-2 bg-neutral-200 rounded-lg accent-primary"/>
      <div class="w-full flex justify-between text-xs text-neutral-600">
        <span>100 F CFA</span>
        <span>10 000 F CFA</span>
      </div>

      <div class="w-full">
        <InputLabel for="desc">Description (optionnel)</InputLabel>
        <TextInput id="desc" v-model="description" placeholder="Description (optionnel)" />
      </div>

      <PrimaryButton class="w-full" @click="askConfirmation">Envoyer</PrimaryButton>
    </div>

    <ConfirmModalFinance
      :show="showConfirmModal"
      :loading="showConfirmModalLoading"
      title="Confirmer le dépôt"
      :message="`Voulez-vous déclarer un dépôt de ${montant} F CFA ?`"
      @confirm="confirmDepot"
      @cancel="cancelDepot"
    />
  </div>
</template>

