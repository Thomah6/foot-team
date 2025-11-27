<template>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-neutral-200">
      <thead class="bg-neutral-10050">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium">Date</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Membre</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Description</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Montant</th>
          <th class="px-6 py-3 text-left text-xs font-medium">Statut</th>
        </tr>
      </thead>
      <tbody class="bg-white divide-y divide-neutral-200">
        <tr v-for="finance in finances" :key="finance.id">
          <td class="px-6 py-4 whitespace-nowrap text-sm">{{ finance.date }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ finance.user_name }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">{{ finance.description }}</td>
          <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold" :class="montantClass(finance)">
            {{ finance.montant > 0 ? '+' : '' }}{{ finance.montant }}€
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-sm">
            <span v-if="finance.statut_valide" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Validé</span>
            <span v-else class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
<script setup>
const props = defineProps({
  finances: {
    type: Array,
    default: () => []
  }
})
function montantClass(finance) {
  if (finance.type === 'dépense') return 'text-danger'
  if (finance.statut_valide) return 'text-green-600'
  return 'text-yellow-800'
}
</script>
