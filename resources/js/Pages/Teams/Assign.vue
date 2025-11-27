<script setup>
import draggable from 'vuedraggable'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  teams: Array,
  users: Array
})

function save() {
  router.post('/teams/assign-members', {
    members: props.users
  })
}
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div v-for="team in props.teams" class="bg-white p-4 rounded shadow">
      <h2 class="font-bold mb-2">{{ team.name }}</h2>

      <draggable v-model="team.members" group="members">
        <div
          v-for="u in team.members"
          class="p-2 bg-gray-100 rounded mb-2 cursor-move"
        >
          {{ u.name }}
        </div>
      </draggable>
    </div>
  </div>

  <button class="mt-6 bg-green-600 text-white px-4 py-2 rounded" @click="save">
    Enregistrer
  </button>
</template>
