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
    onSuccess: () => form.reset('content'), // Réinitialise le contenu après succès
  });
};
</script>

<template>
  <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
    <h3 class="text-2xl font-bold text-gray-800 mb-4">Partagez votre réflexion</h3>

    <form @submit.prevent="submit">
      <textarea
        v-model="form.content"
        rows="4"
        class="w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-150 resize-none"
        placeholder="Écrivez ce qui vous vient à l'esprit... (500 caractères max)"
        maxlength="500"
      ></textarea>

      <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">
        {{ form.errors.content }}
      </div>

      <div class="text-right text-sm text-gray-500 mt-1">
        {{ 500 - form.content.length }} caractères restants
      </div>

      <button
        type="submit"
        :disabled="form.processing || form.content.length === 0"
        class="mt-4 w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition duration-200 disabled:opacity-50"
      >
        <span v-if="form.processing">Soumission...</span>
        <span v-else>Soumettre la Réflexion</span>
      </button>

      <div v-if="$page.props.flash.success" class="mt-3 p-3 bg-green-100 text-green-700 rounded-lg">
        {{ $page.props.flash.success }}
      </div>
    </form>
  </div>
</template>


