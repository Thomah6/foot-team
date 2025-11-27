<script setup>
import FinanceStats from '@/Pages/Finance/FinanceStats.vue'
import FinanceCreateDepot from '@/Pages/Finance/FinanceCreateDepot.vue'
import FinanceAdminActions from '@/Pages/Finance/FinanceAdminActions.vue'
import FinanceFilter from '@/Pages/Finance/FinanceFilter.vue'
import FinanceHistoriqueTable from '@/Pages/Finance/FinanceHistoriqueTable.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

// Props reçues du backend Laravel
const props = defineProps({
  soldeCotisations: Number,
  soldeDepenses: Number,
  soldeTotal: Number,
  totalAttente: Number,
  nbAttente: Number,
  // global
  soldeCotisationsGlobal: Number,
  soldeDepensesGlobal: Number,
  soldeTotalGlobal: Number,
  totalAttenteGlobal: Number,
  nbAttenteGlobal: Number,
  // autres
  finances: Array,
  users: Array,
})

// Filtrage dynamique
const filteredfinances = ref(props.finances)

function handleFiltre({ selectedUser, dateFrom, dateTo, selectedType }) {
  router.get(route('finances.index'), {
    member_id: selectedUser,
    date_from: dateFrom,
    date_to: dateTo,
    type: selectedType,
  }, {
    preserveState: true,
    preserveScroll: true,
    // ⚠️ si tu veux que les stats se mettent à jour avec les filtres,
    // enlève "only" OU ajoute les clés nécessaires
    // only: ['finances'],
    onSuccess: page => {
      filteredfinances.value = page.props.finances
    }
  })
}

function handleDepense() {
  router.get(route('finances.createDepense'))
}

function handleAjustement() {
  // À implémenter : modal ou route pour ajustement manuel
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
      <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
        <h1 class="text-3xl sm:text-4xl font-black text-neutral-900">Caisse / Finances</h1>

        <FinanceStats
          :solde-total="props.soldeTotal"
          :total-attente="props.totalAttente"
          :nb-attente="props.nbAttente"
          :solde-cotisations="props.soldeCotisations"
          :solde-depenses="props.soldeDepenses"
          :solde-total-global="props.soldeTotalGlobal"
          :total-attente-global="props.totalAttenteGlobal"
          :nb-attente-global="props.nbAttenteGlobal"
          :solde-cotisations-global="props.soldeCotisationsGlobal"
          :solde-depenses-global="props.soldeDepensesGlobal"
        />

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8">
          <FinanceCreateDepot />
          <FinanceAdminActions
            @depense="handleDepense"
            @ajustement="handleAjustement"
          />
        </div>

        <div class="p-4 sm:p-6 rounded-lg bg-white border border-neutral-200 space-y-4">
          <h2 class="text-lg sm:text-xl font-bold text-neutral-900">Historique des transactions</h2>
          <FinanceFilter :users="props.users" @filter="handleFiltre" />
          <FinanceHistoriqueTable :finances="filteredfinances" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
