<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  content: {
    type: Object,
    required: true
  },
  title: {
    type: String,
    required: true
  }
})


// Formulaire
const form = useForm({
  sub_number: props.content.sub_number,
  content: props.content.content,
})

// Mise à jour du contenu
function submit() {
  form.put(`/regulations/content/${props.content.id}`, {
    onSuccess: () => {
      window.location.href = '/regulations'
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div 
      class="min-h-screen flex items-center justify-center px-4
             bg-cover bg-center"
      style="background-image: url('https://static.vecteezy.com/system/resources/thumbnails/046/408/525/small/football-soccer-grass-field-stadium-with-light-for-outdoor-sport-photo.jpg');"
    >

      <!-- Carte floue -->
      <div class="w-full max-w-xl 
                  bg-blue-600/40 dark:bg-gray-900/70
                  backdrop-blur-md 
                  border border-blue-300/40 dark:border-gray-700
                  rounded-xl shadow-lg
                  p-6 sm:p-8
                  text-white dark:text-gray-100">

        <!-- Titre de la rubrique -->
        <h2 class="text-xl font-semibold mb-1 text-center">
          {{ props.title }}
        </h2>

        <h1 class="text-2xl font-bold mb-6 text-center">
          Modifier un sous-point
        </h1>

        <form @submit.prevent="submit" class="space-y-4">

          <!-- Numéro du sous-point -->
          <div>
            <label class="block text-sm font-medium mb-1">Numéro du sous-point</label>
            <input type="text" v-model="form.sub_number"
                   class="w-full border rounded-lg p-2
                          text-black dark:text-white
                          bg-white dark:bg-gray-800
                          border-gray-300 dark:border-gray-600" />

            <p v-if="form.errors.sub_number" class="text-red-300 text-sm mt-1">
              {{ form.errors.sub_number }}
            </p>
          </div>

          <!-- Contenu -->
          <div>
            <label class="block text-sm font-medium mb-1">Contenu</label>
            <textarea v-model="form.content" rows="5"
                      class="w-full border rounded-lg p-2 resize-none
                             text-black dark:text-white
                             bg-white dark:bg-gray-800
                             border-gray-300 dark:border-gray-600"></textarea>

            <p v-if="form.errors.content" class="text-red-300 text-sm mt-1">
              {{ form.errors.content }}
            </p>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button type="submit"
                    class="flex-1 bg-blue-600 dark:bg-cyan-500 
                           hover:bg-blue-700 dark:hover:bg-cyan-600
                           text-white py-2 rounded-lg"
                    :disabled="form.processing">
              <span v-if="form.processing">Sauvegarde...</span>
              <span v-else>Mettre à jour</span>
            </button>

            <button type="button"
                    class="flex-1 bg-gray-700 dark:bg-gray-600 
                           hover:bg-gray-800 dark:hover:bg-gray-700
                           text-white py-2 rounded-lg"
                    @click="$inertia.visit('/regulations')">
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
