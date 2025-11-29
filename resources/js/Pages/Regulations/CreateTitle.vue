<script setup>
// import { useForm } from '@inertiajs/vue3'

// const form = useForm({
//   title: '',
// })

// function submit() {
//   form.post('/regulations/title')
// }
</script>

<template>
  <!-- <div class="p-6 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-green-600">Créer un titre</h1>

    <form @submit.prevent="submit" class="space-y-4 bg-white shadow-md rounded-lg p-6">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
        <input 
          v-model="form.title" 
          type="text" 
          placeholder="Titre du règlement" 
          class="border border-gray-300 rounded-lg p-2 w-full focus:ring-2 focus:ring-green-500 focus:border-green-500"
        />
        <span v-if="form.errors.title" class="text-red-500 text-sm">{{ form.errors.title }}</span>
      </div>

      <button 
        class="bg-green-500 hover:bg-green-600 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition duration-200 ease-in-out"
        :disabled="form.processing"
      >
        <span v-if="!form.processing"> Enregistrer le titre</span>
        <span v-else> Enregistrement...</span>
      </button>
    </form>
  </div> -->
</template>
