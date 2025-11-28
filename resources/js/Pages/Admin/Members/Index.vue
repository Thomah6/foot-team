<script setup>
// Imports des composants et librairies nécessaires
import { ref, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props reçues du contrôleur Laravel
// members: Objet pagine contenant les données des membres
// filters: Objet contenant les filtres appliqués actuellement
defineProps({
  members: Object,
  filters: Object,
})

// ÉTAT RÉACTIF - Variables pour gérer l'UI
// Modaux d'affichage
const showDeleteModal = ref(false)
const showRoleModal = ref(false)

// Données sélectionnées
const selectedMember = ref(null)
const selectedRole = ref('')

// Paramètres de filtrage et recherche
const searchQuery = ref('')
const roleFilter = ref('')
const statusFilter = ref('')
const perPageValue = ref(10)

// Formulaire pour envoyer les données au serveur
const form = useForm({
  search: '',
  role: '',
  status: '',
  perPage: 10,
})

// WATCH - Surveillance automatique des changements de recherche
// Déclenche automatiquement la requête lors de chaque modification
// Initialise les champs à partir des filtres fournis par le backend
onMounted(() => {
  searchQuery.value = (filters && filters.search) || '';
  roleFilter.value = (filters && filters.role) || '';
  statusFilter.value = (filters && filters.status) || '';
  perPageValue.value = (filters && filters.perPage) || 10;
});

// Recherche en direct (live search) avec debounce manuel
let searchTimeout = null;
watch(searchQuery, (val) => {
  if (searchTimeout) clearTimeout(searchTimeout);
  // Attendre 300ms après la frappe avant d'envoyer la requête
  searchTimeout = setTimeout(() => {
    form.search = val;
    // utilise preserveState pour conserver le composant Inertia et éviter un flash
    form.get(route('members.index'), { preserveState: true, preserveScroll: true });
  }, 300);
});

// Les autres filtres déclenchent la recherche immédiatement
watch([roleFilter, statusFilter, perPageValue], () => {
  handleFilter();
});

// FONCTION: Applique les filtres et effectue la recherche
// Met à jour le formulaire avec les valeurs actuelles et envoie les données au serveur
const handleFilter = () => {
  form.search = searchQuery.value
  form.role = roleFilter.value
  form.status = statusFilter.value
  form.perPage = perPageValue.value
  form.get(route('members.index'), {
    preserveScroll: true,
  })
}

// FONCTION: Réinitialise tous les filtres aux valeurs par défaut
const resetFilters = () => {
  searchQuery.value = ''
  roleFilter.value = ''
  statusFilter.value = ''
  perPageValue.value = 10
  form.reset()
  form.get(route('members.index'), {
    preserveScroll: true,
  })
}

// FONCTION: Ouvre le modal pour changer le rôle d'un membre
const openRoleModal = (member) => {
  selectedMember.value = member
  selectedRole.value = member.role
  showRoleModal.value = true
}

// FONCTION: Envoie la requête de changement de rôle au serveur
const updateRole = () => {
  const roleForm = useForm({
    role: selectedRole.value,
  })
  roleForm.patch(route('members.update-role', selectedMember.value.id), {
    onSuccess: () => {
      showRoleModal.value = false
    },
  })
}

// FONCTION: Bascule l'état activation/désactivation d'un membre
const toggleStatus = (member) => {
  const statusForm = useForm({})
  statusForm.patch(route('members.toggle-status', member.id), {
    preserveScroll: true,
    preserveState: true,
  })
}

// FONCTION: Prépare la suppression d'un membre (ouvre le modal de confirmation)
const deleteMember = (member) => {
  selectedMember.value = member
  showDeleteModal.value = true
}

// FONCTION: Confirme et exécute la suppression du membre
const confirmDelete = () => {
  const deleteForm = useForm({})
  deleteForm.delete(route('members.destroy', selectedMember.value.id), {
    onSuccess: () => {
      showDeleteModal.value = false
    },
  })
}

// FONCTION: Retourne les couleurs Tailwind selon le rôle du membre
// Utilisée pour styliser les badges de rôles
const getRoleColor = (role) => {
  const colors = {
    admin: 'bg-red-50 text-red-700 border-red-200',
    bureau: 'bg-blue-50 text-blue-700 border-blue-200',
    simple: 'bg-gray-50 text-gray-700 border-gray-200',
  }
  return colors[role] || 'bg-gray-50 text-gray-700'
}

// FONCTION: Retourne le label traduit d'un rôle
const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrateur',
    bureau: 'Bureau',
    simple: 'Joueur',
  }
  return labels[role] || role
}

// FONCTION: Retourne la couleur du statut
const getStatusColor = (isActive) => {
  return isActive 
    ? 'bg-green-50 text-green-700 border-green-200' 
    : 'bg-red-50 text-red-700 border-red-200'
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 py-6">
      <!-- Header Section -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Gestion des Joueurs</h1>
            <p class="mt-1 text-sm text-gray-600">Administrez l'effectif de votre équipe</p>
          </div>
          <Link 
            :href="route('members.create')"
            class="inline-flex items-center justify-center rounded-lg bg-green-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-all duration-200 hover:scale-105"
          >
            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Ajouter un Joueur
          </Link>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-8">
        <div class="rounded-xl bg-white p-6 shadow-sm border border-gray-200">
          <div class="flex items-center mb-6">
            <div class="w-1.5 h-6 bg-green-500 rounded-full mr-3"></div>
            <h2 class="text-lg font-semibold text-gray-900">Filtres et Recherche</h2>
          </div>

          <!-- Search Input -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
            <div class="flex gap-3">
              <div class="relative flex-1">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                  <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                  </svg>
                </div>
                <input 
                  v-model="searchQuery" 
                  type="text" 
                  placeholder="Rechercher par nom, email ou pseudo..."
                  class="block w-full rounded-lg border border-gray-300 py-3 pl-10 pr-4 text-sm placeholder-gray-400 focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
                />
              </div>
              <button 
                @click="handleFilter"
                class="rounded-lg bg-gray-800 px-4 py-3 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200"
              >
                Appliquer
              </button>
            </div>
          </div>

          <!-- Filter Grid -->
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Role Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Rôle</label>
              <select 
                v-model="roleFilter"
                class="block w-full rounded-lg border border-gray-300 py-2.5 px-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
              >
                <option value="">Tous les rôles</option>
                <option value="admin">Administrateur</option>
                <option value="bureau">Bureau</option>
                <option value="simple">Joueur</option>
              </select>
            </div>

            <!-- Status Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Statut</label>
              <select 
                v-model="statusFilter"
                class="block w-full rounded-lg border border-gray-300 py-2.5 px-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
              >
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>

            <!-- Per Page -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1.5">Éléments par page</label>
              <select 
                v-model.number="perPageValue"
                class="block w-full rounded-lg border border-gray-300 py-2.5 px-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
              >
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-2 items-end">
              <button 
                @click="handleFilter"
                class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-all duration-200 hover:scale-105"
              >
                Filtrer
              </button>
              <button 
                @click="resetFilters"
                class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200"
              >
                Réinitialiser
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Members Table Section -->
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-xl bg-white shadow-sm border border-gray-200 overflow-hidden">
          <!-- Mobile View -->
          <div class="block sm:hidden divide-y divide-gray-200">
            <div v-for="member in members.data" :key="member.id" class="p-6 hover:bg-gray-50 transition-all duration-200">
              <div class="flex items-start justify-between mb-4">
                <div class="flex items-center">
                  <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100 mr-4">
                    <span class="text-sm font-semibold text-green-700">{{ member.pseudo.charAt(0) }}</span>
                  </div>
                  <div>
                    <h3 class="font-semibold text-gray-900 text-lg">{{ member.pseudo }}</h3>
                    <p class="text-sm text-gray-600 mt-1">{{ member.email }}</p>
                  </div>
                </div>
                <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getRoleColor(member.role)]">
                  {{ getRoleLabel(member.role) }}
                </span>
              </div>

              <div class="mb-4 space-y-3">
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Position:</span>
                  <span class="font-medium text-gray-900">{{ member.position || 'Non définie' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span class="text-gray-500">Statut:</span>
                  <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                    {{ member.is_active ? 'Actif' : 'Inactif' }}
                  </span>
                </div>
              </div>

              <div class="flex gap-2 pt-4 border-t border-gray-200">
                <button 
                  @click="openRoleModal(member)"
                  class="flex-1 rounded-lg bg-blue-50 px-3 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-100 transition-all duration-200"
                >
                  Rôle
                </button>
                <button 
                  @click="toggleStatus(member)"
                  :class="member.is_active 
                    ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' 
                    : 'bg-green-50 text-green-700 hover:bg-green-100'"
                  class="flex-1 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-200"
                >
                  {{ member.is_active ? 'Désactiver' : 'Activer' }}
                </button>
                <Link 
                  :href="route('members.edit', member.id)"
                  class="flex-1 rounded-lg bg-purple-50 px-3 py-2.5 text-sm font-medium text-purple-700 hover:bg-purple-100 transition-all duration-200 text-center"
                >
                  Éditer
                </Link>
                <button 
                  @click="deleteMember(member)"
                  class="flex-1 rounded-lg bg-red-50 px-3 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100 transition-all duration-200"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </div>

          <!-- Desktop View -->
          <div class="hidden sm:block overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Joueur</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Position</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Rôle</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Statut</th>
                  <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 bg-white">
                <tr v-for="member in members.data" :key="member.id" class="hover:bg-gray-50 transition-all duration-200">
                  <td class="whitespace-nowrap px-6 py-4">
                    <div class="flex items-center">
                      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 mr-3">
                        <span class="text-sm font-semibold text-green-700">{{ member.pseudo.charAt(0) }}</span>
                      </div>
                      <span class="font-medium text-gray-900">{{ member.pseudo }}</span>
                    </div>
                  </td>
                  <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600">{{ member.email }}</td>
                  <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ member.position || '-' }}</td>
                  <td class="whitespace-nowrap px-6 py-4">
                    <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getRoleColor(member.role)]">
                      {{ getRoleLabel(member.role) }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap px-6 py-4">
                    <span :class="['inline-flex items-center rounded-full border px-3 py-1 text-xs font-medium', getStatusColor(member.is_active)]">
                      {{ member.is_active ? 'Actif' : 'Inactif' }}
                    </span>
                  </td>
                  <td class="whitespace-nowrap px-6 py-4 text-sm">
                    <div class="flex gap-2">
                      <button 
                        @click="openRoleModal(member)"
                        class="rounded-lg bg-blue-50 px-3 py-2 text-sm font-medium text-blue-700 hover:bg-blue-100 transition-all duration-200"
                      >
                        Rôle
                      </button>
                      <button 
                        @click="toggleStatus(member)"
                        :class="member.is_active 
                          ? 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' 
                          : 'bg-green-50 text-green-700 hover:bg-green-100'"
                        class="rounded-lg px-3 py-2 text-sm font-medium transition-all duration-200"
                      >
                        {{ member.is_active ? 'Désactiver' : 'Activer' }}
                      </button>
                      <Link 
                        :href="route('members.edit', member.id)"
                        class="rounded-lg bg-purple-50 px-3 py-2 text-sm font-medium text-purple-700 hover:bg-purple-100 transition-all duration-200"
                      >
                        Éditer
                      </Link>
                      <button 
                        @click="deleteMember(member)"
                        class="rounded-lg bg-red-50 px-3 py-2 text-sm font-medium text-red-700 hover:bg-red-100 transition-all duration-200"
                      >
                        Supprimer
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="border-t border-gray-200 bg-gray-50 px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <div class="text-sm text-gray-700">
                Affichage de <span class="font-semibold">{{ members.from }}</span> à <span class="font-semibold">{{ members.to }}</span> sur 
                <span class="font-semibold">{{ members.total }}</span> joueurs
              </div>

              <div class="flex gap-2">
                <Link 
                  v-if="members.prev_page_url" 
                  :href="members.prev_page_url"
                  class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200"
                >
                  Précédent
                </Link>
                <span 
                  v-else 
                  class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400"
                >
                  Précédent
                </span>

                <span class="flex items-center px-4 py-2 text-sm text-gray-700">
                  Page {{ members.current_page }} sur {{ members.last_page }}
                </span>

                <Link 
                  v-if="members.next_page_url" 
                  :href="members.next_page_url"
                  class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200"
                >
                  Suivant
                </Link>
                <span 
                  v-else 
                  class="inline-flex items-center rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm font-medium text-gray-400"
                >
                  Suivant
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Role Modal -->
      <div v-if="showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-lg border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Modifier le rôle</h3>
          <p class="text-sm text-gray-600 mb-4">Joueur: <span class="font-medium">{{ selectedMember?.pseudo }}</span></p>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rôle</label>
            <select 
              v-model="selectedRole"
              class="block w-full rounded-lg border border-gray-300 py-2.5 px-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 transition-colors"
            >
              <option value="simple">Joueur</option>
              <option value="bureau">Bureau</option>
              <option value="admin">Administrateur</option>
            </select>
          </div>

          <div class="flex gap-3">
            <button 
              @click="showRoleModal = false"
              class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200"
            >
              Annuler
            </button>
            <button 
              @click="updateRole"
              class="flex-1 rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-500 transition-all duration-200 hover:scale-105"
            >
              Confirmer
            </button>
          </div>
        </div>
      </div>

      <!-- Delete Modal -->
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
        <div class="w-full max-w-sm rounded-xl bg-white p-6 shadow-lg border border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirmer la suppression</h3>
          <p class="text-sm text-gray-600 mb-6">
            Êtes-vous sûr de vouloir supprimer le joueur <span class="font-medium">{{ selectedMember?.pseudo }}</span> ? 
            Cette action ne peut pas être annulée.
          </p>

          <div class="flex gap-3">
            <button 
              @click="showDeleteModal = false"
              class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all duration-200"
            >
              Annuler
            </button>
            <button 
              @click="confirmDelete"
              class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-500 transition-all duration-200 hover:scale-105"
            >
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>