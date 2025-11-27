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
});
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Modifier l’Actualité</h1>

    <form @submit.prevent="form.put(`/admin/news/${props.news.id}`)">
      <NewsForm
        :form="form"
        :errors="form.errors"
        :processing="form.processing"
      />

      <button class="mt-4 bg-blue-600 text-white px-4 py-2 rounded">
        Mettre à jour
      </button>
    </form>
  </div>
</template>
