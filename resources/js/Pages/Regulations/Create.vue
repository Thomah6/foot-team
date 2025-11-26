<script setup>
import { reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  title: '',
  content: '',
  parent_id: null,
  order: 0,
})

function submit() {
  form.post('/regulations')
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Créer un règlement</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <input v-model="form.title" type="text" placeholder="Titre" class="border p-2 w-full" />
      <textarea v-model="form.content" placeholder="Contenu" class="border p-2 w-full"></textarea>
      <input v-model="form.order" type="number" placeholder="Ordre" class="border p-2 w-full" />

      <button class="bg-blue-500 text-white px-4 py-2 rounded">Enregistrer</button>
    </form>
  </div>
</template>
