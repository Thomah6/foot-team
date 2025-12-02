<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";

const props = defineProps({
  news: Object,
});

const form = useForm({
  title: props.news.title,
  content: props.news.content,
  is_published: props.news.is_published,
  image: null, // <-- Ajout du champ image
});
</script>

<template>
  <div class="max-w-3xl mx-auto bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-md">
    <!-- Title -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800">
      Modifier l’actualité
    </h1>

    <!-- Form -->
    <form
      @submit.prevent="form.post(`/admin/news/${props.news.id}`, { forceFormData: true })"
      class="space-y-6"
    >

      <!-- Composant du formulaire -->
      <NewsForm
        :form="form"
        :errors="form.errors"
        :processing="form.processing"
        :news="props.news"
      />

      <!-- Submit Button -->
      <div class="flex justify-end">
        <button
          class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition"
          :disabled="form.processing"
        >
          Mettre à jour
        </button>
      </div>

    </form>
  </div>
</template>
