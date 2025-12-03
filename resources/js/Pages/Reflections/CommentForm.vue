<template>
    <div class="mt-10">
        <h3 class="text-2xl font-bold text-gray-800 dark:text-lime-500 mb-6 border-b pb-3 border-gray-200 dark:border-gray-700">
          Commentaires ({{ reflection.comments.length }})
        </h3>

        <div v-if="$page.props.auth.user" class="mb-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-xl shadow-inner">
            <h4 class="font-semibold mb-3 text-gray-700 dark:text-lime-500">Ajouter un commentaire</h4>
            <textarea
                name="content"
                v-model="form.content"
                rows="3"
                placeholder="Votre réponse..."
                class="w-full p-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-citron-500 focus:border-citron-500 resize-none bg-white dark:bg-gray-700 text-gray-700 dark:text-white"
                :disabled="false"
            ></textarea>
            <button v-if="content.length<0"
                class="mt-3 px-4 py-2 bg-citron-500 hover:bg-citron-600 dark:bg-citron-400 dark:hover:bg-citron-500 text-white rounded-lg disabled:opacity-50 transition"
                :disabled="true"
            >
                Publier (Bientôt)
            </button>
            <button v-else @click.prevent="comment()"
                type="submit"
                class="mt-3 px-4 py-2 bg-lime-500 hover:bg-lime-600 dark:bg-lime-400 dark:hover:bg-lime-500 text-white rounded-lg disabled:opacity-50 transition"
                :disabled="false"
            >
                Publier (Bientôt)
            </button>
        </div>
        <div v-else class="mb-8 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg text-yellow-800 dark:text-yellow-300 border border-yellow-200 dark:border-yellow-700">
            Connectez-vous pour commenter cette réflexion.
        </div>
      </div>
</template>
<script setup>
import { defineProps, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
const form = useForm({
    reflection_id: null,
    content: '',
});
const props = defineProps({
    reflection: {
        type: Object,
        required: true,
    },
});
function comment() {
    // console.log(reflectionId);
    form.reflection_id = props.reflection.id;

    form.post(route('comments.store'), {
        onSuccess: () => form.reset('content'), // Réinitialise les champs après succès
    });
}
// const formatCommentDate = (dateString) => {
//     const date = new Date(dateString);
//     return date.toLocaleDateString('fr-FR', {
//         year: 'numeric',
//         month: 'long',
//         day: 'numeric',
//     });
// };
const content = ref('');
</script>
