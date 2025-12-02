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
  <div
    class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">

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
    <form @submit.prevent="form.post('/admin/news', { forceFormData: true })" class="space-y-6">        

            <!-- Formulaire -->
            <form @submit.prevent="form.post('/admin/news', { forceFormData: true })"
              class="space-y-6 flex flex-col flex-1 overflow-auto">
              <NewsForm :form="form" :errors="form.errors" :processing="form.processing" />

              <!-- Submit -->
              <div class="flex justify-end mt-auto">
                <button type="submit"
                  class="px-6 py-2.5 bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 text-white font-medium rounded-lg shadow transition disabled:opacity-60"
                  :disabled="form.processing">
                  Enregistrer
                </button>
              </div>

            </form>
        
      </form>
    </div>
  
</template>