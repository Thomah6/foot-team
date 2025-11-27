<template>
  <Layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

      <div class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-indigo-600 mb-10">

        <blockquote class="text-3xl font-serif text-gray-900 italic mb-6 border-l-4 border-gray-300 pl-4">
          "{{ reflection.contenu }}"
        </blockquote>

        <div class="flex justify-between items-center text-gray-500 text-sm">
          <p class="font-semibold text-indigo-700">Publié par {{ reflection.user.name }}</p>
          <p>Le {{ formattedDate }}</p>
        </div>

        <hr class="my-6" />

        <div class="flex items-center space-x-6">
          <h4 class="font-bold text-lg text-gray-700">Score de la Réflexion :</h4>

          <button
            @click="handleVote(true)"
            :disabled="!$page.props.auth.user"
            :class="{'text-green-600 font-extrabold': user_vote && user_vote.is_upvote, 'text-gray-400 hover:text-green-500': !user_vote || !user_vote.is_upvote}"
            class="flex items-center space-x-1 transition duration-200 disabled:opacity-50"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 20 20" fill="currentColor">
              <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 11-3 0v-6zM6.3 5.86c.64-.64 1.48-.96 2.33-.96h3.48c1.67 0 3.2.78 4.28 2.06.66.78 1.05 1.62 1.05 2.51v1.98a2.6 2.6 0 01-2.6 2.6H11v3.25a.75.75 0 01-.75.75h-2a.75.75 0 01-.75-.75V11H6.3a.75.75 0 01-.7-.41L3.92 8.7a1.5 1.5 0 01.3-1.63L6.3 5.86z" />
            </svg>
            <span class="text-xl">{{ upvotes_count }}</span>
          </button>

          <button
            @click="handleVote(false)"
            :disabled="!$page.props.auth.user"
            :class="{'text-red-600 font-extrabold': user_vote && !user_vote.is_upvote, 'text-gray-400 hover:text-red-500': !user_vote || user_vote.is_upvote}"
            class="flex items-center space-x-1 transition duration-200 disabled:opacity-50"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 transform rotate-180" viewBox="0 0 20 20" fill="currentColor">
              <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 11-3 0v-6zM6.3 5.86c.64-.64 1.48-.96 2.33-.96h3.48c1.67 0 3.2.78 4.28 2.06.66.78 1.05 1.62 1.05 2.51v1.98a2.6 2.6 0 01-2.6 2.6H11v3.25a.75.75 0 01-.75.75h-2a.75.75 0 01-.75-.75V11H6.3a.75.75 0 01-.7-.41L3.92 8.7a1.5 1.5 0 01.3-1.63L6.3 5.86z" />
            </svg>
            <span class="text-xl">{{ downvotes_count }}</span>
          </button>

          <p v-if="!$page.props.auth.user" class="text-sm text-red-500">Connectez-vous pour voter.</p>
        </div>
      </div>

      <div class="mt-10">
        <h3 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">
          Commentaires ({{ reflection.comments.length }})
        </h3>

        <div v-if="$page.props.auth.user" class="mb-8 p-6 bg-gray-50 rounded-xl shadow-inner">
            <h4 class="font-semibold mb-3">Ajouter un commentaire</h4>
            <textarea
                rows="3"
                placeholder="Votre réponse..."
                class="w-full p-3 border rounded-lg focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                :disabled="true"
            ></textarea>
            <button
                class="mt-3 px-4 py-2 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 disabled:opacity-50"
                :disabled="true"
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

          <div v-if="reflection.comments.length === 0" class="text-center p-6 bg-gray-50 rounded-lg text-gray-500 italic">
            Soyez le premier à commenter !
          </div>
        </div>
      </div>

    </div>
  </Layout>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import { router } from '@inertiajs/vue3';
// import Layout from '@/Layouts/AppLayout.vue';


// Formatage de la date des commentaires
const formatCommentDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString('fr-FR');
};
defineProps({
  reflection: Object, // Reçoit les données du contrôleur Laravel
});
// Logique pour gérer le vote (cette route doit être créée)
const handleVote = (isUpvote) => {
    if (!props.$page.props.auth.user) {
        alert("Veuillez vous connecter pour voter.");
        return;
    }

    // Envoi de la requête POST ou PUT à une nouvelle route de contrôleur (ex: VoteController)
    // Ici, nous simulons l'appel avec un simple rechargement pour l'exemple

    // Exemple de routage vers une route à créer:
    // router.post(route('reflections.vote', props.reflection.id), { is_upvote: isUpvote }, {
    //     preserveScroll: true,
    //     preserveState: true,
    // });

    alert(`Vote ${isUpvote ? 'positif' : 'négatif'} enregistré ! (Action API à implémenter)`);
    // Pour une expérience réelle, vous rechargeriez la page après le vote réussi
    // router.reload({ only: ['upvotes_count', 'downvotes_count', 'user_vote'] });
};
</script>
