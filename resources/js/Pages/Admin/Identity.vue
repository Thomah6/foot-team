<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    identity: Object
});

// ⚡ Notification success
const pendingMessage = ref(null);

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

            // Message succès visible
            pendingMessage.value = "Identité mise à jour avec succès ✔️";

            // Reset du formulaire
            form.reset();
        }
    });
};
</script>


<template>

<div class="flex flex-col gap-4">
  <a href="/dashboard"
     class="self-end px-4 py-2 bg-green-600 text-white rounded-lg shadow 
            hover:bg-green-700 transition font-medium">
    Sortir
  </a>
</div>

  <div class="min-h-screen flex items-center justify-center bg-gray-50 p-6">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md p-8 space-y-6">
      
      <!-- Titre -->
      <h1 class="text-2xl font-bold text-gray-800 text-center">
        Identité du Club
      </h1>
<div v-if="pendingMessage" class="p-4 mb-4 bg-green-100 text-green-700 rounded-md">
  {{ pendingMessage }}
</div>

      <!-- Nom du club -->
   <form @submit.prevent="submit" class="space-y-6">

  <!-- Nom du club -->
  <label class="block space-y-1">
    <span class="font-medium text-gray-700">Slogan</span>

    <input
      v-model="form.name"
      type="text"
      placeholder="Entrez le nom du club"
      class="w-full border border-gray-300 rounded-lg px-4 py-2 
             focus:outline-none focus:ring-2 focus:ring-green-500 
             focus:border-transparent"
      autofocus
    />

    <span v-if="form.errors.name" class="text-red-500 text-sm">
      {{ form.errors.name }}
    </span>
  </label>

  <!-- Logo -->
  <label class="block space-y-2">
    <span class="font-medium text-gray-700">Logo du club</span>

    <!-- Aperçu -->
    <img
      v-if="preview"
      :src="preview"
      alt="Logo"
      class="w-32 h-32 rounded-full object-cover border border-gray-300 shadow-sm mx-auto"
    />

    <input
      type="file"
      @change="e => form.logo = e.target.files[0]"
      class="block w-full text-sm text-gray-600
             file:mr-4 file:py-2 file:px-4
             file:rounded-full file:border-0
             file:text-sm file:font-semibold
             file:bg-green-50 file:text-green-700
             hover:file:bg-green-100"
    />

    <span v-if="form.errors.logo" class="text-red-500 text-sm">
      {{ form.errors.logo }}
    </span>
  </label>

  <!-- Bouton -->
  <button
    type="submit"
    :disabled="form.processing"
      class="w-full bg-green-500 text-white py-3 rounded-xl hover:bg-green-600 transition font-semibold shadow-md"
  >
    Enregistrer
  </button>

</form>
    </div>
      </div>


</template>
