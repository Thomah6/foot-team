<script setup>
import { router } from "@inertiajs/vue3";
import { Link } from '@inertiajs/vue3';

defineProps({
  news: Array
});

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
  <div class="p-6 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100">

    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold">Actualités</h1>

      <Link 
        :href="route('news.create')" 
        class="px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 transition text-white font-semibold shadow"
      >
        Nouvelle actualité
      </Link>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-lg shadow-md bg-white dark:bg-gray-800">
      <table class="w-full border-collapse">
        
        <thead>
          <tr class="bg-gray-100 dark:bg-gray-700 text-left text-gray-800 dark:text-gray-100">
            <th class="p-3 border-b border-gray-300 dark:border-gray-600">Titre</th>
            <th class="p-3 border-b border-gray-300 dark:border-gray-600">Contenu</th>
            <th class="p-3 border-b border-gray-300 dark:border-gray-600">Auteur</th>
            <th class="p-3 border-b border-gray-300 dark:border-gray-600">Publié</th>
            <th class="p-3 border-b border-gray-300 dark:border-gray-600">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr 
            v-for="item in news" 
            :key="item.id" 
            class="hover:bg-gray-50 dark:hover:bg-gray-700 transition"
          >
            <td class="p-3 border-b border-gray-200 dark:border-gray-700 font-medium">
              {{ item.title }}
            </td>

            <td class="p-3 border-b border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300">
              {{ item.content }}
            </td>

            <td class="p-3 border-b border-gray-200 dark:border-gray-700">
              {{ item.user?.name }}
            </td>

            <td class="p-3 border-b border-gray-200 dark:border-gray-700">
              <span 
                :class="item.is_published 
                  ? 'text-green-500 font-semibold' 
                  : 'text-red-500 font-semibold'"
              >
                {{ item.is_published ? 'Oui' : 'Non' }}
              </span>
            </td>

            <td class="p-3 border-b border-gray-200 dark:border-gray-700 space-x-3">
              <Link 
                :href="`/admin/news/${item.id}/edit`" 
                class="text-blue-600 dark:text-blue-400 hover:underline font-medium"
              >
                Modifier
              </Link>

              <button 
                @click="togglePublish(item.id)" 
                class="text-yellow-600 dark:text-yellow-400 hover:underline font-medium"
              >
                {{ item.is_published ? "Dépublier" : "Publier" }}
              </button>

              <button 
                @click="deleteNews(item.id)" 
                class="text-red-600 dark:text-red-400 hover:underline font-medium"
              >
                Supprimer
              </button>
            </td>
          </tr>
        </tbody>

      </table>
    </div>

    <!-- Back -->
    <div class="mt-6">
      <Link 
        :href="route('dashboard')" 
        class="text-blue-600 dark:text-blue-400 hover:underline font-medium"
      >
        ← Retour
      </Link>
    </div>

  </div>
</template>
