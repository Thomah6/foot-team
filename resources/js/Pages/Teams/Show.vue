<template>
  <div class="flex flex-col md:flex-row min-h-screen bg-gray-50">

    <!-- Sidebar -->
    <section class="hidden md:block">
      <AdminsideBar />
    </section>

    <!-- Main content -->
    <main class="flex-1 w-full p-4 md:p-10 bg-gray-50 min-h-screen">
      <div class="max-w-6xl mx-auto">

        <!-- TOP BAR - Mobile optimized -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
          <div>
            <a href="/teams/index" class="text-blue-600 hover:text-blue-800 text-sm font-semibold flex items-center gap-1 mb-2">
              <i class="fas fa-arrow-left"></i> Retour
            </a>
            <h1 class="text-2xl sm:text-4xl font-extrabold tracking-tight text-gray-900">
              {{ props.team.name }}
            </h1>
            <p class="text-gray-600 text-sm mt-1">
              {{ isAdmin ? 'Gérez les membres de cette équipe' : 'Membres de l\'équipe' }}
            </p>
          </div>

          <button v-if="isAdmin" class="flex items-center justify-center gap-2 bg-blue-600 text-white px-4 py-3 sm:px-5 sm:py-3 rounded-lg shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200 text-sm sm:text-base font-semibold">
            <i class="fas fa-user-plus text-lg"></i>
            <span>Ajouter</span>
          </button>
        </div>

        <!-- Mobile filter toggle (Admin only) -->
        <div v-if="isAdmin" class="md:hidden mb-4">
          <details class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <summary class="font-semibold text-gray-800 cursor-pointer flex items-center gap-2 hover:text-blue-600">
              <i class="fas fa-filter"></i> Filtres
            </summary>
            <div class="mt-4 space-y-4">
              <!-- Search -->
              <div class="relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Rechercher..."
                  class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 px-3 text-sm focus:ring-2 focus:ring-blue-300 focus:bg-white transition"
                />
              </div>

              <!-- Role -->
              <div class="relative">
                <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <select
                  v-model="filters.role"
                  class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                >
                  <option value="">Tous les rôles</option>
                  <option value="admin">Admin</option>
                  <option value="bureau">Bureau</option>
                  <option value="simple">Membre</option>
                </select>
              </div>

              <!-- Goalkeeper -->
              <div class="relative">
                <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <select
                  v-model="filters.goalkeeper"
                  class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                >
                  <option value="">Tous</option>
                  <option value="yes">Gardien</option>
                  <option value="no">Non</option>
                </select>
              </div>

              <button
                @click="resetFilters"
                class="w-full h-10 font-semibold bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"
              >
                Réinitialiser
              </button>
            </div>
          </details>
        </div>

        <!-- Desktop layout with sidebar filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

          <!-- FILTER SIDEBAR - Desktop only (Admin only) -->
          <aside v-if="isAdmin" class="hidden md:block md:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-200 sticky top-6">

              <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-filter text-gray-500"></i> Filtres
              </h2>

              <!-- Search -->
              <div class="mb-5">
                <div class="relative">
                  <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Chercher..."
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 px-3 text-sm focus:ring-2 focus:ring-blue-300 focus:bg-white transition"
                  />
                </div>
              </div>

              <!-- Role -->
              <label class="flex flex-col mb-5">
                <span class="text-sm font-semibold text-gray-700 mb-2">Rôle</span>
                <div class="relative">
                  <i class="fas fa-user-tag absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <select
                    v-model="filters.role"
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                  >
                    <option value="">Tous les rôles</option>
                    <option value="admin">Admin</option>
                    <option value="bureau">Bureau</option>
                    <option value="simple">Membre</option>
                  </select>
                </div>
              </label>

              <!-- Goalkeeper -->
              <label class="flex flex-col mb-5">
                <span class="text-sm font-semibold text-gray-700 mb-2">Gardien</span>
                <div class="relative">
                  <i class="fas fa-shield-alt absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <select
                    v-model="filters.goalkeeper"
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg h-11 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                  >
                    <option value="">Tous</option>
                    <option value="yes">Oui</option>
                    <option value="no">Non</option>
                  </select>
                </div>
              </label>

              <!-- RESET BUTTON -->
              <button
                @click="resetFilters"
                class="w-full h-10 mt-4 font-semibold bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm"
              >
                Réinitialiser les filtres
              </button>

            </div>
          </aside>

          <!-- MEMBERS LIST -->
          <div :class="isAdmin ? 'md:col-span-3' : 'col-span-full'" class="space-y-3">
            <div class="text-sm text-gray-600 mb-4">
              {{ filteredMembers.length }} membre{{ filteredMembers.length !== 1 ? 's' : '' }} trouvé{{ filteredMembers.length !== 1 ? 's' : '' }}
            </div>

            <div v-if="filteredMembers.length > 0" class="space-y-3">
              <div
                v-for="member in filteredMembers"
                :key="member.id"
                class="flex items-center gap-4 bg-white p-4 sm:p-5 rounded-xl border border-gray-200 shadow-sm hover:shadow-md hover:border-blue-300 transition-all"
              >
                
                <!-- Avatar -->
                <div
                  class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-cover bg-center shadow-md flex-shrink-0"
                  :style="{ backgroundImage: `url(${member.avatar})` }"
                ></div>

                <!-- Info - Flex-grow -->
                <div class="flex-1 min-w-0">
                  <p class="text-base sm:text-lg font-bold text-gray-800 truncate">
                    {{ member.name }}
                    <span v-if="member.pseudo" class="text-gray-500 text-sm font-normal">({{ member.pseudo }})</span>
                  </p>
                  <p class="text-xs sm:text-sm text-gray-500">{{ member.email }}</p>
                  <p class="text-xs sm:text-sm text-gray-600 mt-1">
                    <i class="fas fa-tag mr-1"></i>{{ member.role }}
                  </p>
                </div>

                <!-- Tags - Responsive -->
                <div class="flex flex-col gap-2 flex-shrink-0">
                  <span v-if="member.is_goalkeeper" class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold whitespace-nowrap">
                    <i class="fas fa-shield-alt mr-1"></i>Gardien
                  </span>
                  <span v-if="member.position" class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold whitespace-nowrap capitalize">
                    {{ member.position }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Empty state -->
            <div v-else class="text-center py-12 bg-white rounded-xl border border-gray-200">
              <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
              <p class="text-gray-500 text-lg">Aucun membre ne correspond à vos filtres</p>
            </div>
          </div>

        </div>

      </div>
    </main>

  </div>
</template>


<script setup>
import { reactive, computed } from 'vue'
import AdminsideBar from "@/Components/AdminsideBar.vue";

const props = defineProps({
  team: Object,
  isAdmin: Boolean
})

// Filtres réactifs
const filters = reactive({
  search: '',
  role: '',
  goalkeeper: ''
})

// Filtrer les membres dynamiquement
const filteredMembers = computed(() => {
  return props.team.users.filter(m => {
    const matchesSearch = m.name.toLowerCase().includes(filters.search.toLowerCase()) || (m.email && m.email.toLowerCase().includes(filters.search.toLowerCase()))
    const matchesRole = !filters.role || m.role === filters.role
    const matchesGoalkeeper = !filters.goalkeeper || (filters.goalkeeper === 'yes' ? m.is_goalkeeper : !m.is_goalkeeper)
    return matchesSearch && matchesRole && matchesGoalkeeper
  })
})

// Réinitialiser les filtres
function resetFilters() {
  filters.search = ''
  filters.role = ''
  filters.goalkeeper = ''
}
</script>
