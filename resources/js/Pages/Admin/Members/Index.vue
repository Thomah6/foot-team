<script setup>
// Imports des composants et librairies nécessaires
import { ref, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

defineProps({
  members: Object,
  filters: Object,
})

// ÉTAT
const showDeleteModal = ref(false)
const showRoleModal = ref(false)
const selectedMember = ref(null)
const selectedRole = ref('')

const searchQuery = ref('')
const roleFilter = ref('')
const statusFilter = ref('')
const perPageValue = ref(10)

const form = useForm({
  search: '',
  role: '',
  status: '',
  perPage: 10,
})

onMounted(() => {
  searchQuery.value = (filters && filters.search) || '';
  roleFilter.value = (filters && filters.role) || '';
  statusFilter.value = (filters && filters.status) || '';
  perPageValue.value = (filters && filters.perPage) || 10;
});

// Recherche live avec debounce
let searchTimeout = null;
watch(searchQuery, (val) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    form.search = val;
    form.get(route('members.index'), { preserveState: true, preserveScroll: true });
  }, 300);
});

// Filtres
watch([roleFilter, statusFilter, perPageValue], () => {
  handleFilter();
});

const handleFilter = () => {
  form.search = searchQuery.value
  form.role = roleFilter.value
  form.status = statusFilter.value
  form.perPage = perPageValue.value
  form.get(route('members.index'), { preserveScroll: true })
}

const resetFilters = () => {
  searchQuery.value = ''
  roleFilter.value = ''
  statusFilter.value = ''
  perPageValue.value = 10
  form.reset()
  form.get(route('members.index'), { preserveScroll: true })
}

// Modaux
const openRoleModal = (member) => {
  selectedMember.value = member
  selectedRole.value = member.role
  showRoleModal.value = true
}

const updateRole = () => {
  const roleForm = useForm({ role: selectedRole.value })
  roleForm.patch(route('members.update-role', selectedMember.value.id), {
    onSuccess: () => showRoleModal.value = false,
  })
}

const toggleStatus = (member) => {
  const statusForm = useForm({})
  statusForm.patch(route('members.toggle-status', member.id), {
    preserveScroll: true,
    preserveState: true,
  })
}

const deleteMember = (member) => {
  selectedMember.value = member
  showDeleteModal.value = true
}

const confirmDelete = () => {
  const deleteForm = useForm({})
  deleteForm.delete(route('members.destroy', selectedMember.value.id), {
    onSuccess: () => showDeleteModal.value = false,
  })
}

const filterInactiveUsers = () => {
  statusFilter.value = 'inactive'
  handleFilter()
}

// Styles role
const getRoleColor = (role) => {
  const colors = {
    admin: 'bg-red-50 text-red-700 border-red-200',
    bureau: 'bg-blue-50 text-blue-700 border-blue-200',
    simple: 'bg-gray-50 text-gray-700 border-gray-200',
  }
  return colors[role] || 'bg-gray-50 text-gray-700 border-gray-200'
}

const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrateur',
    bureau: 'Bureau',
    simple: 'Simple',
  }
  return labels[role] || role
}

// Statuts
const getStatusColor = (isActive) => {
  return isActive
    ? 'bg-green-50 text-green-700 border-green-200'
    : 'bg-red-50 text-red-700 border-red-200'
}
</script>

<style>
/* Boutons globaux cohérents */
.btn-primary {
  @apply rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-green-700 transition shadow-sm;
}

.btn-secondary {
  @apply rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition;
}

/* Champs */
.input-select {
  @apply w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm focus:ring-green-600 focus:border-green-600;
}
</style>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 py-6">

      <!-- HEADER -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Joueurs</h1>
            <p class="text-sm text-gray-600">Administration de l'effectif</p>
          </div>

          <Link :href="route('members.create')" class="btn-primary inline-flex items-center gap-2">
          Ajouter un nouveau membre
          </Link>
        </div>
      </div>

      <!-- NOTIFICATION BANNER -->
      <div v-if="inactiveUsersCount > 0" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="rounded-xl bg-amber-50 border border-amber-200 p-4 shadow-sm">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <h3 class="text-sm font-medium text-amber-800">
                  Nouveaux utilisateurs en attente d'activation
                </h3>
                <p class="text-sm text-amber-700 mt-1">
                  {{ inactiveUsersCount }} utilisateur{{ inactiveUsersCount > 1 ? 's' : '' }} nécessite{{ inactiveUsersCount > 1 ? 'nt' : '' }} une activation.
                </p>
              </div>
            </div>
            <div class="flex gap-2">
              <button @click="filterInactiveUsers" class="bg-amber-100 hover:bg-amber-200 text-amber-800 px-3 py-2 rounded-md text-sm font-medium transition">
                Voir les inactifs
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- FILTRES -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="rounded-xl bg-white border border-gray-200 p-5 shadow-sm">

          <h2 class="text-base font-semibold text-gray-900 flex items-center gap-2 mb-5">
            <span class="w-1 h-5 bg-green-600 rounded-full"></span>
            Filtres & Recherche
          </h2>

          <!-- Recherche -->
          <div class="mb-5">
            <label class="text-sm font-medium text-gray-700 mb-1 block">Recherche</label>
            <div class="relative">
              <input v-model="searchQuery" type="text" class="w-full rounded-lg border border-gray-300 px-3 py-2.5 pl-10 text-sm
                       focus:ring-green-600 focus:border-green-600" placeholder="Rechercher un joueur..." />
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400" fill="none"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Grille filtres -->
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">Rôle</label>
              <select v-model="roleFilter" class="input-select">
                <option value="">Tous</option>
                <option value="admin">Administrateur</option>
                <option value="bureau">Bureau</option>
                <option value="simple">Joueur</option>
              </select>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">Statut</label>
              <select v-model="statusFilter" class="input-select">
                <option value="">Tous</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-700 mb-1 block">Par page</label>
              <select v-model="perPageValue" class="input-select">
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>
            </div>

            <div class="flex gap-3 items-end">
              <button @click="handleFilter" class="btn-primary flex-1">Filtrer</button>
              <button @click="resetFilters" class="btn-secondary flex-1">Réinitialiser</button>
            </div>

          </div>
        </div>
      </div>

      <!-- TABLEAU -->
      <h1 class="text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center underline mb-5">
        Membres
      </h1>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white border border-gray-200 shadow-sm overflow-hidden">
          <!-- MOBILE -->
          <div class="block sm:hidden divide-y divide-gray-200">
            <div v-for="member in members.data" :key="member.id" class="p-5 hover:bg-gray-50">

              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <span class="font-semibold text-green-700">
                      {{ member.pseudo.charAt(0) }}
                    </span>
                  </div>
                  <div>
                    <h3 class="font-medium text-gray-900 text-base">{{ member.pseudo }}</h3>
                    <p class="text-xs text-gray-600">{{ member.email }}</p>
                  </div>
                </div>

                <span
                  :class="['inline-flex items-center rounded-full border px-2 py-1 text-xs font-medium', getRoleColor(member.role)]">
                  {{ getRoleLabel(member.role) }}
                </span>
              </div>

              <div class="text-sm space-y-2 mb-4">
                <div class="flex justify-between">
                  <span class="text-gray-500">Position</span>
                  <span class="font-medium text-gray-900">{{ member.position || 'Non définie' }}</span>
                </div>

                <div class="flex justify-between">
                  <span class="text-gray-500">Statut</span>
                  <span
                    :class="['inline-flex items-center rounded-full border px-2 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                    {{ member.is_active ? 'Actif' : 'Inactif' }}
                  </span>
                </div>
              </div>

              <div class="flex gap-2 pt-3 border-t border-gray-200">
                <button @click="openRoleModal(member)" class="btn-secondary flex-1">Rôle</button>

                <button @click="toggleStatus(member)"
                  class="flex-1 rounded-lg px-3 py-2.5 text-sm font-medium border transition" :class="member.is_active
                    ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100'
                    : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'">
                  {{ member.is_active ? 'Désactiver' : 'Activer' }}
                </button>

                <Link :href="route('members.edit', member.id)" class="btn-secondary flex-1 text-center">
                Éditer
                </Link>

                <button @click="deleteMember(member)" class="btn-secondary flex-1 border-red-300 text-red-700">
                  Supprimer
                </button>
              </div>
            </div>
          </div>

          <!-- DESKTOP -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Joueur</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Email</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Position</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Rôle</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Statut</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900">Actions</th>
                </tr>
              </thead>

              <tbody class="divide-y divide-gray-200">
                <tr v-for="member in members.data" :key="member.id" :class="['hover:bg-gray-50', !member.is_active ? 'bg-amber-50 border-l-4 border-amber-400' : '']">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                        <span class="font-semibold text-green-700">
                          {{ member.pseudo.charAt(0) }}
                        </span>
                      </div>
                      <span class="font-medium text-gray-900">{{ member.pseudo }}</span>
                    </div>
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-600">{{ member.email }}</td>
                  <td class="px-6 py-4 text-sm text-gray-900">{{ member.position || '-' }}</td>

                  <td class="px-6 py-4">
                    <span
                      :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getRoleColor(member.role)]">
                      {{ getRoleLabel(member.role) }}
                    </span>
                  </td>

                  <td class="px-6 py-4">
                    <span
                      :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                      {{ member.is_active ? 'Actif' : 'Inactif' }}
                    </span>
                  </td>

                  <td class="px-6 py-4">
                    <div class="flex gap-2">
                      <button @click="openRoleModal(member)" class="btn-secondary">Rôle</button>

                      <button @click="toggleStatus(member)" class="rounded-lg px-3 py-2 text-sm font-medium border"
                        :class="member.is_active
                          ? 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100'
                          : 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100'">
                        {{ member.is_active ? 'Désactiver' : 'Activer' }}
                      </button>

                      <Link :href="route('members.edit', member.id)" class="btn-secondary">
                      Éditer
                      </Link>

                      <button @click="deleteMember(member)" class="btn-secondary border-red-300 text-red-700">
                        Supprimer
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- PAGINATION -->
          <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

              <div class="text-sm text-gray-700">
                Affichage de
                <span class="font-semibold">{{ members.from }}</span> à
                <span class="font-semibold">{{ members.to }}</span> sur
                <span class="font-semibold">{{ members.total }}</span> joueurs
              </div>

              <div class="flex items-center gap-2">

                <Link v-if="members.prev_page_url" :href="members.prev_page_url" class="btn-secondary">Précédent</Link>

                <span v-else class="btn-secondary text-gray-400 border-gray-200">Précédent</span>

                <span class="px-3 text-sm">
                  Page {{ members.current_page }} / {{ members.last_page }}
                </span>

                <Link v-if="members.next_page_url" :href="members.next_page_url" class="btn-secondary">Suivant</Link>

                <span v-else class="btn-secondary text-gray-400 border-gray-200">Suivant</span>

              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- MODAL ROLE -->
      <div v-if="showRoleModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-xl p-6 border shadow">

          <h3 class="text-lg font-semibold text-gray-900 mb-2">Modifier le rôle</h3>
          <p class="text-sm text-gray-600 mb-4">
            Joueur : <span class="font-medium">{{ selectedMember?.pseudo }}</span>
          </p>

          <label class="text-sm font-medium text-gray-700 mb-2 block">Rôle</label>
          <select v-model="selectedRole" class="input-select mb-6">
            <option value="simple">Simple</option>
            <option value="bureau">Bureau</option>
            <option value="admin">Administrateur</option>
          </select>

          <div class="flex gap-3">
            <button @click="showRoleModal = false" class="btn-secondary flex-1">Annuler</button>
            <button @click="updateRole" class="btn-primary flex-1">Confirmer</button>
          </div>

        </div>
      </div>

      <!-- MODAL SUPPRESSION -->
      <div v-if="showDeleteModal"
        class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-sm rounded-xl p-6 border shadow">

          <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmer la suppression</h3>
          <p class="text-sm text-gray-600 mb-6">
            Êtes-vous sûr de vouloir supprimer
            <span class="font-medium">{{ selectedMember?.pseudo }}</span> ?
            Cette action est irréversible.
          </p>

          <div class="flex gap-3">
            <button @click="showDeleteModal = false" class="btn-secondary flex-1">Annuler</button>
            <button @click="confirmDelete" class="btn-primary bg-red-600 hover:bg-red-700 flex-1">
              Supprimer
            </button>
          </div>

        </div>
      </div>

    </div>
  </AuthenticatedLayout>
</template>
