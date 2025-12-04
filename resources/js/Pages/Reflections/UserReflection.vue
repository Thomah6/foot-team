<template>
    <div 
        @click="show(reflection.id)"
        :class="{ 
            '': reflection.votes.find(v => v.user_id === $page.props.auth.user?.id),
            'bg-white dark:bg-gray-800': reflection.votes.find(v => v.user_id === $page.props.auth.user?.id) === undefined 
        }"
        class="p-6 rounded-lg shadow-md hover:shadow-lg transition duration-300 border-l-4 border-citron-500 dark:border-lime-500 relative hover:bg-citron-50 dark:hover:bg-citron-900/20 cursor-pointer"
    >

        <!-- Boutons modifier/supprimer si l'utilisateur est le propriétaire -->
        <div 
            v-if="reflection.user_id === $page.props.auth.user?.id"
            class="absolute top-2 right-2 flex gap-2"
        >
            <button v-if="reflection.statut!=='ouvert' && reflection.statut!=='valide'"
                @click.stop="emitEdit(reflection)" 
                class="px-3 py-1 text-xs bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white rounded transition"
            >
                Modifier
            </button>

            <button 
                @click.stop="emitDelete(reflection.id)" 
                class="px-3 py-1 text-xs bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white rounded transition"
            >
                Supprimer
            </button>
        </div>

        <p class="text-gray-700 dark:text-white italic mb-4 h-10">
            "{{ truncatedContent }}"
        </p>

        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-citron-300 border-t dark:border-gray-700 pt-3">
            <span class="font-semibold text-citron-600 dark:text-blue-400">— {{ reflection.user.name }}</span>

            <span>{{ formattedDate }}...</span>

            <span :class="{
                'text-yellow-500 dark:text-yellow-400': reflection.statut == 'ferme',
                'text-citron-600 dark:text-citron-400': reflection.statut == 'ouvert',
                'text-green-600 dark:text-green-400': reflection.statut == 'valide'
            }">
                {{ reflection.statut }}
            </span>
        </div>
    </div>
</template>
<script setup>
import { defineProps, defineEmits, computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  reflection: {
    type: Object,
    required: true,
  },
});

// Déclaration des événements envoyés au parent
const emit = defineEmits(['edit', 'delete']);

function emitEdit(reflection) {
    emit('edit', reflection);
}

function emitDelete(id) {
    emit('delete', id);
}

// Navigation vers la page de détails
function show(id) {
    router.get(route('reflections.show', id));
}

// Tronque le contenu
const truncatedContent = computed(() => {
  const content = props.reflection.contenu || '';
  const maxLength = 100;
  return content.length > maxLength ? content.substring(0, maxLength) + '...' : content;
});

// Formatage de la date
const formattedDate = computed(() => {
  const date = new Date(props.reflection.created_at);
  return date.toLocaleDateString('fr-FR', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  });
});
</script>
