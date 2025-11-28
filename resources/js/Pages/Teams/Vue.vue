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
 <article class="flex justify-between">
    <section> <AdminsideBar /> </section>

    <section>
         <div class="p-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Teams</h1>

      <div class="flex gap-3">
        <a href="/teams/index">
<button
          class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700"
        >
          ➕ Add New Team
        </button>

        </a>

        <button
          class="flex items-center gap-2 border px-4 py-2 rounded-lg shadow-sm hover:bg-gray-100"
        >
          ⚙ Filter
        </button>
      </div>
    </div>

    <!-- Search -->
    <div class="flex justify-center mb-10">
      <input
        v-model="search"
        class="w-full max-w-xl px-5 py-3 rounded-full border shadow-sm focus:ring-2 focus:ring-blue-400"
        type="text"
        placeholder="Search by team name..."
      />
    </div>

      <!-- Teams grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <div
        v-for="team in filteredTeams"
        :key="team.id"
        class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition"
      >
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
          {{ team.name }}
        </h2>

        <p class="flex items-center text-gray-600 mb-6">
          👥 {{ team.users?.length || 0 }} Players
        </p>

        <button @click="router.get(`/teams/${team.id}`)"
          class="w-full text-center bg-blue-50 text-blue-700 py-2 rounded-lg hover:bg-blue-100 transition"
        >
          View Details →
        </button>
      </div>
    </div>


  </div>
    </section>
 </article>
</template>
