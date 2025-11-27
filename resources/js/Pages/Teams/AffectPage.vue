<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
      Affectation des joueurs — {{ team.name }}
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- JOUEURS DISPONIBLES -->
      <div
        class="bg-white rounded-xl shadow p-4 min-h-[400px]"
        @dragover.prevent
        @drop="dropToAvailable"
      >
        <h2 class="text-xl font-semibold mb-3">Joueurs disponibles</h2>

        <input
          type="text"
          v-model="searchQuery"
          placeholder="Rechercher..."
          class="border w-full p-2 mb-3 rounded"
        />

        <div class="space-y-2">
          <div
            v-for="player in filteredAvailable"
            :key="player.id"
            class="p-3 bg-gray-100 rounded-lg border cursor-move transform transition-all duration-200 hover:scale-[1.02]"
            draggable="true"
            @dragstart="dragStart(player, 'available')"
          >
            {{ player.name }}
          </div>
        </div>
      </div>

      <!-- JOUEURS AFFECTÉS -->
      <div
        class="bg-white rounded-xl shadow p-4 min-h-[400px]"
        @dragover.prevent
        @drop="dropToAssigned"
      >
        <h2 class="text-xl font-semibold mb-3 text-green-600">Joueurs affectés</h2>

        <div class="space-y-2">
          <div
            v-for="player in assignedPlayers"
            :key="player.id"
            class="p-3 bg-green-100 rounded-lg border cursor-move transform transition-all duration-200 hover:scale-[1.02]"
            draggable="true"
            @dragstart="dragStart(player, 'assigned')"
          >
            {{ player.name }}
          </div>
        </div>
      </div>
    </div>

    <div class="mt-6">
      <button
        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700"
        :disabled="saving"
        @click="saveAffectation"
      >
        Sauvegarder
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  team: Object,
  available: Array,
  assigned: Array,
})

const searchQuery = ref("")
const draggedPlayer = ref(null)
const draggedFrom = ref(null)

const availablePlayers = ref([...props.available])
const assignedPlayers = ref([...props.assigned])

// Drag start
function dragStart(player, from) {
  draggedPlayer.value = player
  draggedFrom.value = from
}

// Drop into availablePlayers
function dropToAvailable() {
  if (draggedFrom.value === "assigned") {
    assignedPlayers.value = assignedPlayers.value.filter(p => p.id !== draggedPlayer.value.id)
    availablePlayers.value.push(draggedPlayer.value)
  }
}

// Drop into assignedPlayers
function dropToAssigned() {
  if (draggedFrom.value === "available") {
    availablePlayers.value = availablePlayers.value.filter(p => p.id !== draggedPlayer.value.id)
    assignedPlayers.value.push(draggedPlayer.value)
  }
}

const filteredAvailable = computed(() =>
  availablePlayers.value.filter(p =>
    p.name.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

const saving = ref(false)

function saveAffectation() {
  saving.value = true

  router.post(`/teams/${props.team.id}/affect/save`, {
    members: assignedPlayers.value.map(p => p.id),
  }, {
    onFinish: () => saving.value = false
  })
}
</script>
