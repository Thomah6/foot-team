<script setup>
import { useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminsideBar from "@/Components/AdminsideBar.vue";
import ConfirmModal from '@/Components/ConfirmModal.vue'

const props = defineProps({
  teams: Array
})

// Formulaire de création
const createForm = useForm({
  name: '',
  description: ''
})

function createTeam() {
  createForm.post('teams/create', {
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
const deleteForm = useForm({})
const showDeleteModal = ref(false)
const deletingTeamId = ref(null)

function deleteTeam(id) {
  deletingTeamId.value = id
  showDeleteModal.value = true
}

function confirmDeleteTeam() {
  if (!deletingTeamId.value) return
  deleteForm.delete(route('admin.teams.destroy', deletingTeamId.value))
  deletingTeamId.value = null
}

//Affectation
function affect(teamId) {
  router.get(route('teams.affect', teamId))
}
</script>

<template>
    <section class="flex justify-between">
        <section>
            <AdminsideBar />
        </section>

  <div class="p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-extrabold mb-8 text-gray-800">Gestion des sous-équipes</h1>

    <!-- Create team -->
    <form @submit.prevent="createTeam" class="mb-10 flex flex-wrap gap-3 items-center">
      <input
        v-model="createForm.name"
        class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        placeholder="Nom"
      />
      <input
        v-model="createForm.description"
        class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-400"
        placeholder="Description"
      />
      <button
        class="bg-blue-600 text-white px-5 py-2 rounded-lg shadow hover:bg-blue-700 transition disabled:opacity-50"
        :disabled="createForm.processing"
      >
        {{ createForm.processing ? 'Création...' : 'Créer' }}
      </button>
    </form>

    <!-- Teams list -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <div
        v-for="team in props.teams"
        :key="team.id"
        class="bg-white p-6 rounded-2xl shadow hover:shadow-lg border border-gray-200 transition"
      >
        <!-- Mode édition -->
        <div v-if="editingTeam && editingTeam.id === team.id">
          <input
            v-model="editForm.name"
            class="border border-gray-300 rounded-lg px-3 py-2 w-full mb-3 focus:outline-none focus:ring-2 focus:ring-green-400"
            placeholder="Nom"
          />
          <input
            v-model="editForm.description"
            class="border border-gray-300 rounded-lg px-3 py-2 w-full mb-3 focus:outline-none focus:ring-2 focus:ring-green-400"
            placeholder="Description"
          />

          <div class="flex gap-3">
            <button
              @click.prevent="submitEdit"
              class="bg-green-600 text-white px-4 py-2 rounded-lg shadow hover:bg-green-700 transition"
              :disabled="editForm.processing"
            >
              Sauvegarder
            </button>

            <button
              @click="editingTeam = null"
              class="bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500 transition"
            >
              Annuler
            </button>
          </div>
        </div>

        <!-- Mode normal -->
        <div v-else>
          <h2 class="font-bold text-xl mb-2 text-gray-900">{{ team.name }}</h2>
          <p class="text-gray-600 mb-4">{{ team.description }}</p>

          <h3 class="font-semibold text-gray-700 mb-2">Membres : {{ team.users.length }}</h3>
          <ul class="list-disc ml-5 text-sm mb-4 text-gray-600">
            <li v-for="m in team.members" :key="m.id">{{ m.name }}</li>
          </ul>

          <div class="flex flex-wrap gap-2">
            <!-- Edit -->
            <button
              class="bg-yellow-500 text-white px-4 py-1 rounded-lg shadow hover:bg-yellow-600 transition"
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
              class="bg-red-600 text-white px-4 py-1 rounded-lg shadow hover:bg-red-700 transition"
              @click="deleteTeam(team.id)"
            >
              Supprimer
            </button>

            <!-- Affect -->
            <button
              class="bg-green-600 text-white px-4 py-1 rounded-lg shadow hover:bg-green-700 transition"
              @click="affect(team.id)"
            >
              Affecter
            </button>
          </div>
        </div>
      </div>
    </div>

    <ConfirmModal
      v-model="showDeleteModal"
      title="Supprimer l'équipe"
      message="Voulez-vous vraiment supprimer cette équipe ?"
      confirm-text="Supprimer"
      cancel-text="Annuler"
      variant="danger"
      @confirm="confirmDeleteTeam"
    />
  </div>
    </section>
</template>

