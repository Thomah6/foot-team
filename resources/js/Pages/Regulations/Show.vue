<script setup>
defineProps({
  rule: Object
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Détail du règlement</h1>

    <div class="border p-4 rounded bg-gray-50">
      <h2 class="text-lg font-semibold mb-2">{{ rule.title }}</h2>
      <p class="text-gray-700 mb-4">{{ rule.content }}</p>

      <div class="text-sm text-gray-500">
        <p><strong>ID :</strong> {{ rule.id }}</p>
        <p><strong>Parent :</strong> {{ rule.parent_id ?? 'Aucun' }}</p>
        <p><strong>Ordre :</strong> {{ rule.order }}</p>
      </div>
    </div>

    <!-- Boutons visibles seulement si admin -->
    <div class="mt-4 flex gap-2">
      <button 
        v-if="$page.props.auth.user.role === 'admin'" 
        class="bg-yellow-500 text-white px-3 py-1 rounded"
        @click="$inertia.visit(`/regulations/${rule.id}/edit`)"
      >
        Modifier
      </button>

      <button 
        v-if="$page.props.auth.user.role === 'admin'" 
        class="bg-red-500 text-white px-3 py-1 rounded"
        @click="$inertia.delete(`/regulations/${rule.id}`)"
      >
        Supprimer
      </button>
    </div>
  </div>
</template>
