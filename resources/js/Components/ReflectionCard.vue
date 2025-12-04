<template>
    <div 
            @click="reflection.statut !== 'valide' && show(reflection.id)"
            :class="[
                'p-6 rounded-lg shadow-md transition-all duration-300 border-l-4',
                reflection.votes.find(vote => vote.user_id === $page.props.auth.user?.id)
                    ? 'bg-citron-50 dark:bg-citron-900/20 border-citron-500' 
                    : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-lime-500',
                reflection.statut === 'valide' 
                    ? 'pointer-events-none opacity-70 cursor-not-allowed' 
                    : 'hover:shadow-lg hover:border-citron-400 dark:hover:border-citron-500 cursor-pointer hover:scale-[1.02]'
            ]"
            class="dark:border-lime-500"
        >
        <p class="text-gray-700 dark:text-gray-300 italic mb-4 h-10">
        "{{ truncatedContent }}"
        </p>
        <div class="flex items-center justify-between text-sm border-t pt-3">
        <span class="font-semibold text-citron-600 dark:text-blue-400 ">
            — {{ reflection.user.name }}
        </span>
        <span class="text-gray-500 dark:text-gray-400">
            {{ formattedDate }}... 
        </span>
        <span :class="{
            'text-yellow-500 dark:text-yellow-400':reflection.statut=='ferme',
            'text-blue-500 dark:text-blue-400':reflection.statut=='ouvert',
            'text-green-500 dark:text-green-400':reflection.statut=='valide'
        }" class="font-medium">
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