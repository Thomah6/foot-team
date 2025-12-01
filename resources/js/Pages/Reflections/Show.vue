<template>
    <AuthenticatedLayout>
        <Layout class="flex flex-col lg:flex-row bg-[#0B1E35]">

            <!-- ==================== LEFT SIDE ==================== -->
            <div class="max-w-4xl w-full lg:w-1/2 mx-auto py-10 px-4 sm:px-6 lg:px-8">

                <!-- CARD REFLECTION STYLE FOOT -->
                <div class="bg-white p-8 rounded-2xl shadow-2xl border-l-8 border-[#1E63F8] mb-10 relative">

                    <!-- Étiquette style match -->
                    <div class="absolute top-4 right-4 bg-[#1E63F8] text-white text-xs px-3 py-1 rounded-full shadow">
                        RÉFLEXION
                    </div>

                    <blockquote class="text-xl lg:text-3xl font-serif text-gray-900 italic mb-6 border-l-4 border-[#1E63F8] pl-4">
                        "{{ reflection.contenu }}"
                    </blockquote>

                    <div class="flex justify-between items-center text-gray-600 text-sm">

                        <!-- Auteur -->
                        <p class="font-semibold text-[#1E63F8] flex items-center gap-2">
                            <span class="inline-block w-2 h-2 bg-green-400 rounded-full"></span>
                            {{ reflection.user.name }}
                        </p>

                        <!-- Menu + date -->
                        <p class="flex items-center gap-4">

                            <Dropdown align="left" width="48">
                                <template #trigger>
                                    <button
                                        class="inline-flex items-center px-3 py-2 text-gray-600 hover:text-[#1E63F8] transition">
                                        ⚙️
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink @click="activeRef(reflection.id)">
                                        🔵 Ouvrir
                                    </DropdownLink>
                                    <DropdownLink @click="deleteRef(reflection.id)">
                                        🔴 Supprimer
                                    </DropdownLink>
                                </template>
                            </Dropdown>

                            <span class="text-gray-500">
                                Le {{ formatCommentDate(reflection.created_at) }}
                            </span>
                        </p>

                    </div>

                    <!-- VOTE STYLE FOOT -->
                    <div class="mt-6 border-t pt-6">
                        <Vote
                            :options="options"
                            :isAdmin="isAdmin"
                            :isVoteEnded="isVoteEnded"
                            :reflection="reflection"
                        />
                    </div>

                    <!-- COMPTEUR DE TEMPS RESTANT -->
                    <div v-if="reflection.date_fin_vote" class="mt-6 pt-6 border-t">
                        <div v-if="!timeRemaining.isEnded" class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border-l-4 border-[#1E63F8]">
                            <p class="text-sm font-semibold text-gray-700 mb-3">⏱️ Temps restant pour voter:</p>
                            <div class="flex gap-4 justify-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-[#1E63F8]">{{ String(timeRemaining.days).padStart(2, '0') }}</span>
                                    <span class="text-xs text-gray-600">jour(s)</span>
                                </div>
                                <div class="text-2xl text-gray-400">:</div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-[#1E63F8]">{{ String(timeRemaining.hours).padStart(2, '0') }}</span>
                                    <span class="text-xs text-gray-600">h</span>
                                </div>
                                <div class="text-2xl text-gray-400">:</div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-[#1E63F8]">{{ String(timeRemaining.minutes).padStart(2, '0') }}</span>
                                    <span class="text-xs text-gray-600">min</span>
                                </div>
                                <div class="text-2xl text-gray-400">:</div>
                                <div class="flex flex-col items-center">
                                    <span class="text-2xl font-bold text-[#1E63F8]">{{ String(timeRemaining.seconds).padStart(2, '0') }}</span>
                                    <span class="text-xs text-gray-600">sec</span>
                                </div>
                            </div>
                        </div>
                        <div v-else class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                            <p class="text-sm font-semibold text-red-700">🔒 Le vote pour cette réflexion est terminé</p>
                        </div>
                    </div>

                    <div>
                        <p v-if="!$page.props.auth.user" class="text-sm text-red-500 mt-4">
                            Connectez-vous pour voter.
                        </p>
                    </div>
                </div>

                <!-- FORMULAIRE COMMENTAIRE -->
                <CommentForm :reflection="reflection"/>
            </div>


            <!-- ==================== RIGHT SIDE — COMMENTS ==================== -->
            <div class="scroller mx-auto w-full lg:w-1/2 py-10 px-4 sm:px-6 lg:px-8 overflow-y-scroll h-screen">

                <h2 class="text-3xl font-bold text-white mb-6 flex items-center gap-3 dark:text-gray-100">
                    <span class="w-2 h-8 bg-[#1E63F8] rounded"></span>
                    Commentaires
                </h2>

                <!-- Aucun commentaire -->
                <div
                    v-if="comments.length === 0"
                    class="text-center p-6 bg-white/10 backdrop-blur border border-white/20 text-white rounded-xl italic">
                    Aucun commentaire pour le moment !
                </div>

                <!-- Commentaires -->
                <div v-else>
                    <div
                        v-for="comment in comments"
                        :key="comment.id"
                        class="mb-6 p-6 bg-white rounded-xl shadow-xl border-l-4 border-[#1E63F8]"
                    >

                        <!-- User -->
                        <div class="flex justify-between items-center mb-3">

                            <div class="flex items-center gap-4">
                                <img
                                    :src="comment.user.avatar"
                                    class="w-12 h-12 rounded-full border-2 border-[#1E63F8]"
                                />
                                <div>
                                    <p class="font-bold text-gray-800 text-lg">
                                        {{ comment.user.name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        {{ formatCommentDate(comment.created_at) }}
                                    </p>
                                </div>
                            </div>

                        </div>

                        <!-- Content -->
                        <p class="text-gray-700 leading-relaxed mb-4">
                            {{ comment.content }}
                        </p>

                        <!-- LIKE / DISLIKE -->
                        <div class="flex items-center gap-6 text-xl">

                            <span
                                @click="like(comment.id)"
                                class="cursor-pointer hover:scale-125 transition"
                                :class="{'text-green-600': comment.likes.find(l => l.user_id === $page.props.auth.user?.id)}"
                            >
                                👍
                            </span>

                            <span
                                @click="dislike(comment.id)"
                                class="cursor-pointer hover:scale-125 transition"
                                :class="{'text-red-600': comment.likes.find(l => l.user_id === $page.props.auth.user?.id && l.like === -1)}"
                            >
                                👎
                            </span>

                        </div>

                    </div>
                </div>

            </div>

        </Layout>
    </AuthenticatedLayout>
</template>


<script setup>
import { defineProps, onMounted, ref, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import CommentForm from './CommentForm.vue';
// import Layout from '@/Layouts/AppLayout.vue';

import Vote from "@/Components/Vote.vue";

const props = defineProps({
  reflection: Object,
  comments:Array,
  options:Array,
  isVoteEnded:Boolean,
  isAdmin:Boolean,
  existingVote:Number,

});

// État du compteur
const timeRemaining = ref({
  days: 0,
  hours: 0,
  minutes: 0,
  seconds: 0,
  isEnded: false,
});

let countdownInterval;

// Fonction pour calculer le temps restant
const calculateTimeRemaining = () => {
  if (!props.reflection || !props.reflection.date_fin_vote) {
    console.warn("date_fin_vote non disponible");
    return;
  }

  // Parser la date correctement (format ISO ou autre)
  const endDate = new Date(props.reflection.date_fin_vote);
  const now = new Date();

  console.log("Date de fin:", props.reflection.date_fin_vote);
  console.log("Date fin parsée:", endDate);
  console.log("Date maintenant:", now);

  const diff = endDate - now;

  console.log("Différence (ms):", diff);

  if (diff <= 0) {
    timeRemaining.value = {
      days: 0,
      hours: 0,
      minutes: 0,
      seconds: 0,
      isEnded: true,
    };
    if (countdownInterval) {
      clearInterval(countdownInterval);
    }
    return;
  }

  const days = Math.floor(diff / (1000 * 60 * 60 * 24));
  const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const seconds = Math.floor((diff % (1000 * 60)) / 1000);

  timeRemaining.value = {
    days,
    hours,
    minutes,
    seconds,
    isEnded: false,
  };
};

// Initialiser et démarrer le compteur au montage
onMounted(() => {
  if (props.reflection.date_fin_vote) {
    calculateTimeRemaining();
    countdownInterval = setInterval(calculateTimeRemaining, 1000);
  }
});

// Nettoyer l'intervalle au démontage
onUnmounted(() => {
  if (countdownInterval) {
    clearInterval(countdownInterval);
  }
});

function like(id){
    console.log(id);

    router.get(route('likeComment',id));
}
function dislike(id){
     console.log(id);
    router.get(route('dislikeComment',id));
}
// Formatage de la date des commentaires
const formatCommentDate = (dateString) => {
  const date = new Date(dateString);
  return date.toLocaleDateString("fr-FR");
}

function deleteRef(id) {
    console.log(id);
  router.delete(route("reflections.destroy", id));
}

function activeRef(id) {
  router.get(route("reflections.validate", id));
}
</script>

<style scoped>
.scroller {
    scrollbar-width: none;
    scrollbar-color: #a0aec0 transparent;
}
</style>
