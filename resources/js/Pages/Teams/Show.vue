<template>
    <div>
        <section> <AdminsideBar /> </section>
    </div>
  <main class="flex-1 p-8">
    <div class="max-w-7xl mx-auto">

      <!-- Page heading -->
      <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
        <h1 class="text-4xl font-black tracking-[-0.033em]">Club Members</h1>
        <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90">
          <span class="material-symbols-outlined">add</span>
          <span>Add Member</span>
        </button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">

        <!-- Sidebar filters -->
        <aside class="lg:col-span-1">
          <div class="bg-card-light dark:bg-card-dark p-6 rounded-xl shadow-sm border border-border-light dark:border-border-dark">

            <h2 class="text-lg font-bold mb-6">Filters</h2>

            <!-- Search -->
            <div class="mb-6">
              <input
                v-model="filters.search"
                type="text"
                placeholder="Search by name or email..."
                class="form-input w-full bg-transparent px-2 text-sm border border-border-light dark:border-border-dark rounded-lg h-12"
              />
            </div>

            <!-- Role -->
            <label class="flex flex-col mb-4">
              <span class="text-sm font-medium mb-2">Role</span>
              <select v-model="filters.role" class="form-select rounded-lg h-12 px-3 border border-border-light dark:border-border-dark bg-input-light dark:bg-input-dark">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="bureau">Bureau</option>
                <option value="simple">Simple Member</option>
              </select>
            </label>

            <!-- Goalkeeper -->
            <label class="flex flex-col mb-4">
              <span class="text-sm font-medium mb-2">Goalkeeper Status</span>
              <select v-model="filters.goalkeeper" class="form-select rounded-lg h-12 px-3 border border-border-light dark:border-border-dark bg-input-light dark:bg-input-dark">
                <option value="">Any</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </label>

            <!-- Buttons -->
            <div class="flex flex-col gap-3 mt-6">
              <button @click="resetFilters" class="w-full h-10 rounded-lg bg-border-light dark:bg-border-dark text-text-secondary-light dark:text-text-secondary-dark font-bold hover:bg-border-light/80 dark:hover:bg-border-dark/80">
                Reset
              </button>
            </div>
          </div>
        </aside>

        <!-- Members list -->
        <div class="lg:col-span-3 grid grid-cols-1 gap-4">
          <div
            v-for="member in filteredMembers"
            :key="member.id"
            class="flex items-center gap-4 bg-card-light dark:bg-card-dark p-4 rounded-xl shadow-sm border hover:shadow-md hover:border-primary/50 transition-all cursor-pointer"
          >
          <!-- {{ member }} -->
            <div class="bg-center bg-cover rounded-full w-12 h-12" :style="{ backgroundImage: `url(${member.avatar})` }"></div>
            <div class="flex-grow">
              <p class="font-bold text-base">{{ member.name }} <span class="">({{ member.pseudo }})</span></p>
              <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">{{ member.role }}</p>
            </div>
            <div class="flex-shrink-0">
              <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-accent-teal/10 text-accent-teal capitalize ">{{ member.position }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
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
