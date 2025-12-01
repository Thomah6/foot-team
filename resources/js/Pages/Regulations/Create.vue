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
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
      <div class="w-full max-w-3xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
        <h1 class="text-2xl font-bold mb-6 text-green-600 text-center">Ajouter un règlement</h1>

        <form @submit.prevent="submit" class="space-y-4">

          <!-- Titre -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre</label>
            <input v-model="form.title" type="text"
              class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-green-500" />
            <p v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</p>
          </div>

          <!-- Sous-points dynamiques -->
          <div v-for="(content, index) in form.contents" :key="index" class="border p-4 rounded space-y-2 bg-gray-50">
            <div class="flex justify-between items-center">
              <span class="font-semibold">Sous-point {{ index + 1 }}</span>
              <button type="button" class="text-red-500 hover:underline" @click="removeContent(index)">
                Supprimer
              </button>
            </div>

            <!-- Sous-numéro -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Sous-numéro</label>
              <input v-model="content.sub_number" type="text"
                class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-green-500" />
              <p v-if="form.errors[`contents.${index}.sub_number`]" class="text-red-500 text-sm mt-1">
                {{ form.errors[`contents.${index}.sub_number`] }}
              </p>
            </div>

            <!-- Contenu -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
              <textarea v-model="content.content" rows="4"
                class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-green-500 resize-none"></textarea>
              <p v-if="form.errors[`contents.${index}.content`]" class="text-red-500 text-sm mt-1">
                {{ form.errors[`contents.${index}.content`] }}
              </p>
            </div>

            <!-- Ordre -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Ordre (optionnel)</label>
              <input v-model="content.order" type="number"
                class="w-full rounded-lg border border-gray-300 p-2 focus:ring-2 focus:ring-green-500" />
              <p v-if="form.errors[`contents.${index}.order`]" class="text-red-500 text-sm mt-1">
                {{ form.errors[`contents.${index}.order`] }}
              </p>
            </div>
          </div>

          <!-- Ajouter un sous-point -->
          <div>
            <button type="button" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    @click="addContent">
              Ajouter un sous-point
            </button>
          </div>

          <!-- Bouton soumettre -->
          <button type="submit" class="w-full bg-green-500 text-white py-2 rounded-lg shadow-md">
            Ajouter le règlement
          </button>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
