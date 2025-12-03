<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";

const props = defineProps({
  news: Object,
});

const form = useForm({
  title: props.news?.title || '',
  content: props.news?.content || '',
  is_published: props.news?.is_published ?? false,
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
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 p-4">
    <div class="flex-1 flex justify-center items-start w-full">
      <div class="w-full max-w-3xl bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-md flex flex-col flex-1">

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
      </div>
    </div>
  </div>
</template>
