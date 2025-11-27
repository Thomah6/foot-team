<script setup>
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  titles: Array, // Liste des titres envoyée par le contrôleur
})

const form = useForm({
  title: '',   // titre choisi dans le select
  content: '', // contenu du règlement
})

function submit() {
  form.post('/regulations/content')
}
</script>

<template>
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-green-600">Ajouter un contenu</h1>

    <form @submit.prevent="submit" class="space-y-4 bg-black shadow-md rounded-lg p-6">
      <!-- Select titre -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
        <select 
          v-model="form.title" 
          class="border border-gray-300 rounded-lg p-2 w-full focus:ring-2 focus:ring-green-500 focus:border-green-500"
        >
          <option value="">-- Choisir un titre --</option>
          <option v-for="t in props.titles" :key="t.id" :value="t.title">
            {{ t.title }}
          </option>
        </select>
        <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
      </div>

      <!-- Champ contenu -->
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
        <textarea 
          v-model="form.content" 
          placeholder="Contenu du règlement" 
          rows="5"
          class="border border-gray-300 rounded-lg p-2 w-full focus:ring-2 focus:ring-green-500 focus:border-green-500"
        ></textarea>
        <span v-if="form.errors.content" class="text-red-500 text-sm">{{ form.errors.content }}</span>
      </div>

      <!-- Bouton -->
      <button 
        class="bg-green-500 hover:bg-green-600 text-black font-semibold px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out"
        :disabled="form.processing"
      >
        <span v-if="!form.processing"> Ajouter le contenu</span>
        <span v-else> Enregistrement...</span>
      </button>
    </form>
  </div>
</template>
