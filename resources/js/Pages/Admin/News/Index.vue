<script setup>
import { ref } from "vue";
import { router, Link } from "@inertiajs/vue3";

const props = defineProps({
  news: Array
});

const news = props.news;

// Actualité sélectionnée (la dernière)
const selected = ref(
  [...news].sort((a, b) => new Date(b.created_at) - new Date(a.created_at))[0]
);

// Formatage de la date
const formatDate = (date) => {
  return new Date(date).toLocaleDateString("fr-FR", {
    day: "2-digit",
    month: "2-digit",
    year: "numeric",
  });
};

// Actions
const deleteNews = (id) => {
  if (confirm("Supprimer cette actualité ?")) {
    router.delete(`/admin/news/${id}`);
  }
};

const togglePublish = (id) => {
  router.patch(`/admin/news/${id}/toggle-banner`);
};
</script>

<template>
  <div class="flex flex-col h-screen bg-gray-100 dark:bg-gray-900 p-4 gap-4">

    <!-- Header -->
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Actualités</h1>
      <Link 
        :href="route('news.create')" 
        class="px-4 py-2 sm:px-5 sm:py-2.5 rounded-lg bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition text-white font-semibold shadow"
      >
        Nouvelle actualité
      </Link>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col md:flex-row flex-1 gap-4 overflow-hidden">

      <!-- COLONNE GAUCHE — LISTE -->
      <div class="md:w-1/3 bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg p-4 flex flex-col overflow-y-auto max-h-[70vh] md:max-h-full">
        <h2 class="text-lg md:text-xl font-bold mb-3 text-purple-600 dark:text-purple-400">Actualités du mois</h2>

        <div v-for="item in news" :key="item.id" @click="selected = item"
             :class="[
               'p-3 rounded-xl cursor-pointer transition mb-2 flex justify-between items-center',
               selected?.id === item.id
                 ? 'bg-purple-600 text-white shadow-md dark:bg-purple-500'
                 : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-gray-600'
             ]"
        >
          <p class="font-semibold text-sm sm:text-base">{{ item.title }}</p>
          <span class="text-xs sm:text-sm opacity-70">{{ formatDate(item.created_at) }}</span>
        </div>
      </div>

      <!-- PARTIE DROITE — GRAND PANEL -->
      <div class="md:w-2/3 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl p-4 md:p-6 flex flex-col overflow-hidden">

        <!-- IMAGE -->
        <div class="w-full h-56 sm:h-72 md:h-72 rounded-2xl overflow-hidden shadow-lg mb-4 md:mb-6 flex-shrink-0">
          <img
            :src="selected?.image ? `/storage/${selected.image}` : '/images/default.jpg'"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
            alt="news image"
          />
        </div>

        <!-- TITRE -->
        <h1 class="text-2xl sm:text-3xl font-extrabold mb-3 text-white drop-shadow-lg">
          {{ selected?.title }}
        </h1>

        <!-- CONTENU -->
        <p class="text-white/90 text-sm sm:text-base leading-relaxed mb-4 flex-1 overflow-auto">
          {{ selected?.content }}
        </p>

        <!-- AUTEUR + DATE -->
        <div class="text-white/70 text-xs sm:text-sm mb-4">
          Rédigé par : {{ selected?.user?.name }} • {{ formatDate(selected?.created_at) }}
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-4 mt-auto">
          <Link
            :href="`/admin/news/${selected?.id}/edit`"
            class="flex-1 px-4 py-2 sm:px-5 sm:py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg shadow text-white font-semibold transition text-center"
          >
            Modifier
          </Link>

          <button
            @click="togglePublish(selected?.id)"
            class="flex-1 px-4 py-2 sm:px-5 sm:py-2.5 bg-yellow-500 hover:bg-yellow-400 dark:bg-yellow-400 dark:hover:bg-yellow-500 rounded-lg shadow text-black font-semibold transition"
          >
            {{ selected?.is_published ? 'Dépublier' : 'Publier' }}
          </button>

          <button
            @click="deleteNews(selected?.id)"
            class="flex-1 px-4 py-2 sm:px-5 sm:py-2.5 bg-red-600 hover:bg-red-500 dark:bg-red-500 dark:hover:bg-red-600 rounded-lg shadow text-white font-semibold transition"
          >
            Supprimer
          </button>
        </div>

      </div>
    </div>
  </div>
</template>
