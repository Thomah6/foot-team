<script setup>
import { computed } from 'vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
  soldeTotal: Number,
  totalAttente: Number,
  nbAttente: Number,
  soldeCotisations: Number,
  soldeDepenses: Number,
  pendingDepensesTotal: { type: Number, default: 0 },
  pendingDepensesCount: { type: Number, default: 0 },
})

const formatMoney = (v) => Number(v ?? 0).toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' F CFA'
</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
    <Card padding="lg">
      <p class="text-neutral-800 text-base font-medium">Solde Total Actuel</p>
      <p :class="Number(props.soldeTotal ?? 0) < 0 ? 'text-error-600' : Number(props.soldeTotal ?? 0) > 0 ? 'text-success-600' : 'text-neutral-900'" class="text-3xl font-bold">{{ formatMoney(props.soldeTotal) }}</p>
    </Card>

    <Card padding="lg">
      <p class="text-neutral-800 text-base font-medium">Total des Cotisations (validées)</p>
      <p class="text-success-600 text-2xl sm:text-3xl font-bold">{{ formatMoney(props.soldeCotisations) }}</p>
    </Card>

    <Card padding="lg">
      <p class="text-neutral-800 text-base font-medium">Total des Dépenses (validées)</p>
      <p class="text-error-600 text-2xl sm:text-3xl font-bold">{{ formatMoney(props.soldeDepenses) }}</p>
    </Card>

    <Card padding="lg">
      <p class="text-neutral-800 text-base font-medium">Total des Dépôts en Attente</p>
      <p class="text-warning-600 text-2xl sm:text-3xl font-bold">{{ formatMoney(props.totalAttente) }}</p>
      <span class="text-sm text-neutral-800 font-medium">{{ props.nbAttente ?? 0 }} transaction(s)</span>
    </Card>

    <Card padding="lg">
      <p class="text-neutral-800 text-base font-medium">Dépenses en Attente (admin)</p>
      <p class="text-warning-600 text-2xl sm:text-3xl font-bold">{{ formatMoney(props.pendingDepensesTotal) }}</p>
      <span class="text-sm text-neutral-800 font-medium">{{ props.pendingDepensesCount ?? 0 }} transaction(s)</span>
    </Card>
  </div>
</template>
