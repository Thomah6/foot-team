<script setup>
import { computed, ref } from "vue"
import { router, useForm } from "@inertiajs/vue3"
import AdminsideBar from "@/Components/AdminsideBar.vue";
// Props venant du backend
const props = defineProps({
  teams: Array,
})

const search = ref("");

const filteredTeams = computed(()=>{
    if(!search.value) return props.teams;

    return props.teams.filter(team => team.name.toLowerCase().includes(search.value.toLocaleLowerCase()))
})

</script>

<template>
  <article class="flex justify-between bg-gray-50 min-h-screen">

    <!-- Sidebar -->
    <section>
      <AdminsideBar />
    </section>

    <!-- PAGE CONTENT -->
    <section class="flex-1 p-10 ml-4">

      <!-- HEADER -->
      <div class="flex justify-between items-center mb-10 ml-4">
        <h1 class="ml-4 text-4xl font-extrabold text-gray-800 tracking-tight flex items-center gap-3">
          <i class="fas fa-people-group text-blue-600"></i>
          Teams Management
        </h1>

        <div class="flex gap-3">
          <!-- Add -->
          <a href="/teams/index">
            <button
              class="flex items-center gap-3 bg-blue-600 text-white px-5 py-3 rounded-xl shadow-md hover:bg-blue-700 transition font-semibold"
            >
              <i class="fas fa-plus-circle text-lg"></i>
              Add New Team
            </button>
          </a>

          <!-- Filter -->
          <!-- <button
            class="flex items-center gap-3 px-5 py-3 rounded-xl border border-gray-300 shadow-sm hover:bg-gray-100 transition font-semibold"
          >
            <i class="fas fa-filter text-gray-600"></i>
            Filter
          </button> -->
        </div>
      </div>

      <!-- SEARCH BAR -->
      <div class="flex justify-center mb-12">
        <div class="relative w-full max-w-2xl">
          <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
          <input
            v-model="search"
            class="w-full pl-12 pr-4 py-3 rounded-full border shadow-sm focus:ring-2 focus:ring-blue-400 transition"
            type="text"
            placeholder="Search by team name..."
          />
        </div>
      </div>

      <!-- TEAMS GRID -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

        <div
          v-for="team in filteredTeams"
          :key="team.id"
          class="bg-white p-6 rounded-2xl shadow group hover:shadow-xl transition overflow-hidden border border-gray-100"
        >

          <!-- Team Name -->
          <h2 class="text-2xl font-bold text-gray-900 mb-3">
            {{ team.name }}
          </h2>

          <!-- Players Count -->
          <p class="flex items-center text-gray-600 mb-6 text-sm font-medium">
            <i class="fas fa-users text-blue-500 mr-2"></i>
            {{ team.users?.length || 0 }} Players
          </p>

          <!-- Button -->
          <button
            @click="router.get(`/teams/${team.id}`)"
            class="w-full flex items-center justify-center gap-2 bg-blue-50 text-blue-700 py-2.5 rounded-lg hover:bg-blue-100 transition font-medium"
          >
            <i class="fas fa-eye"></i>
            View Details
          </button>

        </div>

      </div>

    </section>
  </article>
</template>
