<script setup>
import { useForm } from '@inertiajs/vue3';
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

// ⚡ Bouton pour supprimer logo + preview + nom
const deleteIdentity = () => {
    form.post('/admin/identity/delete-identity', {
        onSuccess: () => {
            form.name = "";      // vider le nom côté formulaire
            form.logo = null;    // vider le fichier côté formulaire
            preview.value = null; // retirer la preview
        }
    });
};


</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 space-y-6">
      
      <!-- Titre -->
      <h1 class="text-2xl font-bold text-gray-800 text-center">
        Identité du Club
      </h1>

      <!-- Nom du club -->
      <div class="space-y-1">
        <label class="block font-medium text-gray-700"></label>
        <input
          v-model="form.name"
          type="text"
          placeholder="Entrez le nom du club"
          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent border-0  " 
          autofocus

        />
        <div v-if="form.errors.name" class="text-red-500 text-sm">
          {{ form.errors.name }}
        </div>
      </div>

      <!-- Logo -->
      <div class="space-y-2">
        <label class="block font-medium text-gray-700"></label>

        <!-- Aperçu -->
        <div v-if="preview" class="flex justify-center mb-2">
          <img 
            :src="preview" 
            alt="Logo" 
            class="w-32 h-32 rounded-full object-cover border border-gray-300 shadow-sm"
          />
        </div>

        <input
          type="file"
          @change="e => form.logo = e.target.files[0]"
          class="block w-full text-sm text-gray-600
                 file:mr-4 file:py-2 file:px-4
                 file:rounded-full file:border-0
                 file:text-sm file:font-semibold
                 file:bg-blue-50 file:text-blue-700
                 hover:file:bg-blue-100"
        />

        <div v-if="form.errors.logo" class="text-red-500 text-sm">
          {{ form.errors.logo }}
        </div>
      </div>

      <!-- Bouton -->
      <button
        @click="submit"
        :disabled="form.processing"
        class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 disabled:opacity-50 transition-colors"
      >
        Enregistrer
      </button>
<button
  @click="deleteIdentity"
  type="button"
  class="w-full py-2 px-4 bg-red-600 text-white font-semibold rounded-lg shadow hover:bg-red-700 transition-colors"
>
  Supprimer logo et nom
</button>

    </div>
  </div>
</template>
