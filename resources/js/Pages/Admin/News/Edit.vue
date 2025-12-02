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
  image: null,
});
</script>

<template>
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
      <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
        <button
          class="px-6 py-3 bg-citron-500 hover:bg-citron-600 text-white font-medium rounded-lg shadow-sm transition-all duration-200 disabled:opacity-60 flex items-center gap-2"
          :disabled="form.processing"
        >
          <span v-if="form.processing" class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
          <span>{{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}</span>
        </button>
      </div>

    </form>
  </div>
</template>