<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
  teams: Array
})

// Formulaire de création
const createForm = useForm({
  name: '',
  description: ''
})

function createTeam() {
  createForm.post('/teams', {
    onSuccess: () => createForm.reset()
  })
}

// Gestion édition
const editingTeam = ref(null)

function startEdit(team) {
  editingTeam.value = team
  editForm.id = team.id
  editForm.name = team.name
  editForm.description = team.description
}

const editForm = useForm({
  id: null,
  name: '',
  description: ''
})

function submitEdit() {
  editForm.put(`/teams/${editForm.id}`, {
    onSuccess: () => {
      editingTeam.value = null
    }
  })
}

// Suppression
function deleteTeam(id) {
  if (confirm("Voulez-vous vraiment supprimer cette sous-équipe ?")) {
    useForm({}).delete(route('admin.teams.destroy', id))
  }
}

//Affectation
function affect(teamId) {
  router.get(route('teams.affect', teamId))
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Gestion des sous-équipes</h1>

    <!-- Create team -->
    <form @submit.prevent="createTeam" class="mb-8 flex gap-2">
      <input v-model="createForm.name" class="border p-2" placeholder="Nom" />
      <input v-model="createForm.description" class="border p-2" placeholder="Description" />

      <button
        class="bg-blue-600 text-white px-4 py-2 rounded disabled:opacity-50"
        :disabled="createForm.processing"
      >
        {{ createForm.processing ? 'Création...' : 'Créer' }}
      </button>
    </form>

    <!-- Teams list -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div
        v-for="team in props.teams"
        :key="team.id"
        class="p-4 border rounded shadow"
      >
        <!-- Mode édition -->
        <div v-if="editingTeam && editingTeam.id === team.id">
          <input
            v-model="editForm.name"
            class="border p-2 w-full mb-2"
            placeholder="Nom"
          />
          <input
            v-model="editForm.description"
            class="border p-2 w-full mb-2"
            placeholder="Description"
          />

          <div class="flex gap-2">
            <button
              @click.prevent="submitEdit"
              class="bg-green-600 text-white px-4 py-2 rounded"
              :disabled="editForm.processing"
            >
              Sauvegarder
            </button>

            <button
              @click="editingTeam = null"
              class="bg-gray-400 text-white px-4 py-2 rounded"
            >
              Annuler
            </button>
          </div>
        </div>

        <!-- Mode normal -->
        <div v-else>
          <h2 class="font-semibold text-xl mb-2">{{ team.name }}</h2>
          <p class="text-gray-600 mb-4">{{ team.description }}</p>

          <h3 class="font-semibold">Membres :</h3>
          <ul class="list-disc ml-4 text-sm mb-4">
            <li v-for="m in team.members" :key="m.id">{{ m.name }}</li>
          </ul>

          <div class="flex gap-2">
            <!-- Edit -->
            <button
              class="bg-yellow-500 text-white px-3 py-1 rounded"
              @click="() => {
                editForm.id = team.id
                editForm.name = team.name
                editForm.description = team.description
                startEdit(team)
              }"
            >
              Modifier
            </button>

            <!-- Delete -->
            <button
              class="bg-red-600 text-white px-3 py-1 rounded"
              @click="deleteTeam(team.id)"
            >
              Supprimer
            </button>
            <button
              class="bg-green-600 text-white px-3 py-1 rounded"
              @click="affect(team.id)"
            >
              Affecter
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

