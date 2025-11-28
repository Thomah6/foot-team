<template>
    <AuthenticatedLayout>
  <Layout class="flex flex-col sm:flex-row">
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8 ">

  <Layout>
    <div class="max-w-4xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
      <div class="bg-white p-8 rounded-2xl shadow-2xl border-t-4 border-indigo-600 mb-10">
        <blockquote
          class="text-3xl font-serif text-gray-900 italic mb-6 border-l-4 border-gray-300 pl-4"
        >
          "{{ reflection.contenu }}"
        </blockquote>

        <div class="flex justify-between items-center text-gray-500 text-sm">
          <p class="font-semibold text-indigo-700">
            Publié par {{ reflection.user.name }}
          </p>
          <p>Le {{ formatCommentDate(reflection.created_at) }}</p>
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

          <div class="relative ms-3">
            <Dropdown align="right" width="48">
                <template #trigger>
                    <span class="inline-flex rounded-md">
                        <button
                            type="button"
                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                        >

                            <svg
                                class="-me-0.5 ms-2 h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="currentColor"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </span>
                </template>

                <template #content>
                    <DropdownLink
                        @click="activeRef(reflection.id)"
                    >
                        Ouvrir
                    </DropdownLink>
                    <DropdownLink
                        @click="deleteRef(reflection.id)"
                    >
                        Supprimer
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>

          <p v-if="!$page.props.auth.user" class="text-sm text-red-500">Connectez-vous pour voter.</p>
        </div>
      </div>

      <CommentForm :reflection="reflection"/>
      <CreateForm/>
    </div>
    <div class=" mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div v-if="comments.length === 0" class="text-center p-6 bg-gray-50 rounded-lg text-gray-500 italic w-full">
            Soyez le premier à commenter !
        </div>
        <div v-else>
            <h2 class="text-2xl font-bold mb-6">Commentaires</h2>
            <div v-for="comment in comments" :key="comment.id" class="mb-6 p-6 bg-white rounded-lg shadow">
                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center">
                        <img
                            :src="comment.user.profile_photo_url"
                            alt="Photo de profil"
                            class="w-10 h-10 rounded-full me-4"
                        />
                        <div>
                            <p class="font-semibold text-gray-800">{{ comment.user.name }}</p>
                            <p class="text-sm text-gray-500">Le {{ formatCommentDate(comment.created_at) }}</p>
                        </div>
                    </div>
                </div>
                <p>{{ comment.content }}</p>
            </div>
        <div>
          <Vote
            :options="options"
            :isAdmin="isAdmin"
            :isVoteEnded="isVoteEnded"
            :reflection ="reflection"
            @validate-vote="
              (selectedOption) => console.log('Vote validated for option:', selectedOption)
            "
          />
        </div>
    </div>
  </Layout>
</AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import CreateForm from './CreateForm.vue';
import CommentForm from './CommentForm.vue';
// import Layout from '@/Layouts/AppLayout.vue';

import Vote from "@/Components/Vote.vue";
import { defineProps } from "vue";
import { router } from "@inertiajs/vue3";

defineProps({
  options: {
    type: Array,
    required: true,
  },
  isAdmin: {
    type: Boolean,
    default: false,
  },
  isVoteEnded: {
    type: Boolean,
    default: false,
  },
  reflection: Object,
});

// Formatage de la date des commentaires
const formatCommentDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR");
};
defineProps({
  reflection: Object, // Reçoit les données du contrôleur Laravel
  comments: Array,
});

function deleteRef(id) {
  router.get(route("reflections.destroy", id));
}
function activeRef(id) {
  router.get(route("reflections.validate", id));
}
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

  alert(
    `Vote ${isUpvote ? "positif" : "négatif"} enregistré ! (Action API à implémenter)`
  );
  // Pour une expérience réelle, vous rechargeriez la page après le vote réussi
  // router.reload({ only: ['upvotes_count', 'downvotes_count', 'user_vote'] });
};

const validateVote = () => {
  alert("Vote validé !");
};
</script>

<style scoped></style>
