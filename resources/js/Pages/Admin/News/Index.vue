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
  <!-- Header -->
  <div class="flex items-center justify-between mb-6">
    <Link 
      :href="route('news.create')" 
      class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition text-white font-semibold shadow"
    >
      Nouvelle actualité
    </Link>
  </div>

  <div class="flex gap-6 min-h-screen">

    <!-- ===================== -->
    <!-- COLONNE GAUCHE — LISTE -->
    <!-- ===================== -->
    <div class="w-1/3 bg-gray-100 dark:bg-gray-800 rounded-2xl shadow-lg p-4 overflow-y-auto max-h-screen">
      <h2 class="text-xl font-bold mb-4 text-purple-600 dark:text-purple-400">Actualités du mois</h2>

      <div v-for="item in news" :key="item.id" @click="selected = item"
        :class="[
          'p-3 rounded-xl cursor-pointer transition mb-2 flex justify-between items-center',
          selected?.id === item.id
            ? 'bg-purple-600 text-white shadow-md dark:bg-purple-500'
            : 'bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-purple-50 dark:hover:bg-gray-600'
        ]"
      >
        <p class="font-semibold">{{ item.title }}</p>
        <span class="text-sm opacity-70">{{ formatDate(item.created_at) }}</span>
      </div>
    </div>

    <!-- ===================== -->
    <!-- PARTIE DROITE — GRAND PANEL -->
    <!-- ===================== -->
    <div class="w-2/3 bg-gradient-to-br from-purple-600 via-blue-600 to-indigo-700 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-2xl shadow-xl p-6 flex flex-col overflow-hidden">

      <!-- IMAGE -->
      <div class="w-full h-72 rounded-2xl overflow-hidden shadow-lg mb-6 relative">
        <img
          :src="selected?.image ? `/storage/${selected.image}` : '/images/default.jpg'"
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
          alt="news image"
        />
      </div>

      <!-- TITRE -->
      <h1 class="text-3xl font-extrabold mb-4 text-white drop-shadow-lg">
        {{ selected?.title }}
      </h1>

      <!-- CONTENU -->
      <p class="text-white/90 text-lg leading-relaxed mb-4">
        {{ selected?.content }}
      </p>

      <!-- AUTEUR + DATE -->
      <div class="text-white/70 text-sm mb-6">
        Rédigé par : {{ selected?.user?.name }} • {{ formatDate(selected?.created_at) }}
      </div>

      <!-- ACTIONS -->
      <div class="flex gap-4 mt-auto">
        <Link
          :href="`/admin/news/${selected?.id}/edit`"
          class="px-5 py-2 bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 rounded-lg shadow text-white font-semibold transition"
        >
          Modifier
        </Link>

        <button
          @click="togglePublish(selected?.id)"
          class="px-5 py-2 bg-yellow-500 hover:bg-yellow-400 dark:bg-yellow-400 dark:hover:bg-yellow-500 rounded-lg shadow text-black font-semibold transition"
        >
          {{ selected?.is_published ? 'Dépublier' : 'Publier' }}
        </button>

        <button
          @click="deleteNews(selected?.id)"
          class="px-5 py-2 bg-red-600 hover:bg-red-500 dark:bg-red-500 dark:hover:bg-red-600 rounded-lg shadow text-white font-semibold transition"
        >
          Supprimer
        </button>
      </div>
    </div>

  </div>
</template>
