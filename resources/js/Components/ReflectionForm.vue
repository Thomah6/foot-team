<script setup>
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

// Formulaire Inertia
const form = useForm({
  content: '',
});

const page = usePage();

const submit = () => {
  form.post(route('reflections.store'), {
    onSuccess: () => form.reset('content'),
  });
};
</script>

<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mb-4">Partagez votre réflexion</h3>

    <form @submit.prevent="submit">
      <textarea
        v-model="form.content"
        rows="4"
        class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-citron-500 focus:border-citron-500 dark:focus:ring-citron-600 dark:focus:border-citron-600 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 transition duration-150 resize-none placeholder-gray-500 dark:placeholder-gray-400"
        placeholder="Écrivez ce qui vous vient à l'esprit... (500 caractères max)"
        maxlength="500"
      ></textarea>

      <div v-if="form.errors.content" class="text-red-500 dark:text-red-400 text-sm mt-1 font-medium">
        {{ form.errors.content }}
      </div>

      <div class="text-right text-sm text-gray-500 dark:text-gray-400 mt-1">
        {{ 500 - form.content.length }} caractères restants
      </div>

      <button
        type="submit"
        :disabled="form.processing || form.content.length === 0"
        class="mt-4 w-full px-4 py-2 bg-citron-500 hover:bg-citron-600 text-white font-semibold rounded-lg transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
      >
        <span v-if="form.processing">Soumission...</span>
        <span v-else>Soumettre la Réflexion</span>
      </button>
    </form>
  </div>
</template>