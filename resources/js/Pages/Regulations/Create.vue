<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

// Formulaire initial
const form = useForm({
    title: '',
    contents: [
        { sub_number: '', content: '', order: null }
    ]
})

// Ajouter un sous-point
function addContent() {
    form.contents.push({ sub_number: '', content: '', order: null })
}

// Supprimer un sous-point
function removeContent(index) {
    form.contents.splice(index, 1)
}

// Soumettre le formulaire
function submit() {
    form.post('/regulations/fusion', {
        onSuccess: () => $inertia.visit('/regulations')
    })
}
</script>

<template>
  <AuthenticatedLayout>
    <div 
      class="relative min-h-screen flex items-center justify-center px-4 bg-cover bg-center"
      style="background-image: url('https://media.istockphoto.com/id/1662909784/fr/photo/ar%C3%A8ne-de-football-am%C3%A9ricain-avec-poteau-de-but-jaune-terrain-en-herbe-et-fans-flous-%C3%A0-la-vue.jpg?s=612x612&w=0&k=20&c=E3i9ztZldA3O4mmyoKaGAc8uxWWrJADfh_LnfNayMFc=');"
      v-if="$page.props.auth.user.role === 'admin'"
    >
      <!-- Overlay translucide avec blur -->
      <div class="absolute inset-0 bg-white/40 dark:bg-black/40 backdrop-blur-sm"></div>

      <!-- Bloc avec effet glassmorphism -->
      <div class="relative w-full max-w-3xl 
                  bg-white/80 dark:bg-gray-900/80 
                  border border-white/40 dark:border-gray-700 
                  rounded-xl shadow-lg p-6 sm:p-8">
        <h1 class="text-2xl font-bold mb-6 text-black dark:text-white text-center">
          Ajouter un règlement
        </h1>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Titre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Titre</label>
            <input v-model="form.title" type="text"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                     p-2 focus:ring-2 focus:ring-cyan-500 
                     bg-white dark:bg-gray-800 
                     text-black dark:text-white" />
            <p v-if="form.errors.title" class="text-red-500 dark:text-red-400 text-sm mt-1">{{ form.errors.title }}</p>
          </div>

          <!-- Sous-points dynamiques -->
          <div v-for="(content, index) in form.contents" :key="index" 
               class="border p-4 rounded space-y-2 
                      bg-white/50 dark:bg-gray-800/50 
                      backdrop-blur-sm">
            <div class="flex justify-between items-center">
              <span class="font-semibold dark:text-gray-200">Sous-point {{ index + 1 }}</span>
              <button type="button" class="text-red-500 dark:text-red-400 hover:underline" @click="removeContent(index)">
                Supprimer
              </button>
            </div>

            <!-- Sous-numéro -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sous-numéro</label>
              <input v-model="content.sub_number" type="text"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       p-2 focus:ring-2 focus:ring-cyan-500 
                       bg-white dark:bg-gray-800 
                       text-black dark:text-white" />
            </div>

            <!-- Contenu -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contenu</label>
              <textarea v-model="content.content" rows="4"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       p-2 focus:ring-2 focus:ring-cyan-500 resize-none 
                       bg-white dark:bg-gray-800 
                       text-black dark:text-white"></textarea>
            </div>

            <!-- Ordre -->
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordre (optionnel)</label>
              <input v-model="content.order" type="number"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       p-2 focus:ring-2 focus:ring-cyan-500 
                       bg-white dark:bg-gray-800 
                       text-black dark:text-white" />
            </div>
          </div>

          <!-- Ajouter un sous-point -->
          <div>
            <button type="button" 
                    class="w-full bg-cyan-600 dark:bg-cyan-500 text-white px-4 py-2 rounded hover:bg-cyan-700 dark:hover:bg-cyan-600"
                    @click="addContent">
              Ajouter un sous-point
            </button>
          </div>

          <div class="flex justify-between gap-5 w-full">
            <button type="submit" 
                    class="bg-cyan-600 dark:bg-cyan-500 text-white py-2 px-5 rounded-lg shadow-md hover:bg-cyan-700 dark:hover:bg-cyan-600">
              Ajouter le règlement
            </button>
            <button type="button" 
                    class="bg-gray-700 dark:bg-gray-600 text-white py-2 px-5 rounded-lg shadow-md hover:bg-gray-800 dark:hover:bg-gray-700"
                    @click="$inertia.visit('/regulations')">
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
