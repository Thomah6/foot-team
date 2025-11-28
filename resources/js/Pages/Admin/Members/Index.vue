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
  statusForm.patch(route('members.toggle-status', member.id))
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
    admin: 'bg-red-100 text-red-800',
    bureau: 'bg-blue-100 text-blue-800',
    simple: 'bg-gray-100 text-gray-800',
  }
  return colors[role] || 'bg-gray-100 text-gray-800'
}

// FONCTION: Retourne le label traduit d'un rôle
const getRoleLabel = (role) => {
  const labels = {
    admin: 'Administrateur',
    bureau: 'Bureau',
    simple: 'Simple',
  }
  return labels[role] || role
}
</script>

<template>
  <AuthenticatedLayout>
    <!-- En-tête de la page avec titre et bouton d'ajout -->
    
      <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Gestion des Membres</h2>
        <Link :href="route('members.create')" class="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 transition">
          + Ajouter un Membre
        </Link>
      </div>
    

    <div class="py-4 sm:py-6">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- SECTION FILTRES ET RECHERCHE -->
        <!-- Conteneur avec arrière-plan blanc et ombre pour les filtres -->
        <div class="mb-6 rounded-lg bg-white p-4 shadow-sm sm:p-6">
          <h3 class="mb-4 text-lg font-semibold text-gray-800">Filtres et Recherche</h3>
          
          <!-- CHAMP RECHERCHE - Recherche en temps réel par nom ou email -->
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
            <div class="flex gap-2">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Nom ou email..."
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <!-- Bouton désormais optionnel car la recherche est automatique -->
              <button
                @click="handleFilter"
                class="rounded-lg bg-blue-600 px-4 py-2 text-white font-medium hover:bg-blue-700 transition"
              >
                Rechercher
              </button>
            </div>
          </div>

          <!-- GRILLE DE FILTRES - Disposition responsive avec 4 colonnes sur desktop -->
          <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- FILTRE RÔLE - Filtre par rôle (admin, bureau, simple) -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
              <select
                v-model="roleFilter"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Tous les roles</option>
                <option value="admin">Administrateur</option>
                <option value="bureau">Bureau</option>
                <option value="simple">Simple</option>
              </select>
            </div>

            <!-- FILTRE STATUT - Filtre par activation/désactivation -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
              <select
                v-model="statusFilter"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Tous les statuts</option>
                <option value="active">Actif</option>
                <option value="inactive">Inactif</option>
              </select>
            </div>

            <!-- PAGINATION - Sélection du nombre de résultats par page -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Par page</label>
              <select
                v-model.number="perPageValue"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option :value="5">5</option>
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>
            </div>

            <!-- BOUTONS D'ACTION -->
            <div class="flex gap-2 items-end">
              <!-- Bouton Appliquer (moins utilisé avec la recherche automatique) -->
              <button
                @click="handleFilter"
                class="flex-1 rounded-lg bg-green-600 px-4 py-2 text-white font-medium hover:bg-green-700 transition text-sm"
              >
                Appliquer
              </button>
              <!-- Bouton Réinitialiser pour effacer tous les filtres -->
              <button
                @click="resetFilters"
                class="flex-1 rounded-lg bg-gray-300 px-4 py-2 text-gray-800 font-medium hover:bg-gray-400 transition text-sm"
              >
                Reinitialiser
              </button>
            </div>
          </div>
        </div>

        <!-- TABLEAU MEMBRE - Affichage des données -->
        <div class="rounded-lg bg-white shadow-sm overflow-hidden">
          <!-- VUE MOBILE - Cards empilées pour petits écrans -->
          <div class="block sm:hidden">
            <div v-for="member in members.data" :key="member.id" class="border-b border-gray-200 p-4">
              <!-- En-tête de la card: pseudo et rôle -->
              <div class="flex items-start justify-between mb-3">
                <div>
                  <h4 class="font-semibold text-gray-900">{{ member.pseudo }}</h4>
                  <p class="text-sm text-gray-600">{{ member.email }}</p>
                </div>
                <!-- Badge de rôle coloré -->
                <span :class="['px-2 py-1 rounded-full text-xs font-medium', getRoleColor(member.role)]">
                  {{ getRoleLabel(member.role) }}
                </span>
              </div>

              <!-- Corps de la card: informations du membre -->
              <div class="mb-3 space-y-2">
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600">Position:</span>
                  <span class="font-medium">{{ member.position || 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-600">Statut:</span>
                  <span :class="member.is_active ? 'text-green-600 font-medium' : 'text-red-600 font-medium'">
                    {{ member.is_active ? 'Actif' : 'Inactif' }}
                  </span>
                </div>
              </div>

              <!-- Actions sur la card: boutons pour éditer, changer rôle, etc. -->
              <div class="flex gap-2">
                <button
                  @click="openRoleModal(member)"
                  class="flex-1 rounded px-2 py-1 text-xs font-medium bg-blue-100 text-blue-700 hover:bg-blue-200 transition"
                >
                  Modifier Role
                </button>
                <button
                  @click="toggleStatus(member)"
                  :class="member.is_active ? 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200' : 'bg-green-100 text-green-700 hover:bg-green-200'"
                  class="flex-1 rounded px-2 py-1 text-xs font-medium transition"
                >
                  {{ member.is_active ? 'Desactiver' : 'Activer' }}
                </button>
                <Link
                  :href="route('members.edit', member.id)"
                  class="flex-1 rounded px-2 py-1 text-xs font-medium bg-purple-100 text-purple-700 hover:bg-purple-200 transition text-center"
                >
                  Editer
                </Link>
                <button
                  @click="deleteMember(member)"
                  class="flex-1 rounded px-2 py-1 text-xs font-medium bg-red-100 text-red-700 hover:bg-red-200 transition"
                >
                  Supprimer
                </button>
              </div>
            </div>
          </div>

          <!-- VUE DESKTOP - Tableau classique pour écrans moyens et grands -->
          <div class="hidden sm:block">
            <div class="overflow-x-auto">
              <table class="w-full">
                <!-- En-tête du tableau avec colonnes -->
                <thead class="bg-gray-50 border-b border-gray-200">
                  <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Pseudo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Position</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <!-- Corps du tableau avec les données des membres -->
                <tbody class="divide-y divide-gray-200">
                  <tr v-for="member in members.data" :key="member.id" class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="font-medium text-gray-900">{{ member.pseudo }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ member.email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ member.position || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="['px-3 py-1 rounded-full text-xs font-medium', getRoleColor(member.role)]">
                        {{ getRoleLabel(member.role) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="member.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="px-3 py-1 rounded-full text-xs font-medium">
                        {{ member.is_active ? 'Actif' : 'Inactif' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <div class="flex gap-2">
                        <button
                          @click="openRoleModal(member)"
                          class="px-3 py-1 text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 transition"
                        >
                          Role
                        </button>
                        <button
                          @click="toggleStatus(member)"
                          :class="member.is_active ? 'text-yellow-700 bg-yellow-100 hover:bg-yellow-200' : 'text-green-700 bg-green-100 hover:bg-green-200'"
                          class="px-3 py-1 text-xs font-medium rounded transition"
                        >
                          {{ member.is_active ? 'Desactiver' : 'Activer' }}
                        </button>
                        <Link
                          :href="route('members.edit', member.id)"
                          class="px-3 py-1 text-xs font-medium rounded text-purple-700 bg-purple-100 hover:bg-purple-200 transition"
                        >
                          Editer
                        </Link>
                        <button
                          @click="deleteMember(member)"
                          class="px-3 py-1 text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 transition"
                        >
                          Supprimer
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- PAGINATION - Affichage et navigation entre les pages -->
          <div class="border-t border-gray-200 bg-gray-50 px-4 py-4 sm:px-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <!-- Texte d'information sur les résultats affichés -->
              <div class="text-sm text-gray-600">
                Affichage de <span class="font-medium">{{ members.from }}</span> a <span class="font-medium">{{ members.to }}</span> sur <span class="font-medium">{{ members.total }}</span> membres
              </div>
              
              <!-- Boutons de navigation entre les pages -->
              <div class="flex gap-2 flex-wrap">
                <!-- Lien vers la page précédente (désactivé si on est à la première page) -->
                <Link
                  v-if="members.prev_page_url"
                  :href="members.prev_page_url"
                  class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition"
                >
                  Precedent
                </Link>
                <span v-else class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-400">
                  Precedent
                </span>

                <!-- Affichage du numéro de page actuel -->
                <span class="flex items-center px-3 py-2 text-sm text-gray-600">
                  Page {{ members.current_page }} sur {{ members.last_page }}
                </span>

                <!-- Lien vers la page suivante (désactivé si on est à la dernière page) -->
                <Link
                  v-if="members.next_page_url"
                  :href="members.next_page_url"
                  class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 transition"
                >
                  Suivant
                </Link>
                <span v-else class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-400">
                  Suivant
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL CHANGEMENT DE RÔLE -->
    <!-- Modal pour afficher/modifier le rôle d'un membre sélectionné -->
    <div v-if="showRoleModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
      <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Modifier le Role</h3>
        <p class="mb-4 text-sm text-gray-600">Membre: <span class="font-medium">{{ selectedMember?.pseudo }}</span></p>
        
        <div class="mb-6">
          <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
          <!-- Select pour choisir le nouveau rôle -->
          <select
            v-model="selectedRole"
            class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <option value="simple">Simple</option>
            <option value="bureau">Bureau</option>
            <option value="admin">Administrateur</option>
          </select>
        </div>

        <!-- Boutons d'action du modal -->
        <div class="flex gap-3">
          <button
            @click="showRoleModal = false"
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 transition"
          >
            Annuler
          </button>
          <button
            @click="updateRole"
            class="flex-1 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 transition"
          >
            Confirmer
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL SUPPRESSION -->
    <!-- Modal de confirmation avant suppression d'un membre -->
    <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
      <div class="w-full max-w-sm rounded-lg bg-white p-6 shadow-lg">
        <h3 class="mb-4 text-lg font-semibold text-gray-900">Confirmer la Suppression</h3>
        <p class="mb-6 text-sm text-gray-600">
          Etes-vous sur de vouloir supprimer le membre <span class="font-medium">{{ selectedMember?.pseudo }}</span> ? Cette action ne peut pas etre annulee.
        </p>

        <!-- Boutons d'action du modal -->
        <div class="flex gap-3">
          <button
            @click="showDeleteModal = false"
            class="flex-1 rounded-lg border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 transition"
          >
            Annuler
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 rounded-lg bg-red-600 px-4 py-2 font-medium text-white hover:bg-red-700 transition"
          >
            Supprimer
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
