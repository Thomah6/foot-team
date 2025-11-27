<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-neutral-200">
      <thead class="bg-neutral-100">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Membre</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Description</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Montant</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Statut</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Actions</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-neutral-200">
        <tr v-for="finance in finances" :key="finance.id">
          <td class="px-6 py-4 whitespace-nowrap text-sm">{{ finance.date }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ finance.user_name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">{{ finance.description || '-' }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" :class="montantClass(finance)">
            {{ finance.montant > 0 ? '+' : '' }}{{ finance.montant }}€
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <span v-if="finance.statut_valide" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Validé</span>
            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <button
              v-if="!finance.statut_valide && finance.type === 'cotisation'"
              @click="handleValider(finance.id)"
              class="text-green-600 hover:text-green-900 font-semibold"
            >
              Valider
            </button>
            <span v-else class="text-neutral-400">-</span>
          </td>
        </tr>
        <tr v-if="finances.length === 0">
          <td colspan="6" class="px-6 py-4 text-center text-neutral-500">Aucune transaction</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

const props = defineProps({
  finances: {
    type: Array,
    default: () => []
  }
})

function montantClass(finance) {
  if (finance.type === 'dépense') return 'text-red-600'
  if (finance.statut_valide) return 'text-green-600'
  return 'text-yellow-500'
}

function handleValider(id) {
  if (confirm('Voulez-vous valider ce dépôt ?')) {
    router.post(route('finances.valider', id), {}, {
      onSuccess: () => {
      }
    })
  }
}
</script>
