<template>
<AuthenticatedLayout>
    <Layout>
        <div class="max-w-6xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-100">

        <div class="md:col-span-2">

          <div class="space-y-6">
            <div v-if="reflections.length === 0" class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200">
              <p class="text-gray-500 italic text-lg">
                Aucune réflexion active n'a été trouvée pour les derniers 7 jours.
              </p>
            </div>

            <ReflectionCard
              v-for="reflection in reflections"
              :key="reflection.id"
              :reflection="reflection"
            />
          </div>
        </div>
      </div>
    </div>
  </Layout>
</AuthenticatedLayout>
</template>
<script setup>
import { defineProps } from 'vue';
import AdminsideBar from '@/Components/AdminsideBar.vue';
import ReflectionForm from '@/Components/ReflectionForm.vue';
import ReflectionCard from '@/Components/ReflectionCard.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
  reflections: Array, // Reçoit les données du contrôleur Laravel
});
</script>
