<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";
import { ref } from "vue";

const imagePreview = ref(null);

const form = useForm({
  title: "",
  content: "",
  is_published: false,
  image: null        // <── AJOUT
});

// Gestion du fichier et du preview
const handleFile = (e) => {
  const file = e.target.files[0];
  form.image = file;

  if (file) {
    imagePreview.value = URL.createObjectURL(file);
  }
};
</script>

<template>
  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">

    <!-- Header -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
      Créer une actualité
    </h1>

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
      <div class="space-y-2">
        <label class="font-semibold text-gray-700 dark:text-gray-300">
          Image de l’actualité
        </label>

        <input
          type="file"
          accept="image/*"
          @change="handleFile"
          class="block w-full text-sm text-gray-700 bg-white dark:bg-gray-800 
                 border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer
                 file:bg-green-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded-lg 
                 hover:file:bg-green-700 transition"
        />

        <!-- Preview -->
        <div 
          v-if="imagePreview" 
          class="mt-3 w-full rounded-lg overflow-hidden shadow-md border border-gray-300 dark:border-gray-600"
        >
          <img :src="imagePreview" class="w-full h-64 object-cover" />
        </div>
      </div>

      <!-- Submit Button -->
      <div class="flex justify-end">
        <button
          type="submit"
          class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium 
                 rounded-lg shadow transition disabled:opacity-60"
          :disabled="form.processing"
        >
          Enregistrer
        </button>
      </div>

    </form>
  </div>
</template>
