<template>
  <div class="flex flex-col md:flex-row min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <section class="hidden md:block">
      <AdminsideBar />
    </section>

    <!-- Main content -->
    <main class="flex-1 w-full p-4 md:p-10">
      <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <a href="/teams/index" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center gap-1 mb-3">
            <i class="fas fa-arrow-left"></i> Retour
          </a>
          <h1 class="text-2xl sm:text-4xl font-extrabold text-gray-900 mb-2">
            Affectation des joueurs
          </h1>
          <p class="text-gray-600 text-sm">{{ team.name }} — Glissez-déposez ou utilisez le mercato</p>
        </div>

        <!-- Drag-and-drop section - Mobile optimized -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-8">

          <!-- JOUEURS DISPONIBLES -->
          <div class="flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
              <i class="fas fa-list text-gray-500"></i>
              Joueurs disponibles
            </h2>
            <div
              class="bg-white rounded-xl shadow border border-gray-300 p-4 min-h-96 flex flex-col overflow-y-auto"
              @dragover.prevent="$event.dataTransfer.dropEffect = 'move'"
              @drop="dropToAvailable"
            >
              <!-- Search input -->
              <input
                type="text"
                v-model="searchQuery"
                placeholder="Rechercher..."
                class="border border-gray-300 w-full p-3 mb-4 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
              />

              <!-- Player list -->
              <div class="space-y-2 flex-1">
                <div
                  v-for="player in filteredAvailable"
                  :key="player.id"
                  class="p-3 bg-gradient-to-r from-gray-100 to-gray-50 rounded-lg border-2 border-gray-300 cursor-move transform transition-all duration-200 hover:scale-[1.02] hover:from-blue-50 hover:to-blue-50 hover:border-blue-400 active:scale-95 select-none"
                  draggable="true"
                  @dragstart="dragStart(player, 'available')"
                >
                  <div class="flex items-center gap-2">
                    <i class="fas fa-futbol text-gray-600"></i>
                    <span class="font-medium text-gray-800 text-sm">{{ player.name }}</span>
                  </div>
                </div>

                <!-- Empty state -->
                <div v-if="filteredAvailable.length === 0" class="text-center py-8 text-gray-500">
                  <i class="fas fa-inbox text-3xl mb-2 opacity-30"></i>
                  <p class="text-sm">Aucun joueur disponible</p>
                </div>
              </div>
            </div>
          </div>

          <!-- JOUEURS AFFECTÉS -->
          <div class="flex flex-col">
            <h2 class="text-lg font-semibold mb-4 text-green-700 flex items-center gap-2">
              <i class="fas fa-check-circle text-green-600"></i>
              Joueurs affectés
            </h2>
            <div
              class="bg-white rounded-xl shadow border-2 border-green-300 p-4 min-h-96 flex flex-col overflow-y-auto terrain"
              @dragover.prevent="$event.dataTransfer.dropEffect = 'move'"
              @drop="dropToAssigned"
            >
              <div class="flex flex-wrap gap-3 content-start flex-1">
                <div
                  v-for="player in assignedPlayers"
                  :key="player.id"
                  class="flex flex-col items-center cursor-move transform transition-all duration-200 hover:scale-110 active:scale-95 select-none"
                  draggable="true"
                  @dragstart="dragStart(player, 'assigned')"
                  :title="player.name"
                >
                  <!-- Petite icône/badge -->
                  <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white shadow-md hover:bg-green-600 transition mb-1 border border-green-600">
                    <i class="fas fa-futbol text-sm"></i>
                  </div>
                  
                  <!-- Nom du joueur -->
                  <p class="text-xs font-semibold text-gray-700 text-center max-w-[50px] line-clamp-2">
                    {{ player.name }}
                  </p>
                </div>

                <!-- Empty state -->
                <div v-if="assignedPlayers.length === 0" class="w-full flex items-center justify-center py-12 text-gray-500">
                  <div class="text-center">
                    <i class="fas fa-inbox text-4xl mb-3 opacity-30"></i>
                    <p class="text-sm font-medium">Glissez les joueurs ici</p>
                  </div>
                </div>
              </div>

              <!-- Compteur de joueurs -->
              <div v-if="assignedPlayers.length > 0" class="mt-4 pt-4 border-t border-green-300 text-center">
                <p class="text-sm font-semibold text-green-700">
                  {{ assignedPlayers.length }} Joueur{{ assignedPlayers.length !== 1 ? 's' : '' }} affecté{{ assignedPlayers.length !== 1 ? 's' : '' }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions - Mobile optimized -->
        <div class="bg-white rounded-xl shadow border border-gray-200 p-4 md:p-6 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Save button -->
            <button
              class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-semibold text-sm md:text-base disabled:opacity-50 flex items-center justify-center gap-2"
              :disabled="saving"
              @click="saveAffectation"
            >
              <i class="fas fa-save"></i>
              {{ saving ? 'Sauvegarde...' : 'Sauvegarder' }}
            </button>

            <!-- Reset button -->
            <button
              class="w-full bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition font-semibold text-sm md:text-base"
              @click="() => {
                availablePlayers.value = [...props.available];
                assignedPlayers.value = [...props.assigned];
              }"
            >
              <i class="fas fa-redo mr-2"></i>
              Réinitialiser
            </button>
          </div>

          <!-- Mercato - Mobile optimized -->
          <div class="border-t border-gray-300 pt-4">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
              <i class="fas fa-magic text-purple-600"></i>
              Mercato aléatoire
            </h3>
            <p class="text-xs md:text-sm text-gray-600 mb-3">Affectez automatiquement un nombre aléatoire de joueurs</p>
            <div class="flex gap-2 flex-col sm:flex-row">
              <input
                type="number"
                v-model.number="mercatoCount"
                min="1"
                :max="availablePlayers.length"
                placeholder="Nombre de joueurs"
                class="flex-1 border border-gray-300 p-3 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400"
              />
              <button
                class="bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition font-semibold text-sm whitespace-nowrap disabled:opacity-50"
                @click="doMercato"
                :disabled="availablePlayers.length === 0 || !mercatoCount"
              >
                <i class="fas fa-shuffle mr-2"></i>
                Mercato
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminsideBar from "@/Components/AdminsideBar.vue";

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

// Mercato - Affectation aléatoire
const mercatoCount = ref(0)

function doMercato() {
  if (mercatoCount.value < 1) return

  // Mélanger les joueurs disponibles
  const shuffled = [...availablePlayers.value].sort(() => Math.random() - 0.5)

  // Sélectionner le nombre demandé
  const selected = shuffled.slice(0, Math.min(mercatoCount.value, availablePlayers.value.length))

  // Les affecter
  selected.forEach(player => {
    availablePlayers.value = availablePlayers.value.filter(p => p.id !== player.id)
    assignedPlayers.value.push(player)
  })

  // Réinitialiser l'input
  mercatoCount.value = 0
}
</script>

<style scoped>
.terrain {
  background-image: url('/terrain.jpg');
  background-size: cover;
  background-repeat: no-repeat;
  background-position: center;
  position: relative;
}

/* Mobile - Image visible but not covering */
@media (max-width: 767px) {
  .terrain {
    background-size: contain;
    min-height: 300px;
  }
}

/* Tablet - Better coverage */
@media (min-width: 768px) {
  .terrain {
    background-size: cover;
    min-height: 400px;
  }
}

/* Desktop - Full coverage */
@media (min-width: 1024px) {
  .terrain {
    min-height: 500px;
  }
}

/* Smooth drag-and-drop animations */
.drag-over {
  background-color: rgba(59, 130, 246, 0.1);
  border-color: rgb(59, 130, 246);
}

/* Hidden scrollbar styling for drag areas */
div::-webkit-scrollbar {
  width: 6px;
}

div::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 10px;
}

div::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 10px;
}

div::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}
</style>
