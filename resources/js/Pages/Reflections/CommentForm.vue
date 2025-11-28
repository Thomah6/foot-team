<template>
    <div class="mt-10">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
          Commentaires ({{ reflection.comments.length }})
        </h3>

        <div v-if="$page.props.auth.user" class="mb-8 p-6 bg-gray-50 rounded-xl shadow-inner">
            <h4 class="font-semibold mb-3">Ajouter un commentaire</h4>
            <textarea
                name="content"
                rows="3"
                placeholder="Votre réponse..."
                class="w-full p-3 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                :disabled="false"
            ></textarea>
            <button v-if="content.length<0"
                class="mt-3 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 disabled:opacity-50"
                :disabled="true"
            >
                Publier (Bientôt)
            </button>
            <button v-else @click.prevent=""
                type="submit"
                class="mt-3 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 disabled:opacity-50"
                :disabled="false"
            >
                Publier (Bientôt)
            </button>
        </div>
        <div v-else class="mb-8 p-4 bg-yellow-50 rounded-lg text-yellow-800 border border-yellow-200">
            Connectez-vous pour commenter cette réflexion.
        </div>

        <div class="space-y-5">
          <div v-for="comment in reflection.comments" :key="comment.id" class="bg-white p-5 rounded-lg shadow-md border-l-4 border-blue-200">
            <p class="text-gray-700 mb-3">{{ comment.contenu }}</p>
            <div class="text-xs text-gray-400 font-medium">
              Par **{{ comment.user.name }}** le {{ formatCommentDate(comment.created_at) }}
            </div>
          </div>
        </div>
      </div>
</template>

<script setup>  
import { defineProps, ref } from 'vue';
const props = defineProps({
    reflection: {
        type: Object,
        required: true,
    },
});
function comment(){
    form.post(route('comments.store', props.reflection.id), {
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