<template>
  <div class="flex">

    <!-- Sidebar -->
    <section class="hidden md:block">
      <AdminsideBar />
    </section>

    <!-- Main content -->
    <main class="flex-1 p-6 md:p-10 bg-gray-50 min-h-screen">
      <div class="max-w-7xl mx-auto ml-4">

        <!-- TOP BAR -->
        <div class="flex flex-wrap justify-between items-center ml-4 mb-10 ml-4 gap-4">
          <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">
            Club Members
          </h1>

          <button class="flex items-center gap-2 bg-blue-600 text-white px-5 py-3 rounded-xl shadow-lg hover:bg-blue-700 hover:shadow-xl transition-all duration-200">
            <i class="fas fa-user-plus text-lg"></i>
            <span class="font-semibold">Add Member</span>
          </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

          <!-- FILTER SIDEBAR -->
          <aside class="lg:col-span-1">
            <div class="bg-white p-6 rounded-2xl shadow-xl border border-gray-200">

              <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-filter text-gray-500 "></i> Filters
              </h2>

              <!-- Search -->
              <div class="mb-6">
                <div class="relative">
                  <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <input
                    v-model="filters.search"
                    type="text"
                    placeholder="Search member..."
                    class="w-full bg-gray-100 border border-gray-300 rounded-xl h-12 pl-10 px-3 text-sm focus:ring-2 focus:ring-blue-300 focus:bg-white transition"
                  />
                </div>
              </div>

              <!-- Role -->
              <label class="flex flex-col mb-5">
                <span class="text-sm font-semibold text-gray-700 mb-2">Role</span>
                <div class="relative">
                  <i class="fas fa-user-tag  absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <select
                    v-model="filters.role"
                    class="w-full bg-gray-100 border border-gray-300 rounded-xl h-12 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                  >
                    <option value="">All Roles</option>
                    <option value="admin">Admin</option>
                    <option value="bureau">Bureau</option>
                    <option value="simple">Simple Member</option>
                  </select>
                </div>
              </label>

              <!-- Goalkeeper -->
              <label class="flex flex-col mb-5">
                <span class="text-sm font-semibold text-gray-700 mb-2">Goalkeeper</span>
                <div class="relative">
                  <i class="fas fa-shield-alt absolute  left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                  <select
                    v-model="filters.goalkeeper"
                    class="w-full bg-gray-100 border border-gray-300 rounded-xl h-12 pl-10 pr-3 text-sm focus:ring-2 focus:ring-blue-300"
                  >
                    <option value="">Any</option>
                    <option value="yes">Yes</option>
                    <option value="no">No</option>
                  </select>
                </div>
              </label>

              <!-- RESET BUTTON -->
              <button
                @click="resetFilters"
                class="w-full h-11 mt-4 font-semibold bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition"
              >
                Reset Filters
              </button>

            </div>
          </aside>
          <!-- MEMBERS LIST -->
          <div class="lg:col-span-3 grid grid-cols-1 gap-5">
              <div
              v-for="member in filteredMembers"
              :key="member.id"
              class="flex items-center gap-5 bg-white p-5 rounded-2xl border border-gray-200 shadow-md hover:shadow-xl hover:border-blue-400 transition-all cursor-pointer"
              >
              
              <!-- Avatar -->
              <div
                class="w-14 h-14 rounded-full bg-cover bg-center shadow-md"
                :style="{ backgroundImage: `url(${member.avatar})` }"
              ></div>

              <!-- Info -->
              <div class="flex-1">
                <p class="text-lg font-bold text-gray-800">
                  {{ member.name }}
                  <span class="text-gray-500">({{ member.pseudo }})</span>
                </p>
                <p class="text-sm text-gray-500">{{ member.role }}</p>
              </div>

              <!-- Position tag -->
              <span class="px-4 py-2 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold capitalize">
                {{ member.position }}
              </span>
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
  team: Array,       // toutes les équipes
})

// Filtres réactifs
const filters = reactive({
  search: '',
  role: '',
  team: '',
  goalkeeper: ''
})

// Filtrer les membres dynamiquement
const filteredMembers = computed(() => {
  return props.team.users.filter(m => {
    const matchesSearch = m.name.toLowerCase().includes(filters.search.toLowerCase()) || (m.email && m.email.toLowerCase().includes(filters.search.toLowerCase()))
    const matchesRole = !filters.role || m.role === filters.role
    const matchesTeam = !filters.team || m.team === filters.team
    const matchesGoalkeeper = !filters.goalkeeper || (filters.goalkeeper === 'yes' ? m.is_goalkeeper : !m.is_goalkeeper)
    return matchesSearch && matchesRole && matchesTeam && matchesGoalkeeper
  })
})

// Réinitialiser les filtres
function resetFilters() {
  filters.search = ''
  filters.role = ''
  filters.team = ''
  filters.goalkeeper = ''
}
</script>
