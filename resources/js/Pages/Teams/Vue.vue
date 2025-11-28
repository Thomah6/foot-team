<script setup>
import { ref } from "vue"
import { router, useForm } from "@inertiajs/vue3"

// Props venant du backend
const props = defineProps({
  teams: Array,
  search: String,
  pagination: Object
})

// STATE
const search = ref(props.search || " ")

// Recherche
function searchTeams() {
  router.get("/teams", { search: search.value }, { preserveState: true })
}

// Formulaire création dans modal
const createForm = useForm({
  name: "",
  description: ""
})

function createTeam() {
  createForm.post("/teams", {
    onSuccess: () => {
      createForm.reset()
      showCreateModal.value = false
    }
  })
}

// Modal control
const showCreateModal = ref(false)
</script>

<template>
  <div class="p-8">

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold text-gray-800">Teams</h1>

      <div class="flex gap-3">
        <a href="teams/create">
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
        @keyup.enter="searchTeams"
        class="w-full max-w-xl px-5 py-3 rounded-full border shadow-sm focus:ring-2 focus:ring-blue-400"
        type="text"
        placeholder="Search by team name..."
      />
    </div>

    <!-- Teams grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <div
        v-for="team in props.teams"
        :key="team.id"
        class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition"
      >
        <h2 class="text-xl font-semibold text-gray-800 mb-2">
          {{ team.name }}
        </h2>

        <p class="flex items-center text-gray-600 mb-6">
          👥 {{ team.members?.length || 0 }} Players
        </p>

        <button @click="showTeam"
          class="w-full text-center bg-blue-50 text-blue-700 py-2 rounded-lg hover:bg-blue-100 transition"
        >
          View Details →
        </button>
      </div>
    </div>

    <!-- Pagination -->
    <!-- <div class="flex justify-center items-center gap-3 mt-10">
      <button class="px-3 py-1 text-gray-500">&lt;</button>

      <div class="flex gap-2">
        <span
          class="px-4 py-2 rounded-full bg-blue-600 text-white"
        >
          1
        </span>

        <span class="px-4 py-2 text-gray-600">2</span>
        <span class="px-4 py-2 text-gray-500">3</span>
        <span class="px-4 py-2">...</span>
        <span class="px-4 py-2">10</span>
      </div>

      <button class="px-3 py-1 text-gray-500">&gt;</button>
    </div> -->

    <!-- Create Modal -->
    <!-- <div
      v-if="showCreateModal"
      class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center"
    >
      <div class="bg-white p-6 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add New Team</h2>

        <form @submit.prevent="createTeam" class="space-y-4">
          <input
            v-model="createForm.name"
            class="w-full border p-3 rounded"
            placeholder="Team name"
          />

          <textarea
            v-model="createForm.description"
            class="w-full border p-3 rounded"
            placeholder="Description"
          ></textarea>

          <div class="flex justify-end gap-3">
            <button
              type="button"
              class="px-4 py-2 rounded bg-gray-200"
              @click="showCreateModal = false"
            >
              Cancel
            </button>

            <button
              class="px-4 py-2 rounded bg-blue-600 text-white"
              :disabled="createForm.processing"
            >
              {{ createForm.processing ? "Saving..." : "Save" }}
            </button>
          </div>
        </form>
      </div>
    </div> -->

  </div>
</template>
