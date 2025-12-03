<script setup>
// Imports des composants et librairies nécessaires
import { ref, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
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
  searchQuery.value = (props.filters && props.filters.search) || '';
  roleFilter.value = (props.filters && props.filters.role) || '';
  statusFilter.value = (props.filters && props.filters.status) || '';
  perPageValue.value = (props.filters && props.filters.perPage) || 10;
});

// Recherche live avec debounce
let searchTimeout = null;
watch(searchQuery, (val) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    form.search = val;
    form.get(route('admin.members.index'), { preserveState: true, preserveScroll: true });
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
  form.get(route('admin.members.index'), { preserveScroll: true })
}

const resetFilters = () => {
  searchQuery.value = ''
  roleFilter.value = ''
  statusFilter.value = ''
  perPageValue.value = 10
  form.reset()
  form.get(route('admin.members.index'), { preserveScroll: true })
}

// Modaux
const openRoleModal = (member) => {
  selectedMember.value = member
  selectedRole.value = member.role
  showRoleModal.value = true
}

const updateRole = () => {
  const roleForm = useForm({ role: selectedRole.value })
  roleForm.patch(route('admin.members.update-role', selectedMember.value.id), {
    onSuccess: () => showRoleModal.value = false,
  })
}

const toggleStatus = (member) => {
  const statusForm = useForm({})
  statusForm.patch(route('admin.members.toggle-status', member.id), {
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
  deleteForm.delete(route('admin.members.destroy', selectedMember.value.id), {
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
    admin: 'bg-red-50 text-red-700 border-red-200 dark:bg-red-700 dark:text-red-50 dark:border-red-600',
    bureau: 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-700 dark:text-blue-50 dark:border-blue-600',
    simple: 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600',
  }
  return colors[role] || 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600'
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
    ? 'bg-green-50 text-green-700 border-green-200 dark:bg-green-700 dark:text-green-50 dark:border-green-600'
    : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-700 dark:text-red-50 dark:border-red-600'
}
</script>

<style>
/* Boutons globaux cohérents */
.btn-primary {
  @apply rounded-lg bg-gradient-to-r from-lime-500 to-emerald-500 px-4 py-2.5 text-sm font-semibold text-white hover:from-lime-600 hover:to-emerald-600 transition shadow-sm;
}

.btn-secondary {
  @apply rounded-lg border border-lime-300 bg-white dark:bg-gray-700 dark:text-gray-100 px-4 py-2.5 text-sm font-medium text-lime-700 hover:bg-lime-50 dark:hover:bg-gray-600 transition;
}

/* Champs */
.input-select {
  @apply w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2.5 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700 focus:ring-green-600 focus:border-green-600;
}
</style>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
      <!-- HEADER -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Gestion des Joueurs</h1>
            <p class="text-sm text-gray-600 dark:text-gray-300">Administration de l'effectif</p>
          </div>
          <Link :href="route('admin.members.create')" class="btn-primary inline-flex items-center gap-2">
            Ajouter un nouveau membre
          </Link>
        </div>
      </div>

      <!-- NOTIFICATION BANNER -->
      <div v-if="inactiveUsersCount > 0" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="rounded-xl bg-amber-50 dark:bg-amber-700 border border-amber-200 dark:border-amber-600 p-4 shadow-sm">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-amber-400 dark:text-amber-100" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
              </div>
              <div>
                <h3 class="text-sm font-medium text-amber-800 dark:text-amber-100">
                  Nouveaux utilisateurs en attente d'activation
                </h3>
                <p class="text-sm text-amber-700 dark:text-amber-200 mt-1">
                  {{ inactiveUsersCount }} utilisateur{{ inactiveUsersCount > 1 ? 's' : '' }} nécessite{{ inactiveUsersCount > 1 ? 'nt' : '' }} une activation.
                </p>
              </div>
            </div>
            <div class="flex gap-2">
              <button @click="filterInactiveUsers" class="bg-amber-100 dark:bg-amber-800 hover:bg-amber-200 dark:hover:bg-amber-700 text-amber-800 dark:text-amber-50 px-3 py-2 rounded-md text-sm font-medium transition">
                Voir les inactifs
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- FILTRES -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-6">
        <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
          <h2 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2 mb-5">
            <span class="w-1 h-5 bg-green-600 rounded-full"></span>
            Filtres & Recherche
          </h2>

          <!-- Recherche -->
          <div class="mb-5">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Recherche</label>
            <div class="relative">
              <input v-model="searchQuery" type="text" placeholder="Rechercher un joueur..." 
                     class="w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2.5 pl-10 text-sm text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-700
                     focus:ring-green-600 focus:border-green-600" />
              <svg class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-gray-400 dark:text-gray-300" fill="none" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Grille filtres -->
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Rôle</label>
              <select v-model="roleFilter" class="input-select">
                <option value="">Tous</option>
                <option value="admin">Administrateur</option>
                <option value="bureau">Bureau</option>
                <option value="simple">Joueur</option>
              </select>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Statut</label>
              <select v-model="statusFilter" class="input-select">
                <option value="">Tous</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>

            <div>
              <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Par page</label>
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
      <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white text-center underline mb-5">
        Membres
      </h1>

      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
          <!-- MOBILE -->
          <div class="block sm:hidden divide-y divide-gray-200 dark:divide-gray-600">
            <div v-for="member in members.data" :key="member.id" class="p-5 hover:bg-gray-50 dark:hover:bg-gray-700">
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div class="h-12 w-12 rounded-full bg-green-100 dark:bg-green-700 flex items-center justify-center">
                    <span class="font-semibold text-green-700 dark:text-green-50">
                      {{ member.pseudo.charAt(0) }}
                    </span>
                  </div>
                  <div>
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 text-base">{{ member.pseudo }}</h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300">{{ member.email }}</p>
                  </div>
                </div>

                <span :class="['inline-flex items-center rounded-full border px-2 py-1 text-xs font-medium', getRoleColor(member.role)]">
                  {{ getRoleLabel(member.role) }}
                </span>
              </div>

              <div class="text-sm space-y-2 mb-4">
                <div class="flex justify-between">
                  <span class="text-gray-500 dark:text-gray-300">Position</span>
                  <span class="font-medium text-gray-900 dark:text-gray-100">{{ member.position || 'Non définie' }}</span>
                </div>

                <div class="flex justify-between">
                  <span class="text-gray-500 dark:text-gray-300">Statut</span>
                  <span :class="['inline-flex items-center rounded-full border px-2 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                    {{ member.is_active ? 'Actif' : 'Inactif' }}
                  </span>
                </div>
              </div>

              <div class="flex gap-2 pt-3 border-t border-gray-200 dark:border-gray-600">
                <button @click="openRoleModal(member)" class="btn-secondary flex-1">Rôle</button>

                <button @click="toggleStatus(member)"
                  class="flex-1 rounded-lg px-3 py-2.5 text-sm font-medium border"
                  :class="member.is_active
                    ? 'bg-yellow-50 dark:bg-yellow-700 text-yellow-700 dark:text-yellow-50 border-yellow-200 dark:border-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-600'
                    : 'bg-green-50 dark:bg-green-700 text-green-700 dark:text-green-50 border-green-200 dark:border-green-600 hover:bg-green-100 dark:hover:bg-green-600'">
                  {{ member.is_active ? 'Désactiver' : 'Activer' }}
                </button>

                <Link :href="route('admin.members.edit', member.id)" class="btn-secondary flex-1 text-center">Éditer</Link>

                <button @click="deleteMember(member)" class="btn-secondary flex-1 border-red-300 text-red-700 dark:border-red-600 dark:text-red-50">Supprimer</button>
              </div>
            </div>
          </div>

          <!-- DESKTOP -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                <tr>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Joueur</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Email</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Position</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Rôle</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Statut</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                <tr v-for="member in members.data" :key="member.id" :class="['hover:bg-gray-50 dark:hover:bg-gray-700', !member.is_active ? 'bg-amber-50 dark:bg-amber-700 border-l-4 border-amber-400 dark:border-amber-600' : '']">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 rounded-full bg-green-100 dark:bg-green-700 flex items-center justify-center">
                        <span class="font-semibold text-green-700 dark:text-green-50">{{ member.pseudo.charAt(0) }}</span>
                      </div>
                      <span class="font-medium text-gray-900 dark:text-gray-100">{{ member.pseudo }}</span>
                    </div>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">{{ member.email }}</td>
                  <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ member.position || '-' }}</td>
                  <td class="px-6 py-4">
                    <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getRoleColor(member.role)]">
                      {{ getRoleLabel(member.role) }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                      {{ member.is_active ? 'Actif' : 'Inactif' }}
                    </span>
                  </td>
                  <td class="px-6 py-4 flex gap-2">
                    <button @click="openRoleModal(member)" class="btn-secondary flex-1">Rôle</button>
                    <button @click="toggleStatus(member)"
                      class="flex-1 rounded-lg px-3 py-2.5 text-sm font-medium border"
                      :class="member.is_active
                        ? 'bg-yellow-50 dark:bg-yellow-700 text-yellow-700 dark:text-yellow-50 border-yellow-200 dark:border-yellow-600 hover:bg-yellow-100 dark:hover:bg-yellow-600'
                        : 'bg-green-50 dark:bg-green-700 text-green-700 dark:text-green-50 border-green-200 dark:border-green-600 hover:bg-green-100 dark:hover:bg-green-600'">
                      {{ member.is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                    <Link :href="route('admin.members.edit', member.id)" class="btn-secondary flex-1 text-center">Éditer</Link>
                    <button @click="deleteMember(member)" class="btn-secondary flex-1 border-red-300 text-red-700 dark:border-red-600 dark:text-red-50">Supprimer</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- MODALES -->
      <!-- Role Modal -->
      <div v-if="showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Modifier le rôle</h3>
          <select v-model="selectedRole" class="input-select w-full mb-4">
            <option value="admin">Administrateur</option>
            <option value="bureau">Bureau</option>
            <option value="simple">Joueur</option>
          </select>
          <div class="flex justify-end gap-2">
            <button @click="showRoleModal = false" class="btn-secondary">Annuler</button>
            <button @click="updateRole" class="btn-primary">Enregistrer</button>
          </div>
        </div>
      </div>

      <!-- Delete Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-96">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Confirmer la suppression</h3>
          <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
            Êtes-vous sûr de vouloir supprimer {{ selectedMember?.pseudo }} ?
          </p>
          <div class="flex justify-end gap-2">
            <button @click="showDeleteModal = false" class="btn-secondary">Annuler</button>
            <button @click="confirmDelete" class="btn-primary bg-red-600 hover:bg-red-700">Supprimer</button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
