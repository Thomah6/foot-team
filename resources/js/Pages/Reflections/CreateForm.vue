<template>
  <div class="bg-white p-6 rounded-2xl shadow-xl border border-indigo-100 transform hover:shadow-2xl transition duration-300">
    <h3 class="text-2xl font-extrabold text-indigo-700 mb-5 border-b pb-3">Partagez votre lumière 💡</h3>

    <form @submit.prevent="submit">

        <input type="text"
            name="titre"
            v-model="form.titre"
            class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 resize-none text-gray-700 placeholder-gray-400 font-medium"
            placeholder="Choisissez un titre percutant"
        />
        <div v-if="form.errors.titre" class="text-red-500 text-sm mt-2">
            {{ form.errors.titre }}
        </div>
        <br>
      
      <textarea
        name="contenu"
        v-model="form.contenu"
        rows="5"
        class="w-full p-4 border-2 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 resize-none text-gray-700 placeholder-gray-400 font-medium"
        placeholder="Écrivez ce qui vous vient à l'esprit... (500 caractères max)"
        maxlength="500"
      ></textarea>

      <div v-if="form.errors.contenu" class="text-red-500 text-sm mt-2">
        {{ form.errors.contenu }}
      </div>

      <div class="text-right text-sm text-gray-500 mt-2">
        {{ 500 - form.contenu.length }} caractères restants
      </div>

      <button
        type="submit"
        :disabled="form.processing || form.contenu.length === 0 || form.titre.length === 0"
        class="mt-6 w-full px-6 py-3 bg-indigo-600 text-white text-lg font-bold rounded-xl hover:bg-indigo-700 transition duration-300 transform hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="form.processing">Soumission en cours...</span>
        <span v-else>Envoyer la Réflexion</span>
      </button>

      <div v-if="$page.props.flash && $page.props.flash.success" class="mt-4 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm">
        <p class="font-semibold">{{ $page.props.flash.success }}</p>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';

// 1. Initialisation du formulaire Inertia
const form = useForm({
  titre: '',
  contenu: '',
});

function submit(){
    form.post(route('reflections.store'), {
        onSuccess: () => form.reset('titre', 'contenu'), // Réinitialise les champs après succès
    });
}
</script>