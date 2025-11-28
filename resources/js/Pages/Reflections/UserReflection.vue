<template>
    <div @click="show(reflection.id)" 
        class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-indigo-500"
        >

        <p class="text-gray-700 italic mb-4 h-10">
        "{{ truncatedContent }}"
        </p>
        <div class="flex items-center justify-between text-sm text-gray-500 border-t pt-3">
        <span class="font-semibold text-indigo-600">
            — {{ reflection.user.name }}
        </span>
        <span>
            {{ formattedDate }}... 
        </span>
        <span :class="{
            'text-yellow-400':reflection.statut=='ferme',
            'text-blue-700':reflection.statut=='ouvert',
            'text-green-600':reflection.statut=='valide'
        }">
            {{ reflection.statut }} 
        </span>
        </div>
    </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  reflection: {
    type: Object,
    required: true,
  },
});
function show(id) {
    router.get(route('reflections.show',id))
}
// Tronque le contenu si il est trop long
const truncatedContent = computed(() => {
  const content = props.reflection.contenu;
  const maxLength = 100;
  if (content.length > maxLength) {
    return content.substring(0, maxLength) + '...';
  }
  return content;
});

// Formatage de la date pour un affichage plus lisible
const formattedDate = computed(() => {
  const date = new Date(props.reflection.created_at);
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
});
</script>