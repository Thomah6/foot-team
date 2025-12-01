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
  <div class="max-w-3xl mx-auto bg-white dark:bg-gray-900 p-6 rounded-xl shadow-md">

    <!-- Title -->
    <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-100">
      Créer une actualité
    </h1>

    <!-- Form -->
    <form @submit.prevent="form.post('/admin/news')" class="space-y-6">

      <NewsForm
        :form="form"
        :errors="form.errors"
        :processing="form.processing"
      />

      <!-- Submit Button -->
      <div class="flex justify-end">
        <button
          class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow transition disabled:opacity-60"
          :disabled="form.processing"
        >
          Enregistrer
        </button>
      </div>

    </form>
  </div>
</template>
