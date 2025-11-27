<script setup>
import { computed } from 'vue'

// Récupérer les props
const props = defineProps({
  rules: Array,
  can: Object
})

// Filtrer les règlements racine (parent_id null)
const rootRules = computed(() => props.rules.filter(rule => rule.parent_id === null))
</script>



<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Liste des règlements</h1>

    <!-- Bouton créer visible seulement si admin -->
    <button 
      v-if="props.can.create" 
      class="bg-blue-500 text-black px-4 py-2 rounded mb-4"
      @click="$inertia.visit('/regulations/create')"
    >
      Créer un règlement
    </button>

    <ul class="space-y-4">
      <li v-for="rule in rootRules" :key="rule.id" class="border p-4 rounded">
        <h2 class="font-semibold">{{ rule.title }}</h2>
        <p class="text-gray-600">{{ rule.content }}</p>
        <!-- Boutons admin -->
        <div class="mt-2 flex gap-2">
          <button 
            v-if="$page.props.auth.user.role === 'admin'"
            class="bg-yellow-500 text-black px-3 py-1 rounded"
            @click="$inertia.visit(`/regulations/${rule.id}/edit`)"
          >
            Modifier
          </button>
        
          <button 
            v-if="$page.props.auth.user.role === 'admin'"
            class="bg-red-500 text-black px-3 py-1 rounded"
            @click="$inertia.delete(`/regulations/${rule.id}`)"
          >
            Supprimer
          </button>
        </div>

        <!-- Enfants -->
<ul v-if="rule.children && rule.children.length" class="ml-6 mt-2 space-y-2 border-l pl-4">
  <li v-for="child in rule.children" :key="child.id" class="border p-2 rounded bg-gray-50">
      <h3 class="font-medium">{{ child.title }}</h3>
      <p class="text-gray-500">{{ child.content }}</p>
      
    <!-- Boutons admin pour chaque enfant -->
    <div class="mt-2 flex gap-2">
      <button 
        v-if="$page.props.auth.user.role === 'admin'"
        class="bg-yellow-500 text-black px-3 py-1 rounded"
        @click="$inertia.visit(`/regulations/${child.id}/edit`)"
      >
        Modifier
      </button>

      <button 
        v-if="$page.props.auth.user.role === 'admin'"
        class="bg-red-500 text-black px-3 py-1 rounded"
        @click="$inertia.delete(`/regulations/${child.id}`)"
      >
        Supprimer
      </button>
    </div>
  </li>
</ul>


      </li>
    </ul>
  </div>
</template>

