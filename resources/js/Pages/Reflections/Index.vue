<template>
  <Layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <header class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-gray-900">Le Mur des Réflexions</h1>
        <p class="mt-2 text-lg text-gray-500">
          Partagez une pensée, une idée, ou une observation du moment.
        </p>
      </header>

      <div v-if="$page.props.auth.user" class="mb-12">
        <ReflectionForm />
      </div>
      <div v-else class="mb-12 text-center p-6 bg-yellow-50 rounded-lg border border-yellow-200">
        <p class="text-lg text-yellow-800">
          Veuillez vous **connecter** pour pouvoir soumettre une nouvelle réflexion.
        </p>
      </div>

      <hr class="my-10" />

      <h2 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">
        Réflexions du moment (derniers 7 jours)
      </h2>

      <div class="space-y-6">
        <div v-if="reflections.length === 0" class="text-center p-8 bg-gray-50 rounded-lg">
          <p class="text-gray-500 italic">
            Aucune réflexion disponible pour le moment. Soyez le premier à en soumettre une !
          </p>
        </div>

        <ReflectionCard
          v-for="reflection in reflections"
          :key="reflection.id"
          :reflection="reflection"
        />
      </div>
    </div>
  </Layout>
</template>

<script setup>
import { defineProps } from 'vue';
import Layout from '@/Layouts/AppLayout.vue'; // Assurez-vous d'avoir un layout principal
import ReflectionForm from '@/Components/ReflectionForm.vue';
import ReflectionCard from '@/Components/ReflectionCard.vue';

defineProps({
  reflections: Array, // Reçoit les données du contrôleur Laravel
});
</script>
