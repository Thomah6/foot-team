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
      :href="route('admin.news.create')" 
      class="px-5 py-2 rounded-lg bg-green-600 hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600 transition text-white font-semibold shadow"
    >
      <i class="fas fa-plus"></i>
      Nouvelle actualité
    </Link>
  </div>

  <div class="flex flex-col lg:flex-row gap-6 min-h-screen">

    <!-- COLONNE GAUCHE — LISTE -->
    <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 border border-gray-200 dark:border-gray-700">
      <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100 flex items-center gap-2">
        <i class="fas fa-newspaper text-citron-500"></i>
        Actualités du mois
      </h2>

      <div class="space-y-3 max-h-[calc(100vh-200px)] overflow-y-auto pr-2">
        <div v-for="item in news" :key="item.id" @click="selected = item"
          :class="[
            'p-4 rounded-xl cursor-pointer transition-all duration-200 border',
            selected?.id === item.id
              ? 'bg-citron-50 dark:bg-citron-900/20 border-citron-500 shadow-sm'
              : 'bg-gray-50 dark:bg-gray-700 border-gray-200 dark:border-gray-600 hover:bg-citron-50 dark:hover:bg-gray-600'
          ]"
        >
          <div class="flex justify-between items-start">
            <div>
              <p class="font-semibold text-gray-800 dark:text-gray-100">{{ item.title }}</p>
              <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ formatDate(item.created_at) }}</p>
            </div>
            <span v-if="item.is_published" class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
              Publiée
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- PARTIE DROITE — GRAND PANEL -->
    <div class="w-full lg:w-2/3 bg-gradient-to-br from-citron-500 via-green-500 to-blue-600 dark:from-gray-900 dark:via-gray-800 dark:to-gray-700 rounded-xl shadow-xl p-6 flex flex-col">

      <!-- IMAGE -->
      <div class="w-full h-64 rounded-xl overflow-hidden shadow-lg mb-6 relative">
        <img
          :src="selected?.image ? `/storage/${selected.image}` : '/images/default.jpg'"
          class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
          alt="news image"
        />
      </div>

      <!-- TITRE -->
      <h1 class="text-3xl font-bold mb-4 text-white drop-shadow-lg">
        {{ selected?.title }}
      </h1>

      <!-- CONTENU -->
      <p class="text-white/90 text-base leading-relaxed mb-4">
        {{ selected?.content }}
      </p>

      <!-- AUTEUR + DATE -->
      <div class="text-white/70 text-sm mb-6">
        <i class="fas fa-user mr-2"></i>Rédigé par : {{ selected?.user?.name }} 
        <i class="fas fa-calendar ml-4 mr-2"></i>{{ formatDate(selected?.created_at) }}
      </div>

      <!-- ACTIONS -->
      <div class="flex flex-wrap gap-3 mt-auto pt-4 border-t border-white/20">
        <Link
          :href="`/admin/news/${selected?.id}/edit`"
          class="px-5 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2"
        >
          <i class="fas fa-edit"></i>Modifier
        </Link>

        <button
          @click="togglePublish(selected?.id)"
          :class="[
            'px-5 py-2 font-medium rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2',
            selected?.is_published 
              ? 'bg-yellow-500 hover:bg-yellow-600 text-black' 
              : 'bg-blue-500 hover:bg-blue-600 text-white'
          ]"
        >
          <i class="fas" :class="selected?.is_published ? 'fa-eye-slash' : 'fa-eye'"></i>
          {{ selected?.is_published ? 'Dépublier' : 'Publier' }}
        </button>

        <button
          @click="deleteNews(selected?.id)"
          class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg shadow-sm transition-all duration-200 flex items-center gap-2"
        >
          <i class="fas fa-trash"></i>Supprimer
        </button>
      </div>
    </div>

  </div>
</template>