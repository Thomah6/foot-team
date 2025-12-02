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

        </form>
      </div>
    </div>
  </div>
</template>
