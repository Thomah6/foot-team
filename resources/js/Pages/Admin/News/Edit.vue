<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";

const props = defineProps({
  news: Object,
});

const form = useForm({
<<<<<<< HEAD
  title: props.news.title,
  content: props.news.content,
  is_published: props.news.is_published,
=======
  title: props.news?.title || '',
  content: props.news?.content || '',
  is_published: props.news?.is_published ?? false,
>>>>>>> bc0c59620896928c4b8142a64de53d68a350f302
  image: null,
});


const submit = () => {
  form.transform((data) => ({
    ...data,
  })).put(`/admin/news/${props.news.id}`, {
    forceFormData: true,
  });
};


</script>

<template>
<<<<<<< HEAD
  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
    <!-- Title -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
        Modifier l'actualité
      </h1>
      <p class="text-gray-600 dark:text-gray-400 mt-1">
        Mettez à jour les informations de cette actualité
      </p>
    </div>
=======
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 p-4">
    <div class="flex-1 flex justify-center items-start w-full">
      <div class="w-full max-w-3xl bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-md flex flex-col flex-1">
>>>>>>> bc0c59620896928c4b8142a64de53d68a350f302

        <!-- Header -->
        <h1 class="text-2xl sm:text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
          Modifier l’actualité
        </h1>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="space-y-6 flex flex-col flex-1 overflow-auto">
          <NewsForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
            :news="props.news"
          />

<<<<<<< HEAD
      <!-- Submit Button -->
      <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
        <button
          class="px-6 py-3 bg-citron-500 hover:bg-citron-600 text-white font-medium rounded-lg shadow-sm transition-all duration-200 disabled:opacity-60 flex items-center gap-2"
          :disabled="form.processing"
        >
          <span v-if="form.processing" class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
          <span>{{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}</span>
        </button>
=======
          <!-- Submit -->
          <div class="flex justify-end mt-auto">
            <button
              type="submit"
              class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium rounded-lg shadow transition disabled:opacity-60"
              :disabled="form.processing"
            >
              Mettre à jour
            </button>
          </div>
        </form>
>>>>>>> bc0c59620896928c4b8142a64de53d68a350f302
      </div>
    </div>
  </div>
</template>