<script setup>
defineProps({
  rules: Array,
  can: Object
})
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Liste des règlements</h1>

    <!-- Bouton créer visible seulement si admin -->
    <button 
      v-if="can.create" 
      class="bg-blue-500 text-white px-4 py-2 rounded mb-4"
      @click="$inertia.visit('/regulations/create')"
    >
      Créer un règlement
    </button>

    <ul class="space-y-4">
      <li v-for="rule in rules" :key="rule.id" class="border p-4 rounded">
        <h2 class="font-semibold">{{ rule.title }}</h2>
        <p class="text-gray-600">{{ rule.content }}</p>

        <!-- Boutons modifier/supprimer visibles seulement si admin -->
        <div class="mt-2 flex gap-2">
          <button 
            v-if="can.update" 
            class="bg-yellow-500 text-white px-3 py-1 rounded"
            @click="$inertia.visit(`/regulations/${rule.id}/edit`)"
          >
            Modifier
          </button>

          <button 
            v-if="can.delete" 
            class="bg-red-500 text-white px-3 py-1 rounded"
            @click="$inertia.delete(`/regulations/${rule.id}`)"
          >
            Supprimer
          </button>
        </div>
      </li>
    </ul>
  </div>
</template>
