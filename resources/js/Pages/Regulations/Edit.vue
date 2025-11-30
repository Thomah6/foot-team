<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  rule: {
    type: Object,
    required: true
  },
})

const form = useForm({
  title: props.rule.title || '',
  content: props.rule.content || '',
  parent_id: props.rule.parent_id || null,
  order: props.rule.order || 0,
})

function submit() {
  form.put(`/regulations/${props.rule.id}`, {
    onSuccess: () => {
      // Optionnel : toast ou redirection
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen flex items-center justify-center 
                bg-[url('https://st.depositphotos.com/1000423/1958/i/450/depositphotos_19580567-stock-photo-two-football-players-in-jump.jpg')] 
                bg-cover bg-center relative px-4">
      
      <!-- Overlay sombre + blur léger -->
      <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
  
      <!-- Formulaire centré avec blur réduit -->
      <div class="relative w-full max-w-2xl bg-white/80 backdrop-blur-[2px] rounded-xl shadow-lg p-6 sm:p-10 mx-4">
        <h1 class="text-2xl font-bold mb-4 text-green-600 text-center">Modifier un règlement</h1>
        <h2 class="text-lg font-bold mb-6 text-center text-gray-700">Titre actuel : {{ form.title }}</h2>
  
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Champ titre -->
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Titre</label>
            <input 
              v-model="form.title" 
              type="text" 
              class="w-full rounded-lg border border-gray-300 p-2 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200"
            />
            <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
          </div>
  
          <!-- Champ contenu -->
          <div>
            <label class="block text-sm font-medium text-gray-800 mb-2">Contenu</label>
            <textarea 
              v-model="form.content" 
              rows="5"
              class="w-full rounded-lg border border-gray-300 p-2 text-sm bg-white
                     focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition duration-200 resize-none"
            ></textarea>
            <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">{{ form.errors.content }}</div>
          </div>
  
          <!-- Boutons -->
          <div class="flex flex-col sm:flex-row gap-4">
            <!-- Mettre à jour -->
            <button 
              type="submit" 
              class="flex-1 bg-green-500 hover:bg-green-600 text-white font-semibold py-2 rounded-lg shadow-md
                     transition duration-200 ease-in-out disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="form.processing"
            >
              <span v-if="form.processing"> Sauvegarde...</span>
              <span v-else> Mettre à jour</span>
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

