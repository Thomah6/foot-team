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
      class="relative min-h-screen flex items-center justify-center px-4 bg-cover bg-center transition-colors duration-300"
      style="background-image: url('https://media.istockphoto.com/id/1662909784/fr/photo/ar%C3%A8ne-de-football-am%C3%A9ricain-avec-poteau-de-but-jaune-terrain-en-herbe-et-fans-flous-%C3%A0-la-vue.jpg?s=612x612&w=0&k=20&c=E3i9ztZldA3O4mmyoKaGAc8uxWWrJADfh_LnfNayMFc=');"
      v-if="$page.props.auth.user.role === 'admin'"
    >
      <div class="absolute inset-0 bg-white/40 dark:bg-black/60 backdrop-blur-sm"></div>

      <div class="relative w-full max-w-3xl 
                  bg-white/90 dark:bg-gray-800/90 
                  border border-gray-200 dark:border-gray-700 
                  rounded-2xl shadow-2xl p-6 sm:p-8 transition-colors duration-300">
        <div class="mb-8 text-center">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
              Ajouter un règlement
          </h1>
          <p class="text-gray-600 dark:text-gray-400 text-sm">Créez un nouveau règlement avec ses sous-points</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
          <div>
            <label class="bloc text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
              <i class="fas fa-heading text-citron-500"></i>
              Titre du règlement
            </label>
            <input v-model="form.title" type="text" placeholder="Ex: Règlement intérieur"
              class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                     p-3 focus:ring-2 focus:ring-citron-500 focus:border-transparent
                     bg-white dark:bg-gray-700 
                     text-gray-900 dark:text-white transition duration-300" />
            <p v-if="form.errors.title" class="text-red-500 dark:text-red-400 text-sm mt-2">{{ form.errors.title }}</p>
          </div>

          <div class="space-y-4">
            <div class="flex justify-between items-center">
              <label class="bloc text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center gap-2">
                <i class="fas fa-list-ol text-citron-500"></i>
                Sous-points
              </label>
              <span class="text-xs text-gray-500 dark:text-gray-400">{{ form.contents.length }} sous-point(s)</span>
            </div>
            
            <div v-for="(content, index) in form.contents" :key="index" 
                 class="border border-gray-300 dark:border-gray-700 p-4 sm:p-5 rounded-xl space-y-4 
                         bg-white/50 dark:bg-gray-800/70 
                         backdrop-blur-sm hover:border-citron-500 dark:hover:border-citron-400 transition-all duration-300">
              <div class="flex justify-between items-center">
                <span class="font-semibold text-gray-800 dark:text-white"> Sous-point {{ index + 1 }}</span>
                <button type="button" 
                        class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:underline text-sm flex items-center gap-1 transition"
                        @click="removeContent(index)"
                        v-if="form.contents.length > 1">
                  <i class="fas fa-times"></i>
                  Supprimer
                </button>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Numéro</label>
                <input v-model="content.sub_number" type="text" placeholder="Ex: 1.1, 2.3, etc."
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                          p-2 focus:ring-2 focus:ring-citron-500 focus:border-transparent
                          bg-white dark:bg-gray-700 
                          text-gray-900 dark:text-white transition duration-300" />
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contenu</label>
                <textarea v-model="content.content" rows="3" placeholder="Contenu du sous-point..."
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                          p-2 focus:ring-2 focus:ring-citron-500 focus:border-transparent resize-none
                          bg-white dark:bg-gray-700 
                          text-gray-900 dark:text-white transition duration-300"></textarea>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Ordre d'affichage (optionnel)</label>
                <input v-model="content.order" type="number" placeholder="Position dans la liste"
                  class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                          p-2 focus:ring-2 focus:ring-citron-500 focus:border-transparent
                          bg-white dark:bg-gray-700 
                          text-gray-900 dark:text-white transition duration-300" />
              </div>
            </div>
          </div>

          <div>
            <button type="button" 
                    class="w-full bg-citron-50 hover:bg-citron-100 dark:bg-citron-900/50 dark:hover:bg-citron-900/70 
                           text-citron-700 dark:text-citron-300 px-4 py-3 rounded-lg border-2 border-dashed border-citron-300 dark:border-citron-700
                           transition font-semibold flex items-center justify-center gap-2 duration-300"
                    @click="addContent">
              <i class="fas fa-plus"></i>
              Ajouter un sous-point
            </button>
          </div>

          <div class="flex flex-col sm:flex-row justify-between gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button type="submit" 
                    class="flex items-center justify-center gap-2 py-3 px-6 rounded-lg 
                           bg-gradient-to-r from-emerald-600 to-lime-600 
                           hover:from-emerald-700 hover:to-lime-700 
                           text-white font-bold text-sm shadow-lg hover:shadow-emerald-500/30 
                           transition-all duration-300"
                    :disabled="form.processing">
              <i class="fas fa-save"></i>
              <span v-if="form.processing">Création en cours...</span>
              <span v-else>Créer le règlement</span>
            </button>

            <button type="button" 
                    class="flex items-center justify-center gap-2 py-3 px-6 rounded-lg 
                           bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 
                           text-gray-800 dark:text-gray-200 font-semibold shadow transition-colors duration-300"
                    @click="$inertia.visit('/regulations')">
              <i class="fas fa-times"></i>
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>