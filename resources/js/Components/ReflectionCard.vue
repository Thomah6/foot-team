<template>
  <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-indigo-500">
    <p class="text-gray-700 italic mb-4">
      "{{ reflection.content }}"
    </p>

    <div class="flex items-center justify-between text-sm text-gray-500 border-t pt-3">
      <span class="font-semibold text-indigo-600">
        — {{ reflection.user.name }}
      </span>
      <span>
        {{ formattedDate }}
      </span>
    </div>
  </div>
</template>

<script setup>
import { defineProps, computed } from 'vue';

const props = defineProps({
  reflection: {
    type: Object,
    required: true,
  },
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
