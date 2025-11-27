<script setup>
defineProps({
  soldeTotal: Number,
  totalAttente: Number,
  nbAttente: Number,
  soldeCotisations: Number,
  soldeDepenses: Number,
})
</script>
<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Solde Total Actuel</p>
      <p class="text-secondary text-3xl font-bold">{{ soldeTotal.toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
    </div>
    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Total des Dépôts en Attente</p>
      <p class="text-warning text-3xl font-bold">{{ totalAttente.toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
      <span class="text-sm text-neutral-800 font-medium">{{ nbAttente }} transactions</span>
    </div>
  </div>
</template>
