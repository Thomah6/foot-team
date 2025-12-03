<script setup>
import { useForm, router, usePage } from '@inertiajs/vue3'
import { ref, computed, watch } from 'vue'
import ConfirmModal from '@/Components/ConfirmModal.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';


const props = defineProps({
  teams: Array,
  isAdmin: Boolean
})

//Les messages de succès ou d'erreur
const page = usePage()

// On récupère le message venant de Inertia
const successMessage = computed(() => page.props.flash.success)

// Contrôle d'affichage du modal
const showModal = ref(false)

// Dès qu’il y a un message → affiche le modal
watch(successMessage, (val) => {
    if (val) {
        showModal.value = true

        // Disparait après 5 secondes
        setTimeout(() => {
            showModal.value = false
        }, 2000)
    }
})

const search = ref('')
const showCreateForm = ref(false)

// Filtrer les équipes
const filteredTeams = computed(() => {
  if (!search.value) return props.teams
  return props.teams.filter(team =>
    team.name.toLowerCase().includes(search.value.toLowerCase())
  )
})

// Formulaire de création
const createForm = useForm({
  name: '',
  description: ''
})

function createTeam() {
  createForm.post(route('admin.teams.store'), {
    onSuccess: () => {
      createForm.reset()
      showCreateForm.value = false
    }
  })
}

// Gestion édition
const editingTeam = ref(null)

const editForm = useForm({
  id: null,
  name: '',
  description: ''
})

function startEdit(team) {
  editingTeam.value = team.id
  editForm.id = team.id
  editForm.name = team.name
  editForm.description = team.description
}

function submitEdit() {
  editForm.put(route('admin.teams.update', editForm.id), {
    onSuccess: () => {
      editingTeam.value = null
    }
  })
}

function cancelEdit() {
  editingTeam.value = null
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
  deleteForm.delete(route('admin.teams.destroy', deletingTeamId.value), {
    onSuccess: () => {
      deletingTeamId.value = null
    }
  })
}

// Affectation des joueurs
function affectPlayers(teamId) {
  router.get(`/teams/${teamId}/affect`)
}

// Voir les détails
function viewTeam(teamId) {
  router.get(`/teams/${teamId}`)
}
</script>

<template>
<AuthenticatedLayout>
<!-- MODAL SUCCESS -->
<Transition name="fade">
  <div
    v-if="showModal"
    class="fixed inset-0 flex items-center justify-center z-50"
  >
    <!-- Fond sombre -->
    <div class="absolute inset-0 bg-black/40"></div>

    <!-- Modal -->
    <div
      class="relative bg-white w-[90%] max-w-sm rounded-2xl shadow-xl p-6 text-center animate-bounce-in"
    >
      <div
        class="w-14 h-14 mx-auto mb-3 flex items-center justify-center
               bg-green-100 text-green-600 rounded-full text-2xl"
      >
        ✓
      </div>

      <p class="text-gray-800 font-semibold text-sm">
        {{ successMessage }}
      </p>

      <button
        class="mt-4 text-xs text-gray-500 hover:text-gray-700"
        @click="showModal = false"
      >
        Fermer
      </button>
    </div>
  </div>
</Transition>


    <div class="flex flex-col md:flex-row min-h-screen bg-gray-50 dark:bg-gray-900">
        <main class="flex-1 w-full p-4 md:p-10">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-gray-400 mb-2 flex items-center gap-2">
                    <i class="fas fa-users dark:text-gray-400"></i>
                    {{ isAdmin ? 'Gestion des équipes' : 'Équipes' }}
                </h1>
                <p class="text-gray-600 dark:text-gray-300 text-sm md:text-base">
                    {{ isAdmin ? 'Créez, modifiez et gérez les équipes' : 'Consultez les équipes disponibles' }}
                </p>
            </div>

            <!-- Create team form (Admin only) -->
            <div v-if="isAdmin" class="bg-gradient-to-r from-lime-400/20 to-emerald-500/20 dark:bg-gray-800 rounded-xl shadow-md p-4 md:p-6 mb-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4 flex items-center gap-2">
                    <i class="fas fa-plus-circle text-gray-600 dark:text-gray-400"></i>
                    Créer une nouvelle équipe
                </h2>
                <form @submit.prevent="createTeam" class="space-y-3 md:space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-4">
                        <input
                            v-model="createForm.name"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-citron-400 dark:focus:ring-citron-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition"
                            placeholder="Nom de l'équipe"
                            required
                        />
                        <input
                            v-model="createForm.description"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-citron-400 dark:focus:ring-citron-500 bg-white dark:bg-gray-700 text-gray-800 dark:text-white transition"
                            placeholder="Description"
                        />
                    </div>
                    <button
                        type="submit"
                        class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 dark:bg-black dark:hover:bg-emerald-600 text-white px-6 py-3 rounded-lg shadow transition disabled:opacity-50 font-semibold text-sm md:text-base"
                        :disabled="createForm.processing"
                    >
                        <i class="fas fa-plus mr-2"></i>
                        {{ createForm.processing ? 'Création...' : 'Créer l\'équipe' }}
                    </button>
                </form>
            </div>

            <!-- Search bar -->
            <div class="mb-8">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-citron-500"></i>
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Rechercher une équipe..."
                        class="w-full pl-12 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-2 focus:ring-citron-400 dark:focus:ring-citron-500 focus:border-transparent transition text-sm md:text-base bg-white dark:bg-gray-700 text-gray-800 dark:text-white"
                    />
                </div>
            </div>

            <!-- Teams grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
                <div
                    v-for="team in filteredTeams"
                    :key="team.id"
                    class="bg-white bg-gradient-to-r from-lime-400/20 to-emerald-500/20 p-5 md:p-6 rounded-xl shadow hover:shadow-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:border-gray-700 dark:shadow-lg transition"
                >
                    <!-- Edit mode -->
                    <div v-if="editingTeam === team.id" class="space-y-3 ">
                        <input
                            v-model="editForm.name"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2  bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-lime-400"
                            placeholder="Nom"
                        />
                        <input
                            v-model="editForm.description"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2  bg-white text-gray-800 dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600 dark:focus:ring-lime-400"
                            placeholder="Description"
                        />
                        <div class="flex gap-2 flex-col sm:flex-row">
                            <button
                                @click="submitEdit"
                                class="flex-1 bg-green-600 hover:bg-green-700 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold dark:bg-lime-500 dark:hover:bg-lime-600 dark:text-gray-900"
                                :disabled="editForm.processing"
                            >
                                <i class="fas fa-check mr-1"></i>Sauvegarder
                            </button>
                            <button
                                @click="cancelEdit"
                                class="flex-1 bg-gray-400 hover:bg-gray-500 dark:bg-gray-700 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold dark:bg-gray-700 dark:hover:bg-gray-800 dark:text-gray-200"
                            >
                                <i class="fas fa-times mr-1"></i>Annuler
                            </button>
                        </div>
                    </div>

                    <!-- View mode -->
                    <div v-else>
                        <h2 class="font-bold text-lg md:text-xl text-gray-900 dark:text-white dark:text-gray-300 mb-2">{{ team.name }}</h2>
                        <p class="text-gray-600 dark:text-white dark:text-gray-300  text-sm mb-4">{{ team.description || 'Aucune description' }}</p>

                        <div class="mb-4 p-3 bg-blue-50 rounded-lg border dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 ">
                            <p class="text-sm font-semibold text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-200 ">
                                <i class="fas fa-users text-citron-600 dark:text-citron-400 mr-2"></i>
                                {{ team.users.length }} Joueur{{ team.users.length !== 1 ? 's' : '' }}
                            </p>
                        </div>

                        <!-- Action buttons (Admin only) -->
                        <div v-if="isAdmin" class="flex flex-col gap-2 dark:bg-gray-800 dark:hover:bg-gray-700     dark:text-gray-100 ">
                            <button
                                @click="() => {
                                    editForm.id = team.id
                                    editForm.name = team.name
                                    editForm.description = team.description
                                    startEdit(team)
                                }"
                                class="w-full bg-gradient-to-r from-lime-400/20 to-emerald-500/20 hover:bg-gray-500 text-black px-4 py-2 rounded-lg transition text-sm font-semibold dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white "
                                >
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </button>
                            <button
                                @click="viewTeam(team.id)"
                                class="w-full bg-gradient-to-r from-lime-400/20 to-emerald-500/20 hover:bg-blue-700 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold dark:bg-blue-600 dark:hover:bg-blue-700 dark:text-white "
                            >
                                <i class="fas fa-eye mr-2"></i>Voir détails
                            </button>
                            <button
                                @click="affectPlayers(team.id)"
                                class="w-full bg-gradient-to-r from-lime-400/20 to-emerald-500/20 hover:bg-green-600 dark:bg-green-500 dark:hover:bg-green-600 dark:text-white text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold"
                            >
                                <i class="fas fa-users-cog mr-2"></i>Affecter joueurs
                            </button>
                            <button
                                @click="deleteTeam(team.id)"
                                class="w-full bg-gradient-to-r from-lime-400/20 to-emerald-500/20 hover:bg--700 dark:bg-red-500 dark:hover:bg-red-600 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold"
                            >
                                <i class="fas fa-trash mr-2"></i>Supprimer
                            </button>
                        </div>

                        <!-- View button only (Member) -->
                        <div v-else class="flex flex-col gap-2">
                            <button
                                @click="viewTeam(team.id)"
                                class="w-full bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-gray-800 px-4 py-2 rounded-lg transition text-sm font-semibold"
                            >
                                <span class="text-black"><i class="fas fa-eye mr-2"></i>Voir l'équipe</span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Empty state -->
            <div v-if="filteredTeams.length === 0" class="text-center py-12">
                <i class="fas fa-inbox text-gray-300 dark:text-citron-700 text-5xl mb-4"></i>
                <p class="text-gray-500 dark:text-citron-400 text-lg">Aucune équipe trouvée</p>
            </div>
        </main>
    </div>

    <!-- Delete confirmation modal -->
    <ConfirmModal
        v-model="showDeleteModal"
        title="Supprimer l'équipe"
        message="Êtes-vous sûr(e) de vouloir supprimer cette équipe ? Cette action est irréversible."
        confirm-text="Supprimer"
        cancel-text="Annuler"
        variant="danger"
        @confirm="confirmDeleteTeam"
    />
</AuthenticatedLayout>
</template>
<style>
.fade-enter-active,
.fade-leave-active {
  transition: opacity .3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

@keyframes bounceIn {
  0%   { transform: scale(0.7); opacity: 0; }
  60%  { transform: scale(1.05); opacity: 1; }
  100% { transform: scale(1); }
}

.animate-bounce-in {
  animation: bounceIn .4s ease forwards;
}
</style>
