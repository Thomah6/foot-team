<script setup>
import { computed, ref } from 'vue'
import AdminsideBar from '@/Components/AdminsideBar.vue'
const props = defineProps({
  rules: Array,
  can: Object
})

// Champ de recherche
const search = ref('')

// Filtrer les titres (content null) + recherche
const titles = computed(() =>
  props.rules
    .filter(rule => rule.content === null) // uniquement les titres
    .filter(rule =>
      rule.title.toLowerCase().includes(search.value.toLowerCase())
    )
)

// Filtrer les contenus liés à un titre
function contentsForTitle(title) {
  return props.rules.filter(rule => rule.title === title && rule.content)
}
</script>

<template>
  <div class="flex ">
    <div>
      <AdminsideBar />
    </div>

    <div class="p-6 flex-1 justify-center">
    <div class="p-6 max-w-5xl mx-auto">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-xl font-bold mb-4">Liste des règlements</h1>
  
        <!-- Bouton créer visible seulement si admin -->
        <button v-if="props.can.create" class="bg-green-500 text-black px-4 py-2 rounded mb-4"
          @click="$inertia.visit('/regulations/create')">
          Créer un contenu
        </button>
      </div>
        



      <!-- Champ recherche -->
      <input v-model="search" type="text" placeholder="Rechercher un règlement..." class="border p-2 w-full mb-4 rounded" />

      <!-- Message si aucun résultat -->
      <div v-if="titles.length === 0" class="text-red-500 font-semibold">
        Aucun règlement trouvé pour "{{ search }}"
      </div>

      <!-- Liste des titres + contenus -->
      <ul v-else class="space-y-6">
        <li v-for="t in titles" :key="t.id" class="border p-4 rounded bg-gray-50">
          <h2 class="font-bold text-green-600">{{ t.title }}</h2>

          <!-- Contenus liés -->
          <ul class="mt-2 space-y-2">
            <li v-for="c in contentsForTitle(t.title)" :key="c.id" class="border p-2 rounded bg-white">
              <p class="text-gray-700">{{ c.content }}</p>

              <!-- Boutons admin -->
              <div class="mt-2 flex gap-2">
                <button v-if="$page.props.auth.user.role === 'admin'" class="bg-yellow-500 text-black px-3 py-1 rounded"
                  @click="$inertia.visit(`/regulations/${c.id}/edit`)">
                  Modifier
                </button>

                <button v-if="$page.props.auth.user.role === 'admin'" class="bg-red-500 text-black px-3 py-1 rounded"
                  @click="$inertia.delete(`/regulations/${c.id}`)">
                  Supprimer
                </button>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>

  </div>
  </div>
</template>
