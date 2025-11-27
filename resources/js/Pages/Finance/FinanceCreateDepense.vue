<script setup>
import { ref } from 'vue'
const visible = ref(false)
const montant = ref('')
const description = ref('')
const emit = defineEmits(['submit', 'close'])
function open() { visible.value = true }
function close() { visible.value = false; emit('close') }
function submit() {
  emit('submit', { montant: montant.value, description: description.value })
  montant.value = ''; description.value = ''
  close()
}
defineExpose({ open, close })
</script>
<template>
  <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
      <h2 class="text-xl font-bold mb-6">Ajouter une dépense</h2>
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Montant</label>
          <input type="number" step="0.01" min="1" v-model="montant" required class="w-full rounded border border-neutral-200 p-2" />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-1">Description</label>
          <input type="text" v-model="description" required class="w-full rounded border border-neutral-200 p-2" />
        </div>
        <div class="flex gap-2 justify-end">
          <button type="button" @click="close" class="px-4 py-2 rounded bg-neutral-200">Annuler</button>
          <button type="submit" class="px-4 py-2 rounded bg-danger text-white">Confirmer</button>
        </div>
      </form>
    </div>
  </div>
</template>
