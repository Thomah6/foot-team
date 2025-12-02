<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";
import { ref } from "vue";

const imagePreview = ref(null);

const form = useForm({
  title: "",
  content: "",
  is_published: false,
  image: null
});

const handleFile = (e) => {
  const file = e.target.files[0];
  form.image = file;

  if (file) {
    imagePreview.value = URL.createObjectURL(file);
  } else {
    imagePreview.value = null;
  }
};
</script>

<template>
<<<<<<< HEAD
  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
        Créer une actualité
      </h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">
        Rédigez une nouvelle actualité pour votre club
      </p>
    </div>

    <!-- Form -->
    <form 
      @submit.prevent="form.post('/admin/news', { forceFormData: true })" 
      class="space-y-6"
    >

      <NewsForm 
        :form="form"
        :errors="form.errors"
        :processing="form.processing"
      />

      <!-- Upload Image -->
      <div class="space-y-3">
        <label class="font-medium text-gray-700 dark:text-gray-300">
          Image de l'actualité
        </label>

        <input
          type="file"
          accept="image/*"
          @change="handleFile"
          class="block w-full text-sm text-gray-700 bg-white dark:bg-gray-700 
                 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer
                 file:bg-citron-500 file:hover:bg-citron-600 file:text-white file:border-none 
                 file:px-4 file:py-3 file:rounded-lg file:font-medium
                 hover:file:bg-citron-600 transition-all"
        />

        <!-- Preview -->
        <div 
          v-if="imagePreview" 
          class="mt-3 w-full rounded-lg overflow-hidden shadow-md border-2 border-gray-200 dark:border-gray-700"
        >
          <img :src="imagePreview" class="w-full h-64 object-cover" alt="Aperçu de l'image" />
        </div>
        
        <p class="text-sm text-gray-500 dark:text-gray-400">
          Formats acceptés : JPG, PNG, GIF. Taille max : 5MB.
        </p>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
        <button
          type="submit"
          class="px-6 py-3 bg-citron-500 hover:bg-citron-600 text-white font-medium 
                 rounded-lg shadow-sm transition-all duration-200 disabled:opacity-60
                 flex items-center gap-2"
          :disabled="form.processing"
        >
          <span v-if="form.processing" class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
          <span>{{ form.processing ? 'Création en cours...' : 'Créer l\'actualité' }}</span>
        </button>
      </div>
=======
  <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900 p-4">
    <div class="flex-1 flex justify-center items-start">
      <div class="w-full max-w-3xl bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col flex-1">

        <!-- Header -->
        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
          Créer une actualité
        </h1>

        <!-- Formulaire -->
        <form
          @submit.prevent="form.post('/admin/news', { forceFormData: true })"
          class="space-y-6 flex flex-col flex-1 overflow-auto"
        >
          <NewsForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
          />

          <!-- Submit -->
          <div class="flex justify-end mt-auto">
            <button
              type="submit"
              class="px-6 py-2.5 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-lg shadow transition disabled:opacity-60"
              :disabled="form.processing"
            >
              Enregistrer
            </button>
          </div>
>>>>>>> bc0c59620896928c4b8142a64de53d68a350f302

        </form>
      </div>
    </div>
  </div>
</template>