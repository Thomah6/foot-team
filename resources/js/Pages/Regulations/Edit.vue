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

/** FORMULAIRE POUR LE TITRE DE LA RUBRIQUE **/
const titleForm = useForm({
  title: props.title
})

function updateTitle() {
  titleForm.put(`/regulations/${props.content.regulation_id}/title`, {
    preserveScroll: true,
  })
}

/** FORMULAIRE POUR LE CONTENU **/
const form = useForm({
  sub_number: props.content.sub_number || '',
  content: props.content.content || ''
})

function updateContent() {
  form.put(`/regulations/content/${props.content.id}`, {
    onSuccess: () => {
      $inertia.visit('/regulations')
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>

    <div 
      class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center"
      style="background-image: url('https://static.vecteezy.com/system/resources/thumbnails/046/408/525/small/football-soccer-grass-field-stadium-with-light-for-outdoor-sport-photo.jpg');"
    >
      <div class="w-full max-w-xl 
                  bg-blue-600/40 dark:bg-gray-900/70 
                  backdrop-blur-md 
                  border border-blue-300/40 dark:border-gray-700 
                  rounded-xl shadow-lg p-6 sm:p-8 
                  text-white dark:text-gray-100">

        <!-- TITRE DU RÈGLEMENT -->
        <!-- <h1 class="text-xl font-bold mb-4 text-center">Modifier la rubrique</h1>

        <form @submit.prevent="updateTitle" class="mb-8 space-y-2">
          <label class="block text-sm font-medium">Titre de la rubrique</label>
          <input 
            type="text"
            v-model="titleForm.title"
            class="w-full p-2 rounded-lg text-black dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600"
          />
          <div v-if="titleForm.errors.title" class="text-red-300 text-sm">
            {{ titleForm.errors.title }}
          </div>

          <button 
            type="submit"
            class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2 rounded-lg"
            :disabled="titleForm.processing"
          >
            <span v-if="titleForm.processing">Mise à jour...</span>
            <span v-else>Modifier le titre</span>
          </button>
        </form> -->

        <!-- CONTENU DU SOUS-POINT -->
        <h1 class="text-xl font-bold mb-4 text-center">Modifier le sous-point</h1>

        <form @submit.prevent="updateContent" class="space-y-4">

          <!-- Numéro du sous-point -->
          <div>
            <label class="block text-sm font-medium mb-1">Numéro du sous-point</label>
            <input 
              type="text" 
              v-model="form.sub_number" 
              placeholder="Ex: 1.1"
              class="w-full p-2 rounded-lg text-black dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600"
            />
            <div v-if="form.errors.sub_number" class="text-red-300 text-sm mt-1">
              {{ form.errors.sub_number }}
            </div>
          </div>

          <!-- Contenu -->
          <div>
            <label class="block text-sm font-medium mb-1">Contenu</label>
            <textarea 
              v-model="form.content" 
              rows="5"
              class="w-full p-2 rounded-lg resize-none text-black dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600"
            />
            <div v-if="form.errors.content" class="text-red-300 text-sm mt-1">
              {{ form.errors.content }}
            </div>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button 
              type="submit" 
              class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg"
              :disabled="form.processing"
            >
              <span v-if="form.processing">Sauvegarde...</span>
              <span v-else>Mettre à jour</span>
            </button>

            <button 
              type="button" 
              @click="$inertia.visit('/regulations')"
              class="flex-1 bg-gray-700 hover:bg-gray-800 text-white py-2 rounded-lg"
            >
              Annuler
            </button>
          </div>

        </form>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
