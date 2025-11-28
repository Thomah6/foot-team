<template>
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

    </div>
  </Layout>
</template>

<script setup>
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
