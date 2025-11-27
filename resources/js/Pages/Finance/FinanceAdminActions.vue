<script setup>
const emit = defineEmits(['valider', 'depense', 'ajustement'])
function validerDepots() { emit('valider') }
function ajouterDepense() { emit('depense') }
function ajustementManuel() { emit('ajustement') }
</script>

<template>
  <div class="flex flex-col gap-4 p-6 bg-white border border-neutral-200 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900">Actions Administrateur</h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
      <button
        @click="validerDepots"
        type="button"
        class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 bg-white rounded-lg hover:bg-neutral-100 transition-colors"
      >
        <span class="material-symbols-outlined text-secondary">check_circle</span>
        <span class="text-sm font-semibold text-neutral-800">Valider Dépôts</span>
      </button>

      <button
        @click="ajouterDepense"
        type="button"
        class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 bg-white rounded-lg hover:bg-neutral-100 transition-colors"
      >
        <span class="material-symbols-outlined text-danger">remove_circle</span>
        <span class="text-sm font-semibold text-neutral-800">Ajouter Dépense</span>
      </button>

      <button
        @click="ajustementManuel"
        type="button"
        class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 bg-white rounded-lg hover:bg-neutral-100 transition-colors"
      >
        <span class="material-symbols-outlined text-primary">edit</span>
        <span class="text-sm font-semibold text-neutral-800">Ajustement Manuel</span>
      </button>
    </div>
  </div>
</template>
