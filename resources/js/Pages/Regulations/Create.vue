<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  titles: Array,
})

const form = useForm({
  title: '',
  content: '',
})

function submit() {
  form.post('/regulations/content')
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen flex items-center justify-center 
                bg-[url('https://st3.depositphotos.com/1000423/17796/i/450/depositphotos_177968548-stock-photo-soccer-best-moments-mixed-media.jpg')] 
                bg-cover bg-center relative">
      <!-- Overlay avec blur -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
  
      <!-- Formulaire centré -->
      <div class="relative w-full max-w-xl bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6 sm:p-8 mx-4">
        <h1 class="text-2xl font-bold mb-6 text-green-600 text-center">Ajouter un contenu règlementaire</h1>
  
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Select titre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Titre du règlement</label>
            <select
              v-model="form.title"
              class="w-full rounded-lg border border-gray-300 p-2 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
            >
              <option value="">-- Choisir un titre --</option>
              <option v-for="t in props.titles" :key="t.id" :value="t.title">
                {{ t.title }}
              </option>
            </select>
          </div>
  
          <!-- Champ contenu -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Contenu du règlement</label>
            <textarea
              v-model="form.content"
              placeholder="Contenu du règlement"
              rows="5"
              class="w-full rounded-lg border border-gray-300 p-2 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none"
            ></textarea>
          </div>
  
          <!-- Boutons -->
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Ajouter -->
            <button
              type="submit"
              class="flex-1 bg-green-500 hover:bg-green-600 text-black font-semibold py-2 rounded-lg shadow-md
                     transition duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="form.processing"
            >
              <span v-if="!form.processing">Ajouter le contenu</span>
              <span v-else>Enregistrement...</span>
            </button>
  
            <!-- Annuler -->
            <button
              type="button"
              class="flex-1 bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded-lg shadow-md
                     transition duration-200 ease-in-out"
              @click="$inertia.visit('/regulations')"
            >
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
