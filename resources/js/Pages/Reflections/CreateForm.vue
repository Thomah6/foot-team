<template>
  <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-xl border border-citron-100 dark:border-citron-900 transform hover:shadow-2xl transition duration-300">
    <div class="flex justify-between">
        <h3 class="text-2xl font-extrabold text-citron-700 dark:text-citron-300 mb-5 border-b pb-3 border-citron-200 dark:border-citron-700">Partagez votre Inspi ⚽</h3>
        <button @click="closeForm" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
            X
        </button>
    </div>

    <form @submit.prevent="submit">

        <input type="text"
            name="titre"
            v-model="form.titre"
            class="cursor-custom-icon w-full my-6 p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-citron-500 focus:border-citron-500 transition duration-200 resize-none text-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 font-medium bg-gray-50 dark:bg-gray-700"
            placeholder="Choisissez un titre percutant"
        />
        <div v-if="form.errors.titre" class="text-red-500 dark:text-red-400 text-sm mt-2">
            {{ form.errors.titre }}
        </div>
        <br>
      
      <textarea
        name="contenu"
        v-model="form.contenu"
        rows="5"
        class="cursor-custom-icon w-full p-4 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-citron-500 focus:border-citron-500 transition duration-200 resize-none text-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 font-medium bg-gray-50 dark:bg-gray-700"
        placeholder="Écrivez ce qui vous vient à l'esprit... (500 caractères max)"
        maxlength="500"
      ></textarea>

      <div v-if="form.errors.contenu" class="text-red-500 dark:text-red-400 text-sm mt-2">
        {{ form.errors.contenu }}
      </div>

      <div class="text-right text-sm text-gray-500 dark:text-citron-300 mt-2">
        {{ 500 - form.contenu.length }} caractères restants
      </div>

      <button
        type="submit"
        :disabled="form.processing || form.contenu.length === 0 || form.titre.length === 0"
        class="mt-6 w-full px-6 py-3 bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white text-lg font-bold rounded-xl transition duration-300 transform hover:scale-[1.01] disabled:opacity-50 disabled:cursor-not-allowed"
      >
        <span v-if="form.processing">Soumission en cours...</span>
        <span v-else>Envoyer la Réflexion</span>
      </button>

      <div v-if="$page.props.flash && $page.props.flash.success" class="mt-4 p-4 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-400 dark:border-green-500 text-green-700 dark:text-green-300 rounded-lg shadow-sm">
        <p class="font-semibold">{{ $page.props.flash.success }}</p>
      </div>
    </form>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineEmits } from 'vue';

const emit = defineEmits(['close']);
function closeForm() {
    emit('close');
}

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
<style scoped>
.cursor-custom-icon:hover {
    /* Utilisez votre propre chemin vers l'image PNG, SVG ou ICO */
    /* Le 'auto' est une valeur de repli si l'image ne se charge pas */
    cursor: url('/pngegg.png'), auto; 
}
</style>