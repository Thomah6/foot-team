<script setup>
import { ref } from "vue";
import { router, Link } from "@inertiajs/vue3";
import Button from '@/Components/Button.vue';

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

const isAdmin = () => page.props.auth.user.role === "admin";

</script>

<template>
  <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4 sm:p-6 gap-4 sm:gap-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
          <i class="fas fa-newspaper text-lime-600"></i>
          Actualités
        </h1>
        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Gérez les actualités du club</p>
      </div>
      <Button :href="route('admin.news.create')" variant="primary" size="md">
        <template #icon>
          <i class="fas fa-plus"></i>
        </template>
        Nouvelle actualité
      </Button>
    </div>

    <!-- Main Content -->
    <div class="flex flex-col lg:flex-row gap-4 sm:gap-6 flex-1 min-h-0">

      <!-- COLONNE GAUCHE — LISTE -->
      <div class="lg:w-1/3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col overflow-hidden">
        <h2 class="text-lg sm:text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center gap-2">
          <span class="w-1 h-6 bg-gradient-to-b from-lime-400 to-emerald-500 rounded-full"></span>
          Actualités du mois
        </h2>

        <div class="overflow-y-auto flex-1 space-y-2">
          <div v-for="item in news" :key="item.id" @click="selected = item"
               :class="[
                 'p-3 sm:p-4 rounded-xl cursor-pointer transition duration-200 flex flex-col gap-1',
                 selected?.id === item.id
                   ? 'bg-gradient-to-r from-emerald-600 to-lime-600 text-white shadow-lg'
                   : 'bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-lime-50 dark:hover:bg-gray-600'
               ]"
          >
            <p class="font-semibold text-sm sm:text-base line-clamp-2">{{ item.title }}</p>
            <span class="text-xs opacity-70">{{ formatDate(item.created_at) }}</span>
          </div>
        </div>
      </div>

      <!-- PARTIE DROITE — GRAND PANEL -->
      <div class="lg:w-2/3 bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 sm:p-6 flex flex-col overflow-hidden">

        <!-- IMAGE -->
        <div class="w-full h-40 sm:h-56 md:h-64 rounded-xl overflow-hidden shadow-lg mb-4 sm:mb-6 flex-shrink-0 bg-gray-100 dark:bg-gray-700">
          <img
            :src="selected?.image ? `/storage/${selected.image}` : '/images/default.jpg'"
            class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
            alt="news image"
          />
        </div>

        <!-- TITRE -->
        <h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold mb-3 text-gray-900 dark:text-white">
          {{ selected?.title }}
        </h1>

        <!-- CONTENU -->
        <p class="text-gray-700 dark:text-gray-300 text-xs sm:text-sm md:text-base leading-relaxed mb-4 flex-1 overflow-auto">
          {{ selected?.content }}
        </p>

        <!-- AUTEUR + DATE -->
        <div class="text-gray-600 dark:text-gray-400 text-xs sm:text-sm mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
          <i class="fas fa-user mr-2 text-emerald-600"></i>{{ selected?.user?.name }} • {{ formatDate(selected?.created_at) }}
        </div>

        <!-- ACTIONS -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mt-auto">
          <Button :href="`/admin/news/${selected?.id}/edit`" variant="secondary" size="md">
            <template #icon>
              <i class="fas fa-edit"></i>
            </template>
            Modifier
          </Button>

          <Button @click="togglePublish(selected?.id)" :variant="selected?.is_published ? 'secondary' : 'primary'" size="md">
            <template #icon>
              <i :class="['fas', selected?.is_published ? 'fa-eye-slash' : 'fa-eye']"></i>
            </template>
            {{ selected?.is_published ? 'Dépublier' : 'Publier' }}
          </Button>

          <Button @click="deleteNews(selected?.id)" variant="danger" size="md">
            <template #icon>
              <i class="fas fa-trash"></i>
            </template>
            Supprimer
          </Button>
        </div>

      </div>
    </div>
  </div>
</template>
