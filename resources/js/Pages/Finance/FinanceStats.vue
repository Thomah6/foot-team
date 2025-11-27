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
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Solde Total Actuel</p>
      <p class="text-secondary text-3xl font-bold">{{ Number(soldeTotal ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
      <p class="text-xs text-neutral-500 mt-1">Global: {{ Number(soldeTotalGlobal ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
    </div>

    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Total des Cotisations (validées)</p>
      <p class="text-green-600 text-2xl sm:text-3xl font-bold">{{ Number(soldeCotisations ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
      <p class="text-xs text-neutral-500 mt-1">Global: {{ Number(soldeCotisationsGlobal ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
    </div>

    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Total des Dépenses (validées)</p>
      <p class="text-danger text-2xl sm:text-3xl font-bold">{{ Number(soldeDepenses ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
      <p class="text-xs text-neutral-500 mt-1">Global: {{ Number(soldeDepensesGlobal ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
    </div>

    <div class="flex flex-col gap-2 rounded-lg p-6 bg-white border border-neutral-200">
      <p class="text-neutral-800 text-base font-medium">Total des Dépôts en Attente</p>
      <p class="text-warning text-2xl sm:text-3xl font-bold">{{ Number(totalAttente ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€</p>
      <span class="text-sm text-neutral-800 font-medium">{{ nbAttente ?? 0 }} transaction(s)</span>
      <p class="text-xs text-neutral-500 mt-1">Global: {{ Number(totalAttenteGlobal ?? 0).toLocaleString('fr-FR', {minimumFractionDigits:2}) }}€ — {{ nbAttenteGlobal ?? 0 }} tx</p>
    </div>
  </div>
</template>
