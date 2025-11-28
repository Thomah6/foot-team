<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";

const form = useForm({
  title: "",
  content: "",
  is_published: false,
});
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Créer une Actualité</h1>

    <form @submit.prevent="form.post('/admin/news')">
      <NewsForm :form="form" :errors="form.errors" :processing="form.processing" />

      <button
        class="mt-4 bg-green-600 text-white px-4 py-2 rounded"
        :disabled="form.processing"
      >
        Enregistrer
      </button>
    </form>
  </div>
</template>
