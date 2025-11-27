<script setup>
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
      <!-- Champ titre -->
      <div>
        <input v-model="form.title" type="text" placeholder="Titre" class="border p-2 w-full" />
        <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
      </div>

      <!-- Champ contenu -->
      <div>
        <textarea v-model="form.content" placeholder="Contenu" class="border p-2 w-full"></textarea>
        <span v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</span>
      </div>

      <!-- Champ parent_id -->
      <div>
        <input v-model="form.parent_id" type="number" placeholder="Parent ID" class="border p-2 w-full" />
        <span v-if="form.errors.parent_id" class="text-red-500 text-sm">{{ form.errors.parent_id }}</span>
      </div>

      <!-- Champ ordre -->
      <div>
        <input v-model="form.order" type="number" placeholder="Ordre" class="border p-2 w-full" />
        <span v-if="form.errors.order" class="text-red-500 text-sm">{{ form.errors.order }}</span>
      </div>

      <!-- Bouton -->
      <button class="bg-blue-500 text-black px-4 py-2 rounded" :disabled="form.processing">
          <span v-if="!form.processing">Enregistrer</span>
          <span v-else>Enregistrement...</span>
      </button>
    </form>
  </div>
</template>
