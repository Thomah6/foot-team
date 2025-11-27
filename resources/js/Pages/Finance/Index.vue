<script setup>
import FinanceStats from '@/Pages/Finance/FinanceStats.vue'
import FinanceCreateDepot from '@Pages/Finance/FinanceCreateDepot.vue'
import FinanceAdminActions from '@/Pages/Finance/FinanceAdminActions.vue'
import FinanceFilter from '@/Pages/Finance/FinanceFilter.vue'
import FinanceHistoriqueTable from '@/Pages/Finance/FinanceHistoriqueTable.vue'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Props reçues du backend Laravel
const props = defineProps({
soldeCotisations: Number,
soldeDepenses: Number,
  soldeTotal: Number,
  totalAttente: Number,
  nbAttente: Number,
  finances: Array,
  users: Array,

})

// Filtrage dynamique
const filteredfinances = ref(props.finances)

function handleFiltre({ selectedUser, dateFrom, dateTo }) {
  router.get(route('finances.index'), {
    member_id: selectedUser,
    date_from: dateFrom,
    date_to: dateTo,
  }, {
    preserveState: true,
    preserveScroll: true,
    only: ['finances'],
    onSuccess: page => {
      filteredfinances.value = page.props.finances
    }
  })
}

// Soumettre un dépôt
function handleDepot(montant) {
  router.post(route('finances.storeDepot'), { montant })
}

// Actions admin
function handleValider() {
  router.post(route('finances.valider'))
}
function handleDepense() {
  router.post(route('finances.storeDepense'))
}

// Ajustement de la caisse
function handleAjustement() {
  // Ouvre un modal/route spéciale si nécessaire
}

</script>

<template>
  <div class="max-w-7xl mx-auto py-8 space-y-8">
    <h1 class="text-4xl font-black mb-6 text-neutral-900">Caisse / Finances</h1>

    <financestats
      :solde-total="props.soldeTotal"
      :total-attente="props.totalAttente"
      :nb-attente="props.nbAttente"
      

    />

    <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
      <FinanceCreateDepot @depot="handleDepot" />
      <FinanceAdminActions
        @valider="handleValider"
        @depense="handleDepense"
        @ajustement="handleAjustement"
      />
    </div>

    <div class="mt-8 p-6 rounded-lg bg-white border border-neutral-200">
      <h2 class="text-xl font-bold mb-4">Historique des transactions</h2>
      <FinanceFilter :users="props.users" @filter="handleFiltre" />
      <FinanceHistoriqueTable :finances="filteredfinances" />
    </div>
  </div>
</template>