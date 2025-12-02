<script setup>
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
  identity: Object
});

// ⚡ Formulaire Inertia
const form = useForm({
  name: props.identity?.name ?? "",
  logo: null,
});

// ⚡ Prévisualisation du logo
const preview = ref(props.identity?.logo ?? null);

watch(() => form.logo, (newFile) => {
  if (newFile) {
    preview.value = URL.createObjectURL(newFile);
  }
});

// ⚡ Envoi du formulaire
const submit = () => {
  form.post('/admin/identity/update', {
    forceFormData: true,
    onSuccess: () => {
      console.log('Identité mise à jour');
    }
  });
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 p-6">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-md p-8 space-y-6">

      <!-- Titre -->
      <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100 text-center">
        Identité du Club
      </h1>

      <!-- Nom du club -->
      <div class="space-y-1">
        <label class="block font-medium text-gray-700 dark:text-gray-200">Nom du club</label>
        <input v-model="form.name" type="text" placeholder="Entrez le nom du club"
          class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100"
          autofocus />
        <div v-if="form.errors.name" class="text-red-500 text-sm">
          {{ form.errors.name }}
        </div>
      </div>

      <!-- Logo -->
      <div class="space-y-2">
        <label class="block font-medium text-gray-700 dark:text-gray-200">Logo</label>

        <!-- Aperçu -->
        <div v-if="preview" class="flex justify-center mb-2">
          <img :src="preview" alt="Logo"
            class="w-32 h-32 rounded-full object-cover border border-gray-300 dark:border-gray-600 shadow-sm" />
        </div>

        <input type="file" @change="e => form.logo = e.target.files[0]" class="block w-full text-sm text-gray-600 dark:text-gray-300
                 file:mr-4 file:py-2 file:px-4
                 file:rounded-full file:border-0
                 file:text-sm file:font-semibold
                 file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-400
                 hover:file:bg-blue-100 dark:hover:file:bg-blue-800" />

        <div v-if="form.errors.logo" class="text-red-500 text-sm">
          {{ form.errors.logo }}
        </div>
      </div>

      <!-- Bouton -->
      <Link :href="route('dashboard')">
      <button @click="submit" :disabled="form.processing"
        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 dark:hover:bg-blue-500 disabled:opacity-50 transition-colors">
        Enregistrer
      </button>
      </Link>
    </div>
  </div>
</template>
