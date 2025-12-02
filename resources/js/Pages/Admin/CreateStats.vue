<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
 
const props = defineProps({
  users: Array,
});
 
// 🔥 Message affiché après enregistrement
const pendingMessage = ref('');
 
// 🔥 Formulaire Inertia
const form = useForm({
  user_id: '',
  goals: '',
  assists: '',
  goals_against: '',
  matches_played: '',
  date: '',
});
 
// 🔥 Soumission + reset + message
const submit = () => {
  form.post(route('admin.stats.store'), {
    preserveScroll: true,
 
    onSuccess: () => {
      pendingMessage.value =
        "Statistique envoyée, en attente de validation par l'administrateur.";
 
      // Réinitialise proprement tous les champs
      form.reset();
    },
  });
};
</script>
 
 
<template>
<AuthenticatedLayout>
 
 
 <div class="flex">
 
 
 
 
         <div class=" flex-1">
  <h1 class="text-center text-3xl font-bold mb-8 text-gray-800">
    Statistique des joueurs
  </h1>
 
  <div class="max-w-xl mx-auto bg-white shadow-lg rounded-2xl p-8 border border-gray-200">
 
    <h2 class="text-xl font-semibold mb-6 text-gray-700">
      Ajouter une Statistique
    </h2>
 
    <!-- Flash success -->
    <div v-if="$page.props.flash?.success" class="mb-4 p-3 rounded-md bg-green-50 text-green-700 border border-green-200">
      {{ $page.props.flash.success }}
    </div>
 
  <div v-if="pendingMessage" class="p-4 mb-4 bg-yellow-100 text-yellow-700 rounded-md">
  {{ pendingMessage }}
</div>
 
 
 
    <form @submit.prevent="submit" class="space-y-5">
 
      <!-- FIELD TEMPLATE -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Membre</label>
        <select v-model="form.user_id"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
          <option value="">-- Sélectionner un membre --</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
        <p v-if="form.errors.user_id" class="text-sm text-red-500 mt-1">
          {{ form.errors.user_id }}
        </p>
      </div>
 
      <!-- GOALS -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Buts</label>
        <input type="number" min="0" v-model="form.goals"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
        <p v-if="form.errors.goals" class="text-sm text-red-500 mt-1">
          {{ form.errors.goals }}
        </p>
      </div>
 
      <!-- ASSISTS -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Passes Décisives</label>
        <input type="number" min="0" v-model="form.assists"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
        <p v-if="form.errors.assists" class="text-sm text-red-500 mt-1">
          {{ form.errors.assists }}
        </p>
      </div>
 
      <!-- GOALS AGAINST -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Buts Encaissés</label>
        <input type="number" min="0" v-model="form.goals_against"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
        <p v-if="form.errors.goals_against" class="text-sm text-red-500 mt-1">
          {{ form.errors.goals_against }}
        </p>
      </div>
 
      <!-- MATCHES PLAYED -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Matchs Joués</label>
        <input type="number" min="1" v-model="form.matches_played"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
        <p v-if="form.errors.matches_played" class="text-sm text-red-500 mt-1">
          {{ form.errors.matches_played }}
        </p>
      </div>
 
      <!-- DATE -->
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-600">Date</label>
        <input type="date" v-model="form.date"
          class="w-full rounded-lg border border-gray-300 p-2.5 bg-gray-50 focus:ring-2 focus:ring-green-300 focus:border-green-400 outline-none transition">
        <p v-if="form.errors.date" class="text-sm text-red-500 mt-1">
          {{ form.errors.date }}
        </p>
      </div>
 
      <!-- SUBMIT -->
      <button type="submit" :disabled="form.processing"
        class="w-full bg-green-500 text-white py-3 rounded-xl hover:bg-green-600 transition font-semibold shadow-md">
        Enregistrer
      </button>
 
    </form>
  </div>
  </div>
  </div>
</AuthenticatedLayout>
 
</template>
 
 