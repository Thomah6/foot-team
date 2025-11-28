<template>
    <AuthenticatedLayout>

        <div class="py-4 sm:py-6">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Filters header -->
                <div class="mb-6 rounded-lg bg-white p-4 shadow-sm sm:p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800">Recherche et filtres</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rechercher</label>
                        <div class="flex gap-2">
                            <input v-model="searchQuery" type="text" placeholder="Nom, pseudo ou email..."
                                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                            <button @click="handleFilter"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-white font-medium hover:bg-indigo-700 transition">Rechercher</button>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Rôle</label>
                            <select v-model="roleFilter"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Tous les rôles</option>
                                <option value="admin">Administrateur</option>
                                <option value="bureau">Bureau</option>
                                <option value="simple">Simple</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Équipe</label>
                            <select v-model="teamFilter"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Toutes les équipes</option>
                                <option v-for="t in teams" :key="t.id" :value="t.id">{{ t.name }}</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                            <select v-model="statusFilter"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option value="">Tous</option>
                                <option value="active">Actif</option>
                                <option value="inactive">Inactif</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Inscription (de → à)</label>
                            <div class="flex gap-2">
                                <input v-model="registeredFrom" type="date"
                                    class="w-1/2 rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                                <input v-model="registeredTo" type="date"
                                    class="w-1/2 rounded-lg border border-gray-300 px-3 py-2 text-sm" />
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Par page</label>
                            <select v-model.number="perPageValue"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                                <option :value="10">10</option>
                                <option :value="25">25</option>
                                <option :value="50">50</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button @click="handleFilter"
                                class="flex-1 rounded-lg bg-green-600 px-4 py-2 text-white font-medium hover:bg-green-700">Appliquer</button>
                            <button @click="resetFilters"
                                class="flex-1 rounded-lg bg-gray-200 px-4 py-2 text-gray-800">Réinitialiser</button>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="rounded-lg bg-white shadow-sm overflow-hidden">
                    <div class="hidden sm:block">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 border-b border-gray-200">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Nom</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Email</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Rôle</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Équipe(s)</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Statut</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                            Inscrit le</th>

                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    <tr v-for="member in members.data" :key="member.id"
                                        class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap"><span
                                                class="font-medium text-gray-900">{{ member.name || member.pseudo
                                                }}</span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ member.email }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="['px-3 py-1 rounded-full text-xs font-medium', getRoleColor(member.role)]">
                                                {{ getRoleLabel(member.role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <template v-if="member.teams && member.teams.length">
                                                <span v-for="t in member.teams" :key="t.id"
                                                    class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs mr-1">{{
                                                        t.name }}</span>
                                            </template>
                                            <span v-else class="text-gray-400">—</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="member.is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                                                class="px-3 py-1 rounded-full text-xs font-medium">
                                                {{ member.is_active ? 'Actif' : 'Inactif' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ new
                                            Date(member.created_at).toLocaleDateString() }}
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Mobile cards -->
                    <div class="block sm:hidden">
                        <div v-for="member in members.data" :key="member.id" class="border-b border-gray-200 p-4">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ member.pseudo || member.name }}</h4>
                                    <p class="text-sm text-gray-600">{{ member.email }}</p>
                                </div>
                                <span :class="member.is_active ? 'text-green-600' : 'text-red-600'"
                                    class="text-sm font-medium">{{
                                        member.is_active ? 'Actif' : 'Inactif' }}</span>
                            </div>
                            <div class="flex gap-2">
                                <button @click="openProfile(member)"
                                    class="flex-1 rounded px-2 py-1 text-xs font-medium bg-indigo-50 text-indigo-700">Voir</button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="border-t border-gray-200 bg-gray-50 px-4 py-4 sm:px-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div class="text-sm text-gray-600">Affichage de <span class="font-medium">{{ members.from
                            }}</span> à <span class="font-medium">{{ members.to }}</span> sur <span
                                    class="font-medium">{{
                                    members.total }}</span>
                                membres</div>
                            <div class="flex gap-2 flex-wrap items-center">
                                <Link v-if="members.prev_page_url" :href="members.prev_page_url"
                                    class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                Précédent</Link>
                                <span v-else
                                    class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-400">Précédent</span>
                                <span class="px-3 py-2 text-sm text-gray-600">Page {{ members.current_page }} sur {{
                                    members.last_page ||
                                    members.last_page }}</span>
                                <Link v-if="members.next_page_url" :href="members.next_page_url"
                                    class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100">
                                Suivant</Link>
                                <span v-else
                                    class="rounded border border-gray-300 px-3 py-2 text-sm font-medium text-gray-400">Suivant</span>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Profile drawer -->
                <div v-if="showProfile"
                    class="fixed inset-0 z-50 flex items-end justify-end sm:items-center sm:justify-end bg-black bg-opacity-40">
                    <div class="w-full sm:w-1/3 bg-white p-6 overflow-auto">
                        <div class="flex items-start justify-between">
                            <h2 class="text-xl font-semibold">Profil : {{ selectedMember.name || selectedMember.pseudo
                            }}</h2>
                            <button @click="closeProfile" class="text-gray-400 hover:text-gray-700">Fermer</button>
                        </div>
                        <div class="mt-4 space-y-3">
                            <div><strong>Email:</strong> {{ selectedMember.email }}</div>
                            <div><strong>Rôle:</strong> {{ selectedMember.role }}</div>
                            <div><strong>Statut:</strong> <span
                                    :class="selectedMember.is_active ? 'text-green-600 border ' : 'text-red-600'"
                                    class="px-3 py-1 rounded-full text-xs font-medium">{{ selectedMember.is_active ?
                                        'Actif' : 'Inactif'
                                    }}</span></div>
                            <div><strong>Inscrit le:</strong> {{ new Date(selectedMember.created_at).toLocaleString() }}
                            </div>
                            <div>
                                <strong>Équipes:</strong>
                                <div class="mt-2">
                                    <template v-if="selectedMember.teams && selectedMember.teams.length">
                                        <span v-for="t in selectedMember.teams" :key="t.id"
                                            class="inline-block bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs mr-1">{{
                                                t.name }}</span>
                                    </template>
                                    <span v-else class="text-gray-400">—</span>
                                </div>
                            </div>
                            <div v-if="selectedMember.bio"><strong>Bio:</strong>
                                <div class="mt-2 text-sm text-gray-700">{{ selectedMember.bio }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1" @click="closeProfile"></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props provided by the controller
const props = defineProps({
    members: Object,
    teams: Array,
    filters: Object,
})

// UI state
const showProfile = ref(false)
const selectedMember = ref({})

// Filters state
const searchQuery = ref('')
const roleFilter = ref('')
const statusFilter = ref('')
const teamFilter = ref('')
const registeredFrom = ref('')
const registeredTo = ref('')
const perPageValue = ref(10)

// Inertia form to perform requests
const form = useForm({
    search: '',
    role: '',
    status: '',
    team_id: '',
    registered_from: '',
    registered_to: '',
    perPage: 10,
})

// Seed local filters from server-provided `filters` on mount
onMounted(() => {
    const f = props.filters || {}
    searchQuery.value = f.search || ''
    roleFilter.value = f.role || ''
    statusFilter.value = f.status || ''
    teamFilter.value = f.team_id || ''
    registeredFrom.value = f.registered_from || ''
    registeredTo.value = f.registered_to || ''
    perPageValue.value = f.perPage || 10

    // seed form
    form.search = searchQuery.value
    form.role = roleFilter.value
    form.status = statusFilter.value
    form.team_id = teamFilter.value
    form.registered_from = registeredFrom.value
    form.registered_to = registeredTo.value
    form.perPage = perPageValue.value
})

// Debounced live search
let searchTimeout = null
watch(searchQuery, (val) => {
    if (searchTimeout) clearTimeout(searchTimeout)
    searchTimeout = setTimeout(() => {
        form.search = val
        form.get(window.location.pathname, { preserveState: true, preserveScroll: true })
    }, 350)
})

// Other filters update immediately
watch([roleFilter, statusFilter, teamFilter, registeredFrom, registeredTo, perPageValue], () => {
    handleFilter()
})

function handleFilter() {
    form.search = searchQuery.value
    form.role = roleFilter.value
    form.status = statusFilter.value
    form.team_id = teamFilter.value
    form.registered_from = registeredFrom.value
    form.registered_to = registeredTo.value
    form.perPage = perPageValue.value
    form.get(window.location.pathname, { preserveState: true, preserveScroll: true })
}

function resetFilters() {
    searchQuery.value = ''
    roleFilter.value = ''
    statusFilter.value = ''
    teamFilter.value = ''
    registeredFrom.value = ''
    registeredTo.value = ''
    perPageValue.value = 10
    form.reset()
    form.get(window.location.pathname, { preserveState: true, preserveScroll: true })
}

function openProfile(member) {
    selectedMember.value = member
    showProfile.value = true
}

function closeProfile() {
    selectedMember.value = {}
    showProfile.value = false
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
